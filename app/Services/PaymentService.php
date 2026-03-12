<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Booking;
use App\Models\User;
use App\Models\VenueSchedule;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentService
{
    private $midtransConfig;
    private $japatiConfig;
    
    public function __construct()
    {
        $this->loadConfig();
    }
    
    /**
     * Load configuration from database
     */
    private function loadConfig()
    {
        $setting = Setting::first();
        
        if (!$setting) {
            throw new \Exception('Setting configuration not found in database');
        }
        
        // Midtrans Configuration
        Config::$serverKey = $setting->midtrans_server_key;
        Config::$clientKey = $setting->midtrans_client_key;
        Config::$isProduction = (bool) $setting->midtrans_is_production;
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        $this->midtransConfig = [
            'server_key' => $setting->midtrans_server_key,
            'client_key' => $setting->midtrans_client_key,
            'is_production' => (bool) $setting->midtrans_is_production
        ];
        
        // Japati Configuration
        $this->japatiConfig = [
            'api_token' => $setting->japati_api_token,
            'gateway_number' => $setting->japati_gateway_number,
            'is_configured' => !empty($setting->japati_api_token) && !empty($setting->japati_gateway_number)
        ];
    }
    
    /**
     * Create Snap payment for booking
     */
    public function createSnapPayment(Booking $booking, User $user)
    {
        try {
            $orderId = 'BK-' . $booking->id . '-' . time();
            $amount = $booking->schedule->rental_price ?? 0;
            
            // Validasi amount harus lebih dari 0
            if ($amount <= 0) {
                throw new \Exception('Invalid payment amount');
            }
            
            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
            ];
            
            // Prepare customer details
            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ];
            
            // Prepare item details
            $itemDetails = [
                [
                    'id' => 'VENUE-' . $booking->venue_id,
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Booking ' . ($booking->venue->venue_name ?? 'Venue'),
                    'category' => 'Sports Venue Booking'
                ]
            ];
            
            // Prepare Snap parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'expiry' => [
                    'start_time' => date("Y-m-d H:i:s O"),
                    'unit' => 'hours',
                    'duration' => 24
                ]
            ];
            
            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // Update booking with payment info
            $booking->update([
                'midtrans_order_id' => $orderId,
                'payment_token' => $snapToken,
                'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'amount' => $amount,
                'midtrans_transaction_status' => 'pending',
                'payment_status' => 'pending',
                'status' => 'pending'
            ]);
            
            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'client_key' => $this->midtransConfig['client_key'],
                'is_production' => $this->midtransConfig['is_production']
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            throw new \Exception('Failed to create payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle Midtrans notification/callback
     */
    public function handleNotification($notification)
    {
        try {
            // Verify notification using Midtrans Config
            Config::$serverKey = $this->midtransConfig['server_key'];
            Config::$isProduction = $this->midtransConfig['is_production'];
            
            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;
            
            // Find booking by order_id
            $booking = Booking::where('midtrans_order_id', $orderId)->first();
            
            if (!$booking) {
                return ['status' => 'error', 'message' => 'Booking not found'];
            }
            
            // Update booking based on transaction status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $booking->update([
                            'midtrans_transaction_status' => 'challenge',
                            'payment_status' => 'pending'
                        ]);
                    } else {
                        $booking->update([
                            'midtrans_transaction_status' => 'settlement',
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'status' => 'confirmed'
                        ]);
                        
                        // Generate QR Code and send WhatsApp
                        $this->generateAndSendTicket($booking);
                    }
                }
            } elseif ($transaction == 'settlement') {
                $booking->update([
                    'midtrans_transaction_status' => 'settlement',
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'confirmed'
                ]);
                
                // Generate QR Code and send WhatsApp
                $this->generateAndSendTicket($booking);
                
            } elseif ($transaction == 'pending') {
                $booking->update([
                    'midtrans_transaction_status' => 'pending',
                    'payment_status' => 'pending'
                ]);
                
            } elseif ($transaction == 'deny') {
                $booking->update([
                    'midtrans_transaction_status' => 'deny',
                    'payment_status' => 'pending'
                ]);
                
            } elseif ($transaction == 'expire') {
                $booking->update([
                    'midtrans_transaction_status' => 'expire',
                    'payment_status' => 'pending',
                    'status' => 'cancelled'
                ]);
                
                // Kembalikan jadwal menjadi available
                $this->releaseSchedule($booking);
                
            } elseif ($transaction == 'cancel') {
                $booking->update([
                    'midtrans_transaction_status' => 'cancel',
                    'payment_status' => 'pending',
                    'status' => 'cancelled'
                ]);
                
                // Kembalikan jadwal menjadi available
                $this->releaseSchedule($booking);
            }
            
            return [
                'status' => 'success', 
                'booking_id' => $booking->id,
                'transaction_status' => $transaction
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Release schedule when payment fails/cancels
     */
    private function releaseSchedule(Booking $booking)
    {
        try {
            $schedule = VenueSchedule::find($booking->schedule_id);
            if ($schedule) {
                $schedule->update(['available' => true]);
                Log::info('Schedule released for booking: ' . $booking->id);
            }
        } catch (\Exception $e) {
            Log::error('Release schedule error: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate QR Code and send WhatsApp notification
     */
    public function generateAndSendTicket(Booking $booking)
    {
        try {
            // Generate QR Code
            $qrCodeData = [
                'ticket_code' => $booking->ticket_code,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'venue_id' => $booking->venue_id,
                'schedule_id' => $booking->schedule_id,
                'venue' => $booking->venue->venue_name ?? 'Unknown Venue',
                'date' => $booking->schedule->date ?? now()->toDateString(),
                'start_time' => $booking->schedule->start_time ?? '00:00',
                'end_time' => $booking->schedule->end_time ?? '00:00',
                'amount' => $booking->amount,
                'timestamp' => now()->toISOString()
            ];
            
            $qrCodeString = json_encode($qrCodeData);
            
            // Generate QR Code image (format PNG)
            $qrCode = QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrCodeString);
            
            // Save QR Code to storage
            $qrCodeFileName = 'qrcode_' . $booking->ticket_code . '.png';
            $qrCodePath = 'public/qrcodes/' . $qrCodeFileName;
            
            Storage::put($qrCodePath, $qrCode);
            
            // Generate QR Code untuk WhatsApp (format SVG/Text)
            $qrCodeForWhatsApp = QrCode::size(200)
                ->generate($qrCodeString);
            
            // Send WhatsApp notification
            $this->sendWhatsAppNotification($booking, $qrCodeForWhatsApp);
            
            Log::info('Ticket generated for booking: ' . $booking->id);
            
        } catch (\Exception $e) {
            Log::error('Generate Ticket Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send WhatsApp notification with Japati
     */
    private function sendWhatsAppNotification(Booking $booking)
    {
        try {
            $user = $booking->user;
            $venue = $booking->venue;
            $schedule = $booking->schedule;

            if (!$user || !$user->phone) {
                Log::warning('User phone number not found for booking: ' . $booking->id);
                return false;
            }

            // Format date and time
            $formattedDate = $schedule->date ? \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, j F Y') : 'N/A';
            $startTime = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '00:00';
            $endTime = $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '00:00';

            $message  = "*PEMBAYARAN BERHASIL*\n\n";
            $message .= "Halo " . $user->name . ",\n";
            $message .= "Pembayaran booking Anda telah berhasil diverifikasi.\n\n";
            $message .= "DETAIL TIKET\n";
            $message .= "Kode Tiket: " . $booking->ticket_code . "\n";
            $message .= "Venue: " . ($venue->venue_name ?? 'N/A') . "\n";
            $message .= "Lokasi: " . ($venue->location ?? 'N/A') . "\n";
            $message .= "Tanggal: " . $formattedDate . "\n";
            $message .= "Waktu: " . $startTime . " - " . $endTime . "\n";
            $message .= "Total: Rp " . number_format($booking->amount ?? 0, 0, ',', '.') . "\n";
            $message .= "Status: CONFIRMED\n\n";

            $message .= "INFORMASI CHECK-IN\n";
            $message .= "Tunjukkan kode berikut kepada staff venue:\n";
            $message .= "*" . $booking->ticket_code . "*\n\n";

            $message .= "Pesan ini dikirim otomatis oleh sistem.\n";
            $message .= "Tim SewaLapangan";

            // Gunakan global helper kirimWa
            if (function_exists('kirimWa')) {
                return kirimWa($user->phone, $message);
            }

            Log::error('Fungsi kirimWa tidak ditemukan.');
            return false;

        } catch (\Exception $e) {
            Log::error('Send WhatsApp Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check payment status from Midtrans API
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            Config::$serverKey = $this->midtransConfig['server_key'];
            Config::$isProduction = $this->midtransConfig['is_production'];
            
            $status = \Midtrans\Transaction::status($orderId);
            
            return [
                'success' => true,
                'status' => $status->transaction_status,
                'data' => $status
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get QR Code for display
     */
    public function getQrCode(Booking $booking)
    {
        try {
            $qrCodeData = [
                'ticket_code' => $booking->ticket_code,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'venue' => $booking->venue->venue_name ?? 'Unknown',
                'date' => $booking->schedule->date ?? '',
                'time' => $booking->schedule->start_time ?? ''
            ];
            
            $qrCode = QrCode::size(250)
                ->format('png')
                ->generate(json_encode($qrCodeData));
            
            return [
                'success' => true,
                'qr_code' => 'data:image/png;base64,' . base64_encode($qrCode),
                'ticket_code' => $booking->ticket_code
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}