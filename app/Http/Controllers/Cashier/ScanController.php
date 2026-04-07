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
        ]);

        DB::beginTransaction();
        
        try {
            // Cari booking berdasarkan ticket_code
            $booking = Booking::where('ticket_code', $request->ticket_code)
                ->with(['user', 'venue', 'schedule.section'])
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
                    'message' => 'Booking belum dibayar atau belum lunas',
                    'error_type' => 'unpaid',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-'
                ]);
            }

            $now = Carbon::now();
            
            // Ambil waktu main
            if ($booking->schedule) {
                $dateOnly = Carbon::parse($booking->schedule->date)->format('Y-m-d');
                $playDateTime = Carbon::parse($dateOnly . ' ' . $booking->schedule->start_time);
                $startTime = $booking->schedule->start_time;
                $endTime = $booking->schedule->end_time;
            } else {
                return response()->json(['success' => false, 'message' => 'Jadwal booking tidak ditemukan']);
            }
            
            // Waktu mulai check-in (15 menit sebelum main)
            $checkInStartTime = $playDateTime->copy()->subMinutes(15);

            // 1. CEK TERLALU CEPAT
            if ($now->lt($checkInStartTime)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Scan hanya bisa dilakukan 15 menit sebelum jam main dimulai',
                    'error_type' => 'too_early',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-',
                    'allowed_time' => $checkInStartTime->format('H:i')
                ]);
            }

            // 2. CEK TERLALU LAMA (Kadaluarsa) - misal 3 jam setelah main
            if ($now->gt(Carbon::parse($dateOnly . ' ' . $endTime)->addHours(1))) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Waktu booking sudah berakhir',
                    'error_type' => 'expired',
                    'ticket_code' => $booking->ticket_code,
                    'customer_name' => $booking->user->name ?? '-'
                ]);
            }

            $targetStatus = '';
            $statusMessage = '';

            // 3. TENTUKAN STATUS (VENUE vs LAPANGAN)
            if ($now->lt($playDateTime)) {
                // Jendela 15 menit SEBELUM main -> MASUK VENUE
                if ($booking->scan_status === 'masuk_venue') {
                    return response()->json([
                        'success' => false,
                        'message' => 'User sudah masuk venue. Tunggu jam ' . $playDateTime->format('H:i') . ' untuk masuk lapangan.',
                        'error_type' => 'already_scanned',
                        'ticket_code' => $booking->ticket_code,
                        'customer_name' => $booking->user->name ?? '-'
                    ]);
                }
                
                if ($booking->scan_status === 'masuk_lapang') {
                     return response()->json([
                        'success' => false,
                        'message' => 'Tiket sudah digunakan untuk masuk lapangan!',
                        'error_type' => 'already_scanned',
                        'ticket_code' => $booking->ticket_code,
                    ]);
                }

                $targetStatus = 'masuk_venue';
                $statusMessage = 'Check-in venue berhasil';
            } else {
                // SUDAH JAM MAIN -> MASUK LAPANGAN
                if ($booking->scan_status === 'masuk_lapang') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tiket sudah digunakan untuk masuk lapangan sebelumnya.',
                        'error_type' => 'already_scanned',
                        'ticket_code' => $booking->ticket_code,
                    ]);
                }

                $targetStatus = 'masuk_lapang';
                $statusMessage = 'Check-in lapangan berhasil (Mulai bermain)';
            }

            // Update status
            $booking->update([
                'scan_status' => $targetStatus,
                'scan_time' => $now
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $statusMessage,
                'ticket_code' => $booking->ticket_code,
                'customer_name' => $booking->user->name ?? '-',
                'venue_name' => $booking->venue->venue_name ?? '-',
                'section_name' => $booking->schedule->section->section_name ?? '-',
                'booking_type' => $booking->type,
                'play_date' => Carbon::parse($booking->schedule->date)->format('d/m/Y'),
                'play_time' => substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5),
                'scan_status_text' => $this->getScanStatusText($targetStatus),
                'scan_time' => $now->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sistem Error: ' . $e->getMessage(),
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