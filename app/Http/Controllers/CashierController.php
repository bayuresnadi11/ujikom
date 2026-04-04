<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Venue;
use App\Models\VenueSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\VenueSection;
use App\Models\Setting;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard
     */
    public function index()
    {
        $penyewas = User::where('role', 'buyer')->get();
        $totalPenyewa = $penyewas->count();
        $sections = VenueSection::all();
        $venues = Venue::all();

        return view('cashier.dashboard.index', compact('penyewas', 'totalPenyewa', 'sections', 'venues'));
    }

    /**
     * Display QR scanner page
     */
    public function scan()
    {
        $sections = VenueSection::all();
        $venues = Venue::all();

        return view('cashier.scan.scan', compact('sections', 'venues'));
    }

    /**
     * Display queue/booking page
     */
    public function queue()
    {
        $categories = Category::all();
        $sections = VenueSection::all();
        $venues = Venue::all();

        $setting = Setting::first();
        $midtransClientKey = $setting->midtrans_client_key ?? '';
        $isProduction = (bool)($setting->midtrans_is_production ?? false);
        return view('cashier.queue.index', compact('categories', 'sections', 'venues', 'midtransClientKey', 'isProduction'));
    }

    public function processPayment(Request $request)
{
    try {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'community' => 'nullable|string',
            'method' => 'required|in:cash,midtrans',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
            'schedules' => 'required|array|min:1',
            'schedules.*.schedule_id' => 'required|exists:venue_schedules,id',
            'schedules.*.venue_name' => 'nullable|string',
            'schedules.*.section_name' => 'nullable|string'
        ]);

        DB::beginTransaction();

        $bookingIds = [];
        $totalAmount = 0;

        // Generate unique transaction code
        $transactionCode = Booking::generateTicketCode();

        // Create bookings for each schedule
        foreach ($validated['schedules'] as $scheduleData) {
            $schedule = VenueSchedule::with(['section.venue'])->findOrFail($scheduleData['schedule_id']);
            
            // Check if schedule is still available
            if (!$schedule->available) {
                throw new \Exception("Jadwal {$schedule->start_time} - {$schedule->end_time} sudah tidak tersedia");
            }

            // Create booking
            $booking = Booking::create([
                'type' => 'regular',
                'user_id' => $validated['buyer_id'],
                'venue_id' => $schedule->section->venue_id,
                'schedule_id' => $schedule->id,
                'ticket_code' => $transactionCode, // Gunakan transaction code yang sama untuk semua booking
                'amount' => $schedule->rental_price,
                'method' => $validated['method'],
                'total_paid' => 0,
                'paid_amount' => null,
                'change' => null,
                'booking_payment' => 'pending',
                'pay_by' => null,
                'community' => $validated['community'] ?? null,
            ]);

            $bookingIds[] = $booking->id;
            $totalAmount += $schedule->rental_price;
        }

        // Handle Cash Payment
        if ($validated['method'] === 'cash') {
            $paidAmount = $validated['paid_amount'] ?? 0;
            $change = $paidAmount - $totalAmount;

            // Update all bookings to paid
            foreach ($bookingIds as $bookingId) {
                $booking = Booking::find($bookingId);
                $booking->update([
                    'total_paid' => $booking->amount,
                    'paid_amount' => $paidAmount / count($bookingIds), // Split paid amount
                    'change' => $change > 0 ? ($change / count($bookingIds)) : null,
                    'booking_payment' => 'full',
                    'paid_at' => now()
                ]);

                // Mark schedule as unavailable
                $booking->schedule->update(['available' => false]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran cash berhasil',
                'transaction_code' => $transactionCode, // Kirim transaction code
                'booking_ids' => $bookingIds,
                'payment_method' => 'cash'
            ]);
        }

        // Handle Midtrans Payment
        if ($validated['method'] === 'midtrans') {
            // Load Midtrans config from settings
            $setting = Setting::first();
            if (!$setting || !$setting->midtrans_server_key) {
                throw new \Exception('Konfigurasi pembayaran Midtrans belum diatur.');
            }

            Config::$serverKey = $setting->midtrans_server_key;
            Config::$isProduction = (bool)$setting->midtrans_is_production;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Create Midtrans transaction - gunakan transaction code sebagai order ID
            $midtransOrderId = $transactionCode;
            
            $itemDetails = [];
            foreach ($validated['schedules'] as $index => $scheduleData) {
                $schedule = VenueSchedule::with(['section.venue'])->find($scheduleData['schedule_id']);
                
                // Get venue and section name
                $venueName = $schedule->section->venue->venue_name ?? 'Venue';
                $sectionName = $schedule->section->section_name ?? 'Section';
                
                $itemDetails[] = [
                    'id' => $schedule->id,
                    'price' => $schedule->rental_price,
                    'quantity' => 1,
                    'name' => "{$venueName} - {$sectionName} ({$schedule->start_time}-{$schedule->end_time})"
                ];
            }

            $buyer = User::find($validated['buyer_id']);

            $transactionDetails = [
                'order_id' => $midtransOrderId,
                'gross_amount' => $totalAmount,
            ];

            $customerDetails = [
                'first_name' => $buyer->name,
                'email' => $buyer->email ?? 'noemail@example.com',
                'phone' => $buyer->phone ?? '08123456789',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('cashier.payment.finish')
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                
                // Store Midtrans order ID and ticket code
                foreach ($bookingIds as $bookingId) {
                    Booking::where('id', $bookingId)->update([
                        'ticket_code' => $transactionCode,
                        'midtrans_order_id' => $midtransOrderId // Simpan order ID Midtrans terpisah
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Silakan selesaikan pembayaran',
                    'snap_token' => $snapToken,
                    'transaction_code' => $transactionCode, // Kirim transaction code yang sama
                    'order_id' => $midtransOrderId, // Juga kirim order ID untuk Midtrans
                    'booking_ids' => $bookingIds,
                    'payment_method' => 'midtrans'
                ]);

            } catch (\Exception $e) {
                throw new \Exception('Gagal membuat transaksi Midtrans: ' . $e->getMessage());
            }
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Payment processing error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function updatePaymentStatus(Request $request)
{
    try {
        $orderId = $request->order_id;
        $status = $request->status;
        $transactionCode = $request->transaction_code;
        
        if (!$orderId && !$transactionCode) {
            return response()->json(['success' => false, 'message' => 'Order ID atau Transaction Code diperlukan'], 400);
        }

        // Load config
        $setting = Setting::first();
        if (!$setting || !$setting->midtrans_server_key) {
            return response()->json(['success' => false, 'message' => 'Config error'], 500);
        }

        Config::$serverKey = $setting->midtrans_server_key;
        Config::$isProduction = (bool)$setting->midtrans_is_production;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Jika ada status dari request, gunakan itu
        // Jika tidak, cek status dari Midtrans API
        if (!$status) {
            try {
                $midtransStatus = Transaction::status($orderId);
                $transactionStatus = $midtransStatus->transaction_status;
                $fraudStatus = $midtransStatus->fraud_status ?? null;
                
                // Map Midtrans status ke status internal
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'challenge') {
                        $status = 'pending';
                    } else if ($fraudStatus == 'accept') {
                        $status = 'success';
                    }
                } else if ($transactionStatus == 'settlement') {
                    $status = 'success';
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $status = 'failed';
                } else if ($transactionStatus == 'pending') {
                    $status = 'pending';
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Transaction not found in Midtrans: ' . $e->getMessage()], 404);
            }
        }
        
        // Map status ke booking_payment
        $paymentStatusMap = [
            'success' => 'full',
            'pending' => 'pending',
            'failed' => 'failed'
        ];
        
        $bookingPaymentStatus = $paymentStatusMap[$status] ?? 'pending';
        
        // Cari bookings berdasarkan transaction code atau order id
        $bookings = Booking::where('ticket_code', $transactionCode)
            ->orWhere('midtrans_order_id', $orderId)
            ->orWhere('ticket_code', $orderId) // Untuk backward compatibility
            ->get();
        
        if ($bookings->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Bookings not found'], 404);
        }

        DB::beginTransaction();
        $bookingIds = [];
        
        foreach ($bookings as $booking) {
            $bookingIds[] = $booking->id;
            
            $updateData = [
                'booking_payment' => $bookingPaymentStatus,
                'total_paid' => $bookingPaymentStatus === 'full' ? $booking->amount : 0,
                'paid_at' => $bookingPaymentStatus === 'full' ? now() : null
            ];
            
            // Jika pembayaran sukses, update schedule availability
            if ($bookingPaymentStatus === 'full') {
                if ($booking->schedule) {
                    $booking->schedule->update(['available' => false]);
                }
            }
            
            $booking->update($updateData);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment status updated',
            'status' => $status,
            'booking_payment_status' => $bookingPaymentStatus,
            'transaction_code' => $bookings->first()->ticket_code,
            'booking_ids' => $bookingIds
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Update Payment Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function midtransCallback(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $signatureKey = $request->signature_key;

            // Verify signature
            $serverKey = config('midtrans.server_key');
            $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($hash !== $signatureKey) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Update bookings based on order_id
            $bookings = Booking::where('ticket_code', $orderId)->get();

            if ($bookings->isEmpty()) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? null;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $paymentStatus = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $paymentStatus = 'full';
                }
            } else if ($transactionStatus == 'settlement') {
                $paymentStatus = 'full';
            } else if ($transactionStatus == 'cancel' || 
                       $transactionStatus == 'deny' || 
                       $transactionStatus == 'expire') {
                $paymentStatus = 'pending';
            } else if ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
            }

            // Update all bookings
            foreach ($bookings as $booking) {
                $booking->update([
                    'booking_payment' => $paymentStatus,
                    'total_paid' => $paymentStatus === 'full' ? $booking->amount : 0,
                    'paid_amount' => null, // Midtrans doesn't need this
                    'change' => null, // Midtrans doesn't have change
                    'paid_at' => $paymentStatus === 'full' ? now() : null
                ]);

                // Mark schedule as unavailable if payment successful
                if ($paymentStatus === 'full') {
                    $booking->schedule->update(['available' => false]);
                }
            }

            return response()->json(['message' => 'Callback processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }



    public function paymentFinish(Request $request)
    {
        return redirect()->route('cashier.queue.index')
            ->with('success', 'Pembayaran berhasil diproses');
    }
    /**
     * Search venues (AJAX endpoint)
     * Kasir hanya bisa lihat venue milik owner-nya
     */
    public function search(Request $request)
    {
        try {
            $user = Auth::user();
            
            // 🐛 DEBUG LOG
            Log::info('Cashier Search Request', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'user_created_by' => $user->created_by,
                'request_params' => $request->all()
            ]);

            // Tentukan owner_id berdasarkan role
            if ($user->role === 'cashier') {
                // Kasir → ambil dari created_by (id landowner yang membuat kasir ini)
                $ownerId = $user->created_by;
            } elseif ($user->role === 'landowner') {
                // Landowner → pakai id sendiri
                $ownerId = $user->id;
            } else {
                // Role lain tidak bisa akses
                Log::warning('Unauthorized role accessing cashier search', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);
                return response()->json([]);
            }

            // Validasi owner_id
            if (!$ownerId) {
                Log::error('Owner ID is null', [
                    'user_id' => $user->id,
                    'user_created_by' => $user->created_by
                ]);
                return response()->json([]);
            }

            // 🐛 DEBUG: Cek total venues milik owner ini
            $totalVenues = Venue::where('created_by', $ownerId)->count();
            Log::info('Total venues for owner', [
                'owner_id' => $ownerId,
                'total_venues' => $totalVenues
            ]);

            // Query venues dengan filter
            $query = Venue::with('category')
                ->where('created_by', $ownerId);

            // Filter kategori
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter keyword
            if ($request->filled('keyword')) {
                $query->where('venue_name', 'like', '%' . $request->keyword . '%');
            }

            $venues = $query->orderBy('venue_name')->get();

            // 🐛 DEBUG LOG hasil
            Log::info('Venues Search Result', [
                'owner_id' => $ownerId,
                'total_found' => $venues->count(),
                'venues' => $venues->pluck('venue_name')
            ]);

            return response()->json($venues);

        } catch (\Exception $e) {
            Log::error('Error in cashier search', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sections by venue ID
     */
    public function getSections($venueId)
    {
        try {
            $user = Auth::user();
            
            // Validasi apakah venue milik owner dari kasir ini
            $venue = Venue::find($venueId);
            
            if (!$venue) {
                return response()->json([
                    'error' => 'Venue tidak ditemukan'
                ], 404);
            }
            
            // Cek kepemilikan
            $ownerId = $user->role === 'cashier' ? $user->created_by : $user->id;
            
            if ($venue->created_by != $ownerId) {
                return response()->json([
                    'error' => 'Tidak memiliki akses ke venue ini'
                ], 403);
            }
            
            // Ambil sections
            $sections = \App\Models\VenueSection::where('venue_id', $venueId)
                ->orderBy('section_name')
                ->get();
            
            return response()->json($sections);
            
        } catch (\Exception $e) {
            Log::error('Error loading sections', [
                'venue_id' => $venueId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Gagal memuat sections',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get schedules by section ID
     */
    public function getSchedules($sectionId)
    {
        try {
            $user = Auth::user();
            $ownerId = $user->role === 'cashier' ? $user->created_by : $user->id;
            
            Log::info('Loading schedules', [
                'section_id' => $sectionId,
                'user_id' => $user->id,
                'owner_id' => $ownerId
            ]);
            
            // Validasi section ownership
            $section = \App\Models\VenueSection::with('venue')->find($sectionId);
            
            if (!$section) {
                Log::error('Section not found', ['section_id' => $sectionId]);
                return response()->json([
                    'error' => 'Section tidak ditemukan'
                ], 404);
            }
            
            if ($section->venue->created_by != $ownerId) {
                Log::warning('Unauthorized access to section', [
                    'section_id' => $sectionId,
                    'venue_owner' => $section->venue->created_by,
                    'user_owner' => $ownerId
                ]);
                return response()->json([
                    'error' => 'Tidak memiliki akses'
                ], 403);
            }
            
            // Ambil schedules untuk 7 hari ke depan (untuk date filter)
            $now = Carbon::now();
            $today = $now->toDateString();
            $sevenDaysLater = $now->copy()->addDays(7)->toDateString();
            $currentTime = $now->format('H:i:s');

            $schedules = \App\Models\VenueSchedule::where('section_id', $sectionId)
                ->whereBetween('date', [$today, $sevenDaysLater])
                ->where(function ($query) use ($today, $currentTime) {
                    $query
                        // Jadwal hari depan → aman
                        ->where('date', '>', $today)

                        // Jadwal hari ini tapi belum lewat
                        ->orWhere(function ($q) use ($today, $currentTime) {
                            $q->where('date', $today)
                            ->where('start_time', '>', $currentTime);
                        });
                })
                ->where('available', true)
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();
            
            Log::info('Schedules loaded', [
                'section_id' => $sectionId,
                'date_range' => "$today to $sevenDaysLater",
                'count' => $schedules->count(),
                'dates' => $schedules->pluck('date')->unique()->values()
            ]);
            
            return response()->json($schedules);
            
        } catch (\Exception $e) {
            Log::error('Error loading schedules', [
                'section_id' => $sectionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Gagal memuat schedules',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Search buyers (AJAX endpoint for autocomplete)
     */
    public function searchBuyers(Request $request)
    {
        try {
            $keyword = $request->input('keyword', '');
            
            if (empty($keyword)) {
                return response()->json([]);
            }
            
            // Search buyers by name or phone
            $buyers = User::where('role', 'buyer')
                ->where(function($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')
                          ->orWhere('phone', 'like', '%' . $keyword . '%');
                })
                ->select('id', 'name', 'phone')
                ->limit(10)
                ->get();
            
            return response()->json($buyers);
            
        } catch (\Exception $e) {
            Log::error('Error searching buyers', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Gagal mencari penyewa',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}