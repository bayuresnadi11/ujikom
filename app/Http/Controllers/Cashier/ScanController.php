<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function index()
    {
        // Ambil semua venue untuk dropdown
        $venues = Venue::all();
        
        // Ambil sections jika diperlukan (sesuaikan dengan struktur database Anda)
        // Ambil sections jika diperlukan (sesuaikan dengan struktur database Anda)
        $sections = \App\Models\VenueSection::all();
        
        return view('cashier.scan.scan', compact('sections', 'venues'));
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
            'scan_mode' => 'required|in:venue,section'
        ]);

        DB::beginTransaction();
        
        try {
            // Cari booking berdasarkan ticket_code
            $booking = Booking::where('ticket_code', $request->ticket_code)
                ->with(['user', 'venue', 'schedule'])
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode tiket tidak valid atau booking tidak ditemukan',
                    'error_type' => 'not_found'
                ]);
            }

            // Cek status payment - harus sudah paid
            if (!$booking->isPaid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking belum dibayar atau pembayaran belum terverifikasi',
                    'error_type' => 'unpaid',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-'
                ]);
            }



            $scanMode = $request->scan_mode;
            $now = Carbon::now();
            
            // Parse waktu main dari schedule (jika ada relasi schedule)
            if ($booking->schedule) {
                $scheduleDate = Carbon::parse($booking->schedule->date ?? now());
                $dateOnly = Carbon::parse($booking->schedule->date)->format('Y-m-d');
                $playDateTime = Carbon::parse($dateOnly . ' ' . $booking->schedule->start_time);
                $startTime = $booking->schedule->start_time ?? '00:00:00';
                $endTime = $booking->schedule->end_time ?? '23:59:59';
            } else {
                // Fallback jika tidak ada schedule relation
                $scheduleDate = now();
                $playDateTime = now();
                $startTime = '00:00:00';
                $endTime = '23:59:59';
            }
            
            // Waktu mulai check-in (15 menit sebelum main)
            $checkInStartTime = $playDateTime->copy()->subMinutes(15);

            // VALIDASI MASUK VENUE
            if ($scanMode === 'venue') {
                // Cek apakah sudah pernah scan venue
                if (in_array($booking->scan_status, ['masuk_venue', 'masuk_lapang'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tiket sudah di-scan untuk masuk venue sebelumnya!',
                        'error_type' => 'already_scanned',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-',
                        'scan_status_text' => $this->getScanStatusText($booking->scan_status),
                        'last_scan_time' => $booking->scan_time ? 
                            $booking->scan_time->format('d/m/Y H:i:s') : '-'
                    ]);
                }

                // Cek apakah sudah waktunya check-in (15 menit sebelum main)
                if ($now->lt($checkInStartTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu cepat! Silakan scan 15 menit sebelum jam main dimulai',
                        'error_type' => 'too_early',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-',
                        'allowed_time' => $checkInStartTime->format('H:i')
                    ]);
                }

                // Cek apakah sudah lewat waktu main
                if ($now->gt($playDateTime->copy()->addHours(3))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Waktu booking sudah terlewat',
                        'error_type' => 'expired',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-'
                    ]);
                }

                // Update booking - masuk venue
                $booking->update([
                    'scan_status' => 'masuk_venue',
                    'scan_time' => $now
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in venue berhasil',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-',
                    'venue_name' => $booking->venue->venue_name ?? '-',
                    'section_name' => $booking->schedule->section->section_name ?? '-',
                    'booking_type' => $booking->type,
                    'play_date' => $scheduleDate->format('d/m/Y'),
                    'play_time' => substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5),
                    'scan_status_text' => 'Masuk Venue',
                    'scan_time' => $now->format('d/m/Y H:i:s')
                ]);
            }

            // VALIDASI MASUK LAPANGAN
            if ($scanMode === 'section') {
                // Cek apakah sudah scan venue
                if ($booking->scan_status === 'belum_scan') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Harap scan di pintu venue terlebih dahulu!',
                        'error_type' => 'venue_not_scanned',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-',
                        'scan_status_text' => 'Belum Scan'
                    ]);
                }

                // VALIDASI WAKTU: Tidak boleh masuk lapang sebelum jam main
                // Gunakan 5 menit toleransi sebelum jam main jika diperlukan, atau strict on time.
                // Request user: "kalo belum jam main ... gabisa scan MASUK LAPANG" -> Strict start time.
                if ($now->lt($playDateTime)) {
                     return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya masuk lapangan! Silakan tunggu hingga jam main dimulai (' . $playDateTime->format('H:i') . ').',
                        'error_type' => 'too_early_field',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-',
                        'allowed_time' => $playDateTime->format('H:i')
                    ]);
                }

                // Cek apakah sudah pernah scan lapangan
                if ($booking->scan_status === 'masuk_lapang') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tiket sudah di-scan untuk masuk lapangan sebelumnya!',
                        'error_type' => 'already_scanned',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-',
                        'scan_status_text' => 'Sudah Masuk Lapangan',
                        'last_scan_time' => $booking->scan_time ? 
                            $booking->scan_time->format('d/m/Y H:i:s') : '-'
                    ]);
                }

                // Update booking - masuk lapangan
                $booking->update([
                    'scan_status' => 'masuk_lapang',
                    'scan_time' => $now
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in lapangan berhasil',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-',
                    'venue_name' => $booking->venue->venue_name ?? '-',
                    'section_name' => $booking->schedule->section->section_name ?? '-',
                    'booking_type' => $booking->type,
                    'play_date' => $scheduleDate->format('d/m/Y'),
                    'play_time' => substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5),
                    'scan_status_text' => 'Masuk Lapangan',
                    'scan_time' => $now->format('d/m/Y H:i:s')
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'error_type' => 'system_error'
            ], 500);
        }
    }

    /**
     * Helper untuk convert scan_status ke text yang readable
     */
    private function getScanStatusText($status)
    {
        $statusMap = [
            'belum_scan' => 'Belum Scan',
            'masuk_venue' => 'Sudah Masuk Venue',
            'masuk_lapang' => 'Sudah Masuk Lapangan'
        ];

        return $statusMap[$status] ?? $status;
    }
}