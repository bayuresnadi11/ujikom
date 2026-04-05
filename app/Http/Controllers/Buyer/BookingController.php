<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use App\Models\VenueSection;
use App\Models\VenueSchedule;
use App\Models\PlayTogether;
use App\Models\PlayTogetherParticipant;
use App\Models\Sparring;
use App\Models\SparringParticipant;
use App\Models\Community;
use App\Models\Setting;
use App\Models\Deposit; // Tambahkan ini
use App\Models\BookingParticipantPayment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'venue',
            'schedule.section',
            'playTogether',
            'sparring'
        ])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

        $setting = Setting::first();

        return view('buyer.booking.index', compact('bookings', 'setting'));
    }

    public function create(Request $request)
    {
        $venueId = $request->input('venue_id');
        $sectionId = $request->input('section_id');
        
        $venue = null;
        $section = null;
        $schedules = collect();
        
        if ($venueId && $sectionId) {
            $venue = Venue::findOrFail($venueId);
            $section = VenueSection::where('venue_id', $venueId)
                ->where('id', $sectionId)
                ->firstOrFail();
            
            $schedules = VenueSchedule::where('section_id', $sectionId)
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();
        }
        
        $myCommunities = Community::where('created_by', auth()->id())->get();
        
        return view('buyer.booking.create', compact('venue', 'section', 'schedules', 'myCommunities'));
    }

    public function store(Request $request)
{
    $request->validate([
        'venue_id' => 'required|exists:venues,id',
        'section_id' => 'required|exists:venue_sections,id',
        'schedule_id' => 'required|exists:venue_schedules,id',
        'type' => 'required|in:regular,play_together,sparring',
    ]);

    DB::beginTransaction();
    try {
        $schedule = VenueSchedule::findOrFail($request->schedule_id);
        if (!$schedule->available) throw new \Exception("Jadwal tidak tersedia");

        $booking = Booking::create([
            'type' => $request->type,
            'user_id' => auth()->id(),
            'venue_id' => $request->venue_id,
            'schedule_id' => $request->schedule_id,
            'ticket_code' => Booking::generateTicketCode(),
            'amount' => $schedule->rental_price,
            'pay_by' => $request->type === 'regular' ? null : $request->pay_by,
            'booking_payment' => 'pending',
        ]);

        // ====================== PLAY TOGETHER ======================
        if ($request->type === 'play_together') {

            $playTogether = PlayTogether::create([
                'booking_id' => $booking->id,
                'name' => $request->pt_name,
                'community_id' => $request->pt_privacy === 'community' ? $request->pt_community_id : null,
                'date' => $schedule->date,
                'location' => $booking->venue->location,
                'max_participants' => $request->pt_max_participants,
                'type' => $request->pt_type,
                'price_per_person' => $request->pt_type === 'paid' ? $request->pt_price_per_person : 0,
                'payment_deadline' => $request->pt_payment_deadline ? \Carbon\Carbon::parse($request->pt_payment_deadline) : \Carbon\Carbon::parse($schedule->date . ' ' . $schedule->start_time),
                'privacy' => $request->pt_privacy,
                'host_approval' => $request->has('pt_host_approval'),
                'description' => $request->pt_description,
                'payment_type' => $request->pt_type === 'paid' ? 'participant_payment' : null,
                'created_by' => auth()->id(),
            ]);

            // ====================== DEPOSIT HOST ======================
            $this->createHostDeposit($booking, $playTogether);

            // ====================== PARTICIPANT HOST ======================
            if ($request->has('pt_include_host')) {
                $this->createParticipant(auth()->id(), $booking, $playTogether, true);
            }

        } else {
            // ====================== REGULAR / SPARRING ======================
            Deposit::create([
                'user_id' => auth()->id(),
                'booking_id' => $booking->id,
                'amount' => -$schedule->rental_price,
                'source_type' => $request->type === 'sparring' ? 'sparring' : 'booking',
                'source_id' => $booking->id,
                'status' => 'pending',
                'description' => 'Booking ' . ucfirst($request->type) . ' dibuat - pending',
            ]);
        }

        $schedule->update(['available' => false]);

        DB::commit();
        return redirect()->route('buyer.booking.index')->with('success', 'Booking berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Booking store error', ['message' => $e->getMessage()]);
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

    public function edit($id)
    {
        $booking = Booking::with([
            'venue',
            'schedule.section',
            'playTogether',
            'sparring'
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        if ($booking->booking_payment !== 'pending') {
            return redirect()->route('buyer.booking.index')
                ->withErrors(['error' => 'Booking yang sudah dibayar tidak dapat diedit']);
        }

        $schedules = VenueSchedule::where('section_id', $booking->schedule->section_id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $myCommunities = Community::where('created_by', auth()->id())->get();

        return view('buyer.booking.edit', compact('booking', 'schedules', 'myCommunities'));
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::where('user_id', auth()->id())->findOrFail($id);

            if ($booking->booking_payment !== 'pending') {
                return back()->withErrors(['error' => 'Booking yang sudah dibayar tidak dapat diupdate']);
            }

            $rules = [
                'schedule_id' => 'required|exists:venue_schedules,id',
                'type' => 'required|in:regular,play_together,sparring',
            ];

            // Dynamic validation based on type
            if ($request->type === 'play_together') {
                $rules['pay_by'] = 'required|in:host,participant';
                $rules['pt_name'] = 'required|string|max:255';
                $rules['pt_privacy'] = 'required|in:public,private,community';
                $rules['pt_max_participants'] = 'required|integer|min:2';
                $rules['pt_type'] = 'required|in:free,paid';
                
                if ($request->pt_privacy === 'community') {
                    $rules['pt_community_id'] = 'required|exists:communities,id';
                }
                
                if ($request->pt_type === 'paid') {
                    $rules['pt_price_per_person'] = 'required|numeric|min:0';
                }
            } elseif ($request->type === 'sparring') {
                $rules['pay_by'] = 'required|in:host,participant';
                $rules['sp_name'] = 'required|string|max:255';
                $rules['sp_privacy'] = 'required|in:public,private,community';
                $rules['sp_type'] = 'required|in:free,paid';
                
                if ($request->sp_type === 'paid') {
                    $rules['sp_cost_per_participant'] = 'required|numeric|min:0';
                }
            }

            $validatedData = $request->validate($rules);

            DB::beginTransaction();

            $newSchedule = VenueSchedule::findOrFail($validatedData['schedule_id']);
            
            if (!$newSchedule->available && $newSchedule->id != $booking->schedule_id) {
                return back()->withErrors(['schedule_id' => 'Jadwal tidak tersedia'])->withInput();
            }

            // Free old schedule if changed
            if ($booking->schedule_id != $validatedData['schedule_id']) {
                $booking->schedule->update(['available' => true]);
                $newSchedule->update(['available' => false]);
            }

            // Cek apakah amount berubah
            $oldAmount = $booking->amount;
            $newAmount = $newSchedule->rental_price;

            // Update booking
            $booking->update([
                'type' => $validatedData['type'],
                'schedule_id' => $validatedData['schedule_id'],
                'amount' => $newAmount,
                'pay_by' => $validatedData['type'] === 'regular' ? null : ($request->pay_by ?? null),
            ]);

            // Update deposit amount yang negatif jika harga berubah
            if ($oldAmount != $newAmount) {
                $negativeDeposit = Deposit::where('booking_id', $booking->id)
                    ->where('source_type', 'booking')
                    ->where('amount', '<', 0)
                    ->first();
                
                if ($negativeDeposit) {
                    $negativeDeposit->update([
                        'amount' => -$newAmount,
                        'description' => 'Booking diupdate - harga baru: ' . number_format($newAmount),
                    ]);
                }
            }

            // Delete old play_together/sparring if type changed
            if ($booking->type !== $validatedData['type']) {
                $booking->playTogether()->delete();
                $booking->sparring()->delete();
            }

            // Update or create Play Together
            if ($validatedData['type'] === 'play_together') {
                $playTogetherData = [
                    'name' => $request->pt_name,
                    'community_id' => $request->pt_privacy === 'community' ? $request->pt_community_id : null,
                    'date' => $newSchedule->date,
                    'location' => $booking->venue->location,
                    'max_participants' => $request->pt_max_participants,
                    'type' => $request->pt_type,
                    'payment_deadline' => $request->pt_payment_deadline ? Carbon::parse($request->pt_payment_deadline) : now()->addDays(7),
                    'price_per_person' => $request->pt_type === 'paid' ? $request->pt_price_per_person : null,
                    'privacy' => $request->pt_privacy,
                    'gender' => $request->pt_gender,
                    'host_approval' => $request->has('pt_host_approval') ? true : false,
                    'description' => $request->pt_description,
                    'payment_type' => $request->pt_type === 'paid' ? 'participant_payment' : null,
                ];

                $booking->playTogether()->updateOrCreate(
                    ['booking_id' => $booking->id],
                    $playTogetherData
                );
            }

            // Update or create Sparring
            if ($validatedData['type'] === 'sparring') {
                $sparringData = [
                    'name' => $request->sp_name,
                    'type' => $request->sp_type,
                    'date' => $newSchedule->date,
                    'location' => $booking->venue->location,
                    'privacy' => $request->sp_privacy,
                    'cost_per_participant' => $request->sp_type === 'paid' ? $request->sp_cost_per_participant : null,
                    'host_approval' => $request->has('sp_host_approval') ? true : false,
                    'description' => $request->sp_description,
                    'payment_type' => $request->sp_type === 'paid' ? 'participant_payment' : null,
                ];

                $booking->sparring()->updateOrCreate(
                    ['booking_id' => $booking->id],
                    $sparringData
                );
            }

            DB::commit();

            return redirect()->route('buyer.booking.index')
                ->with('success', 'Booking berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate booking: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);

        DB::beginTransaction();
        try {
            // Free the schedule
            $booking->schedule->update(['available' => true]);
            
            // Hapus deposit yang terkait
            Deposit::where('booking_id', $booking->id)
                ->where('source_type', 'booking')
                ->delete();
            
            // Delete booking (will cascade delete play_together/sparring)
            $booking->delete();

            DB::commit();

            return redirect()->route('buyer.booking.index')
                ->with('success', 'Booking berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus booking']);
        }
    }

    public function getSections($venueId)
    {
        $sections = VenueSection::where('venue_id', $venueId)->get();
        return response()->json($sections);
    }

    public function getSchedulesBySection($sectionId)
    {
        $schedules = VenueSchedule::where('section_id', $sectionId)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json($schedules);
    }

    public function processPayment($id)
    {
        return back()->with('info', 'Fitur pembayaran akan segera tersedia');
    }

    public function getPaymentData($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json([
            'amount' => $booking->amount,
            'booking_payment' => $booking->booking_payment,
        ]);
    }

    public function checkStatus($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json([
            'status' => $booking->booking_payment,
        ]);
    }

    public function generateSnapToken($id)
    {
        $booking = Booking::findOrFail($id);
        $setting = Setting::first();

        // Konfigurasi Midtrans
        Config::$serverKey = $setting->midtrans_server_key;
        Config::$isProduction = $setting->midtrans_is_production;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat order_id unik dengan timestamp atau random string
        $orderId = 'BOOKING-' . $booking->id . '-U' . auth()->id() . '-' . time();
        $booking->update(['midtrans_order_id' => $orderId]);

        if (
            $booking->type === 'play_together'
            && $booking->pay_by === 'participant'
        ) {
            BookingParticipantPayment::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'amount' => $booking->getDisplayTotalAmount(),
                    'status' => 'pending',
                    'midtrans_transaction_status' => 'pending',
                ]
            );            
        }        

        $chargeAmount = $booking->getMidtransChargeAmount();

        if ($chargeAmount < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan pembayaran tidak valid. Silakan refresh atau hubungi admin.'
            ], 422);
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) ceil($chargeAmount),
            ],
            'customer_details' => [
                'first_name' => $booking->user->name ?? 'Buyer',
                'email' => $booking->user->email ?? 'buyer@example.com',
            ],
        ];        

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan Snap token di DB kalau mau digunakan ulang
            $booking->update(['midtrans_snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Error', [
                'message' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan token pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getQrCode($id)
    {
        $booking = Booking::findOrFail($id);
    
        if ($booking->booking_payment !== 'full') {
            return response()->json([
                'success' => false,
                'message' => 'QR code hanya tersedia untuk booking yang sudah dibayar penuh',
            ], 403);
        }
    
        return response()->json([
            'success' => true,
            'ticket_code' => $booking->ticket_code,
        ]);
    }    

    public function testPayment($id)
    {
        return response()->json(['message' => 'Test payment endpoint']);
    }

    public function midtransCallback(Request $request)
    {
        $payload = $request->all();
        \Log::info('MIDTRANS CALLBACK', $payload);
    
        $booking = Booking::where('midtrans_order_id', $payload['order_id'] ?? '')->first();
        if (!$booking) return response()->json(['message' => 'Booking tidak ditemukan'], 404);
    
        DB::transaction(function () use ($booking, $payload) {
            $transactionStatus = $payload['transaction_status'] ?? null;
            $grossAmount = $payload['gross_amount'] ?? 0;
    
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
    
                if ($booking->type === 'play_together') {
                    $playTogether = $booking->playTogether;
                    if (!$playTogether) {
                        Log::error('PlayTogether tidak ditemukan', ['booking_id' => $booking->id]);
                        return;
                    }
    
                    // ====================== Peserta ======================
                    $totalPaid = 0;
                    foreach ($playTogether->participants as $participant) {
                        $amount = $this->calculateParticipantAmount($booking, $playTogether, $participant->user_id);
    
                        // Update status peserta
                        $participant->update([
                            'payment_status' => 'paid',
                            'amount' => $amount,
                            'paid_at' => now(),
                        ]);
    
                        // BookingParticipantPayment
                        BookingParticipantPayment::updateOrCreate(
                            ['play_together_participant_id' => $participant->id],
                            [
                                'booking_id' => $booking->id,
                                'user_id' => $participant->user_id,
                                'amount' => $amount,
                                'status' => 'paid',
                                'midtrans_transaction_status' => 'paid',
                            ]
                        );
    
                        // ✅ FIX: Split deposits for better accounting
                        // Calculate lapang and fee separately
                        $rentalPrice = $booking->schedule->rental_price;
                        $maxParticipants = max(1, (int) $playTogether->max_participants);
                        $joinFee = (float) ($playTogether->price_per_person ?? 0);
                        $lapangPerPerson = 0;

                        if ($booking->pay_by === 'participant') {
                            $lapangPerPerson = $rentalPrice / $maxParticipants;
                        }

                        // 1️⃣ Deposit Lapang (if applicable)
                        if ($lapangPerPerson > 0) {
                            Deposit::create([
                                'user_id' => $participant->user_id,
                                'booking_id' => $booking->id,
                                'source_type' => 'play_together',
                                'source_id' => $playTogether->id,
                                'amount' => $lapangPerPerson,
                                'status' => 'completed',
                                'description' => 'Pembayaran lapang Main Bareng',
                            ]);
                        }

                        // 2️⃣ Deposit Join Fee (if applicable)
                        if ($joinFee > 0) {
                            Deposit::create([
                                'user_id' => $participant->user_id,
                                'booking_id' => $booking->id,
                                'source_type' => 'play_together',
                                'source_id' => $playTogether->id,
                                'amount' => $joinFee,
                                'status' => 'completed',
                                'description' => 'Pembayaran join fee Main Bareng',
                            ]);
                        }
    
                        $totalPaid += $amount;
                    }
    
                    // ====================== Host bayar bagiannya ======================
                    if ($booking->pay_by === 'host') {
    
                        // 1️⃣ Deposit Lapang positif
                        Deposit::create([
                            'user_id' => $booking->user_id,
                            'booking_id' => $booking->id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $booking->schedule->rental_price,
                            'status' => 'completed',
                            'description' => 'Pembayaran host PlayTogether - Lapang',
                        ]);
    
                        // 2️⃣ Deposit Join Fee positif
                        // ✅ FIX: Added type check to ensure join fee is recorded for paid events
                        if ($playTogether->type === 'paid' && $playTogether->price_per_person > 0) {
                            Deposit::create([
                                'user_id' => $booking->user_id,
                                'booking_id' => $booking->id,
                                'source_type' => 'play_together',
                                'source_id' => $playTogether->id,
                                'amount' => $playTogether->price_per_person,
                                'status' => 'completed',
                                'description' => 'Pembayaran host PlayTogether - Join Fee',
                            ]);
                        }
                    }
    
                    // ====================== Hitung total_paid & status ======================
                    $totalPaid = Deposit::where('booking_id', $booking->id)
                        ->where('status', 'completed')
                        ->sum('amount');
    
                    $status = 'pending';
                    if ($totalPaid >= $booking->amount) {
                        $status = 'full';
                    } elseif ($totalPaid > 0) {
                        $status = 'partial';
                    }
    
                    $booking->update([
                        'booking_payment' => $status,
                        'total_paid' => $totalPaid,
                        'paid_amount' => $totalPaid,
                        'paid_at' => $totalPaid > 0 ? now() : null,
                    ]);
    
                } else {
                    // REGULAR / SPARRING
                    Deposit::create([
                        'user_id' => $booking->user_id,
                        'booking_id' => $booking->id,
                        'source_type' => $booking->type === 'sparring' ? 'sparring' : 'booking',
                        'source_id' => $booking->id,
                        'amount' => $grossAmount,
                        'status' => 'completed',
                        'description' => 'Pembayaran Booking via Midtrans',
                    ]);
    
                    $booking->update([
                        'booking_payment' => 'full',
                        'total_paid' => $grossAmount,
                        'paid_amount' => $grossAmount,
                        'paid_at' => now(),
                    ]);
                }
    
            } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                $booking->update(['booking_payment' => 'failed', 'status' => 'canceled']);
                optional($booking->schedule)->update(['available' => true]);
    
                // Cancel deposit negatif host
                $sourceType = $booking->type === 'play_together' ? 'play_together' : 'booking';
                Deposit::where('booking_id', $booking->id)
                    ->where('source_type', $sourceType)
                    ->where('amount', '<', 0)
                    ->update(['status' => 'canceled']);
            }
        });
    
        return response()->json(['message' => 'OK']);
    }
    

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
    
        DB::transaction(function () use ($booking) {
            $totalPaid = 0;
    
            if ($booking->type === 'play_together') {
                $playTogether = $booking->playTogether;
                if (!$playTogether) return;
    
                // ====================== Peserta ======================
                foreach ($playTogether->participants as $participant) {
                    $amount = $this->calculateParticipantAmount($booking, $playTogether, $participant->user_id);
    
                    // Update status peserta
                    $participant->update([
                        'payment_status' => 'paid',
                        'amount' => $amount,
                        'paid_at' => now(),
                    ]);
    
                    // BookingParticipantPayment
                    BookingParticipantPayment::updateOrCreate(
                        ['play_together_participant_id' => $participant->id],
                        [
                            'booking_id' => $booking->id,
                            'user_id' => $participant->user_id,
                            'amount' => $amount,
                            'status' => 'paid',
                            'midtrans_transaction_status' => 'paid',
                        ]
                    );
    
                    // ✅ FIX: Split deposits for better accounting
                    // Calculate lapang and fee separately
                    $rentalPrice = $booking->schedule->rental_price;
                    $maxParticipants = max(1, (int) $playTogether->max_participants);
                    $joinFee = (float) ($playTogether->price_per_person ?? 0);
                    $lapangPerPerson = 0;

                    if ($booking->pay_by === 'participant') {
                        $lapangPerPerson = $rentalPrice / $maxParticipants;
                    }

                    // 1️⃣ Deposit Lapang (if applicable)
                    if ($lapangPerPerson > 0) {
                        Deposit::create([
                            'user_id' => $participant->user_id,
                            'booking_id' => $booking->id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $lapangPerPerson,
                            'status' => 'completed',
                            'description' => 'Pembayaran lapang Main Bareng',
                        ]);
                    }

                    // 2️⃣ Deposit Join Fee (if applicable)
                    if ($joinFee > 0) {
                        Deposit::create([
                            'user_id' => $participant->user_id,
                            'booking_id' => $booking->id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $joinFee,
                            'status' => 'completed',
                            'description' => 'Pembayaran join fee Main Bareng',
                        ]);
                    }
    
                    $totalPaid += $amount;
                }
    
                // ====================== Host bayar ======================
                if ($booking->pay_by === 'host') {
                    $hostUserId = $booking->user_id;

                    // Lapang
                    Deposit::create([
                        'user_id' => $hostUserId,
                        'booking_id' => $booking->id,
                        'source_type' => 'play_together',
                        'source_id' => $playTogether->id,
                        'amount' => $booking->schedule->rental_price,
                        'status' => 'completed',
                        'description' => 'Pembayaran host PlayTogether - Lapang',
                    ]);

                    // Join fee
                    // ✅ FIX: Added type check to ensure join fee is recorded for paid events
                    if ($playTogether->type === 'paid' && $playTogether->price_per_person > 0) {
                        Deposit::create([
                            'user_id' => $hostUserId,
                            'booking_id' => $booking->id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $playTogether->price_per_person,
                            'status' => 'completed',
                            'description' => 'Pembayaran host PlayTogether - Join Fee',
                        ]);
                    }
                }
    
                // ====================== Hitung total_paid & status ======================
                $totalPaid = Deposit::where('booking_id', $booking->id)
                    ->where('status', 'completed')
                    ->sum('amount');
    
                $status = 'pending';
                if ($totalPaid >= $booking->amount) {
                    $status = 'full';
                } elseif ($totalPaid > 0) {
                    $status = 'partial';
                }
    
                $booking->update([
                    'booking_payment' => $status,
                    'total_paid' => $totalPaid,
                    'paid_amount' => $totalPaid,
                    'paid_at' => $totalPaid > 0 ? now() : null,
                ]);
    
            } else {
                // REGULAR / SPARRING
                Deposit::create([
                    'user_id' => $booking->user_id,
                    'booking_id' => $booking->id,
                    'source_type' => $booking->type === 'sparring' ? 'sparring' : 'booking',
                    'source_id' => $booking->id,
                    'amount' => $booking->amount,
                    'status' => 'completed',
                    'description' => 'Pembayaran Booking berhasil',
                ]);
    
                $booking->update([
                    'booking_payment' => 'full',
                    'total_paid' => $booking->amount,
                    'paid_amount' => $booking->amount,
                    'paid_at' => now(),
                ]);
            }
        });
    
        // Kirim notifikasi pembayaran berhasil
        if ($booking->booking_payment === 'full' || $booking->booking_payment === 'partial') {
            Notification::sendPaymentSuccessNotification(
                $booking->user_id,
                $booking->total_paid,
                $booking->id,
                $booking->playTogether->id ?? null
            );
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui (deposit baru dibuat)',
        ]);
    }     

// ====================== HELPERS ======================
private function createHostDeposit($booking, $playTogether)
{
    $hostAmount = $booking->schedule->rental_price;
    Deposit::create([
        'user_id' => auth()->id(),
        'booking_id' => $booking->id,
        'amount' => -$hostAmount,
        'source_type' => 'play_together',
        'source_id' => $playTogether->id,
        'status' => 'pending',
        'description' => 'Host booking PlayTogether - pending',
    ]);
}

// ====================== CREATE PARTICIPANT ======================
private function createParticipant($userId, $booking, $playTogether, $isHost = false)
{
    // Hanya hitung lapang untuk booking_participant_payments
    $lapangAmount = $this->calculateParticipantLapang($booking, $playTogether, $userId);
    $joinFee = $this->calculateParticipantJoinFee($booking, $playTogether, $userId);

    // Simpan participant
    $participant = PlayTogetherParticipant::create([
        'play_together_id' => $playTogether->id,
        'user_id' => $userId,
        'approval_status' => 'approved',
        'payment_status' => 'pending',
        'amount' => $lapangAmount, // amount di participant = lapang saja
        'joined_at' => now(),
    ]);

    // Simpan ke booking_participant_payments hanya lapang
    BookingParticipantPayment::updateOrCreate(
        ['booking_id' => $booking->id, 'user_id' => $userId],
        [
            'play_together_participant_id' => $participant->id,
            'amount' => $lapangAmount,
            'status' => 'pending',
            'midtrans_transaction_status' => 'pending',
        ]
    );

    // Simpan deposit:
    // 1️⃣ Lapang
    if ($lapangAmount > 0) {
        Deposit::create([
            'user_id' => $userId,
            'booking_id' => $booking->id,
            'source_type' => 'play_together',
            'source_id' => $playTogether->id,
            'amount' => $lapangAmount,
            'status' => 'pending',
            'description' => 'Pembayaran lapang Main Bareng',
        ]);
    }

    // 2️⃣ Join Fee
    if ($joinFee > 0) {
        Deposit::create([
            'user_id' => $userId,
            'booking_id' => $booking->id,
            'source_type' => 'play_together',
            'source_id' => $playTogether->id,
            'amount' => $joinFee,
            'status' => 'pending',
            'description' => 'Pembayaran join fee Main Bareng',
        ]);
    }

    return $participant;
}

private function calculateParticipantAmount($booking, $playTogether, $userId)
{
    $rentalPrice = $booking->schedule->rental_price;
    $maxParticipants = max(1, (int) $playTogether->max_participants);
    $joinFee = (float) ($playTogether->price_per_person ?? 0);

    if ($booking->pay_by === 'participant') {
        // Setiap peserta bayar lapang + join fee, TERMASUK HOST
        $lapangPerPerson = $rentalPrice / $maxParticipants;
        
        // ✅ FIX: Removed incorrect logic that zeroed join fee for host
        // All participants (including host) should pay join fee when pay_by = 'participant'
        
    } elseif ($booking->pay_by === 'host') {
        // Host bayar lapang sendiri → peserta lapang = 0
        $lapangPerPerson = 0;
        
        // ✅ KEEP: Host tidak bayar join fee lagi kalau host yang bayar semua
        if ($userId === $booking->user_id) {
            $joinFee = 0;
        }
    } else {
        $lapangPerPerson = 0;
    }

    return $lapangPerPerson + $joinFee;
}

private function calculateParticipantLapang($booking, $playTogether, $userId)
{
    $rentalPrice = $booking->schedule->rental_price;
    $maxParticipants = max(1, (int) $playTogether->max_participants);

    // Hanya peserta yang bayar lapang jika pay_by = participant
    if ($booking->pay_by === 'participant') {
        return $rentalPrice / $maxParticipants;
    }

    return 0;
}

private function calculateParticipantJoinFee($booking, $playTogether, $userId)
{
    $joinFee = (float) ($playTogether->price_per_person ?? 0);

    // Host tidak bayar join fee
    if ($userId === $booking->user_id) $joinFee = 0;

    // Kalau host yang bayar lapang, peserta join fee tetap
    if ($booking->pay_by === 'host') {
        return $joinFee;
    }

    return $joinFee;
}


}