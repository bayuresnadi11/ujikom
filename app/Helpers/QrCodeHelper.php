<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeHelper
{
    /**
     * Generate QR Code for booking
     */
    public static function generateBookingQrCode($booking)
    {
        try {
            $data = [
                'type' => 'booking_ticket',
                'ticket_code' => $booking->ticket_code,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'venue_id' => $booking->venue_id,
                'schedule_id' => $booking->schedule_id,
                'timestamp' => now()->timestamp,
                'hash' => md5($booking->ticket_code . $booking->id . config('app.key'))
            ];
            
            $qrCode = QrCode::size(300)
                ->format('png')
                ->errorCorrection('H')
                ->generate(json_encode($data));
            
            return [
                'success' => true,
                'qr_code' => 'data:image/png;base64,' . base64_encode($qrCode),
                'data' => $data
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Validate QR Code
     */
    public static function validateQrCode($qrData, $booking)
    {
        try {
            if (!isset($qrData['hash']) || !isset($qrData['ticket_code'])) {
                return false;
            }
            
            $expectedHash = md5($qrData['ticket_code'] . $qrData['booking_id'] . config('app.key'));
            
            return hash_equals($expectedHash, $qrData['hash']) && 
                   $qrData['ticket_code'] === $booking->ticket_code;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}