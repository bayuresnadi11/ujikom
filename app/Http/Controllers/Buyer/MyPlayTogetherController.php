<?php

namespace App\Http\Controllers\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlayTogether;
use App\Models\PlayTogetherParticipant;
use App\Models\PlayTogetherInvitation;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Notification;
use App\Models\Deposit; 
use App\Models\BookingParticipantPayment;

class MyPlayTogetherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'created'); // created, joined, pending
        $search = $request->get('search', '');

        $query = PlayTogether::with(['booking.venue.category', 'creator', 'participants'])
            ->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('participants', function($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
            });

        // Filter tab
        if ($filter === 'created') {
            $query->where('created_by', $user->id);
        } elseif ($filter === 'joined') {
            $query->whereHas('participants', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('approval_status', 'approved');
            })->where('created_by', '!=', $user->id);
        } elseif ($filter === 'pending') {
            $query->whereHas('participants', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('approval_status', 'pending');
            })->where('created_by', '!=', $user->id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhereHas('booking.venue', function($query) use ($search) {
                      $query->where('venue_name', 'like', "%{$search}%");
                  });
            });
        }

        $playTogethers = $query->orderBy('date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        $setting = Setting::first();
        $midtransClientKey = $setting->midtrans_client_key ?? '';
        $isProduction = (bool)($setting->midtrans_is_production ?? false);

        return view('buyer.main_bareng_saya.index', compact('playTogethers', 'filter', 'search', 'midtransClientKey', 'isProduction'));
    }

    public function getSnapToken($id)
{
    $user = Auth::user();

    $playTogether = PlayTogether::with('booking')->findOrFail($id);

    $participant = PlayTogetherParticipant::where('play_together_id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $booking = $playTogether->booking;

    // ================================
    // 1️⃣ Cek role user
    // ================================
    $isHost = $playTogether->created_by === $user->id;

    // ================================
    // 2️⃣ Hitung biaya lapangan (harus dibayar semua peserta, termasuk join gratis)
    // ================================
    $lapangFee = 0;
    if ($booking && $booking->pay_by === 'participant') {
        $divider = $playTogether->max_participants;
        $lapangFee = ceil($booking->amount / $divider);
    }

    // ================================
    // 3️⃣ Hitung join fee
    // ================================
    $joinFee = 0;
    if ($playTogether->type === 'paid') {
        $joinFee = (int) $playTogether->price_per_person;
    }    

    // ================================
    // 4️⃣ Total yang harus dibayar participant ini
    // ================================
    $totalShouldPay = $joinFee + $lapangFee;
    $alreadyPaid = (int)($participant->total_paid ?? 0);
    $amountToPay = max(0, $totalShouldPay - $alreadyPaid);
    
    if ($amountToPay <= 0 && $participant->payment_status === 'paid') {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah melunasi kewajiban pembayaran.'
        ], 400);
    }    

    // ================================
    // 5️⃣ Simpan data payment sementara
    // ================================
    $participant->update([
        'amount' => $totalShouldPay,
        'payment_status' => $amountToPay > 0 ? 'pending' : 'paid',
    ]);

    // ================================
    // 6️⃣ Konfigurasi Midtrans
    // ================================
    $setting = Setting::first();
    if (!$setting || !$setting->midtrans_server_key) {
        return response()->json([
            'success' => false,
            'message' => 'Konfigurasi Midtrans belum diatur.'
        ], 500);
    }

    Config::$serverKey = $setting->midtrans_server_key;
    Config::$isProduction = (bool) $setting->midtrans_is_production;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $orderId = 'BOOKING-' . $participant->id . '-' . time();

    // ================================
    // 7️⃣ Item details Midtrans
    // ================================
    $itemDetails = [];

    if ($lapangFee > 0) {
        $itemDetails[] = [
            'id' => 'lapang',
            'price' => (int) $lapangFee,
            'quantity' => 1,
            'name' => 'Biaya Lapangan',
        ];
    }

    if ($joinFee > 0) {
        $itemDetails[] = [
            'id' => 'join',
            'price' => (int) $joinFee,
            'quantity' => 1,
            'name' => 'Biaya Join',
        ];
    }

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => (int) $amountToPay,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ],
        'item_details' => $itemDetails,
    ];

    try {
        $snapToken = Snap::getSnapToken($params);

        $participant->update([
            'midtrans_order_id' => $orderId,
            'payment_token' => $snapToken,
        ]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Update payment status from Snap
     */
    public function updatePaymentStatus(Request $request, $id)
{
    $user = Auth::user();
    $playTogether = PlayTogether::findOrFail($id);
    $participant = PlayTogetherParticipant::where('play_together_id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $status = $request->input('status');
    $paidAmount = (int) $request->input('gross_amount', 0);

    try {
        DB::beginTransaction();

        if (in_array($status, ['settlement', 'capture'])) {

            // 1️⃣ Hitung join fee & lapang fee
            $joinFee = ($playTogether->type === 'paid') ? (int)$playTogether->price_per_person : 0;
            $lapangFee = 0;
            if ($playTogether->booking && $playTogether->booking->pay_by === 'participant') {
                $lapangFee = ceil($playTogether->booking->amount / $playTogether->max_participants);
            }

            $totalShouldPay = $joinFee + $lapangFee;
            $alreadyPaidBeforeThis = (int)($participant->total_paid ?? 0);
            $totalPaidNow = $alreadyPaidBeforeThis + $paidAmount;
            $remaining = max(0, $totalShouldPay - $totalPaidNow);

            // 2️⃣ Update PlayTogetherParticipant
            $participant->update([
                'amount' => $totalShouldPay,
                'total_paid' => $totalPaidNow,
                'payment_status' => $remaining > 0 ? 'pending' : 'paid',
                'midtrans_transaction_status' => $status,
                'paid_at' => now(),
            ]);

            // 3️⃣ Hitung deposit join fee
            $alreadyDepositedJoin = Deposit::where('participant_id', $participant->id)
                ->where('source_type', 'play_together')
                ->where('description', 'like', '%Join%')
                ->sum('amount');

            $depositJoinAmount = max(0, min($joinFee - $alreadyDepositedJoin, $paidAmount));

            if ($depositJoinAmount > 0) {
                Deposit::create([
                    'user_id' => $user->id,
                    'booking_id' => $playTogether->booking_id,
                    'source_type' => 'play_together',
                    'source_id' => $playTogether->id,
                    'participant_id' => $participant->id,
                    'amount' => $depositJoinAmount,
                    'status' => 'completed',
                    'description' => 'Pembayaran join Main Bareng',
                ]);
            }

            // 4️⃣ Hitung lapang fee yang dibayar dari sisa
            $lapangAmount = max(0, $paidAmount - $depositJoinAmount);

            if ($lapangAmount > 0) {
                BookingParticipantPayment::create([
                    'user_id' => $user->id,
                    'booking_id' => $playTogether->booking_id,
                    'play_together_participant_id' => $participant->id,
                    'amount' => $lapangAmount,
                    'payment_token' => $participant->payment_token,
                    'payment_url' => null,
                    'midtrans_order_id' => $participant->midtrans_order_id,
                    'midtrans_transaction_status' => $status,
                    'paid_at' => now(),
                ]);

                Deposit::create([
                    'user_id' => $user->id,
                    'booking_id' => $playTogether->booking_id,
                    'source_type' => 'play_together',
                    'source_id' => $playTogether->id,
                    'participant_id' => $participant->id,
                    'amount' => $lapangAmount,
                    'status' => 'completed',
                    'description' => 'Pembayaran lapang Main Bareng',
                ]);
            }

            // 5️⃣ Update booking jika semua peserta lunas
            $approvedParticipants = $playTogether->participants()
                ->where('approval_status', 'approved')
                ->get();

            $allPaidAndFull =
                $approvedParticipants->count() === $playTogether->max_participants &&
                $approvedParticipants->where('payment_status', 'paid')->count() === $approvedParticipants->count();

            if ($allPaidAndFull && $playTogether->booking) {
                $playTogether->booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'paid_at' => now(),
                ]);
            }

            // 6️⃣ Kirim notifikasi
            $user->createNotification([
                'type' => 'payment_received',
                'title' => 'Pembayaran Main Bareng Berhasil',
                'message' => 'Pembayaran Anda untuk main bareng ' . ($playTogether->name ?? 'di ' . $playTogether->location) . ' sebesar Rp ' . number_format($paidAmount, 0, ',', '.') . ' telah berhasil.',
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'success',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogether->id),
                'action_text' => 'Lihat Detail',
            ]);

            if ($playTogether->creator && $playTogether->creator->id !== $user->id) {
                $playTogether->creator->createNotification([
                    'type' => 'main_bareng_payment',
                    'title' => 'Pembayaran Peserta Masuk',
                    'message' => $user->name . ' telah membayar Rp ' . number_format($paidAmount, 0, ',', '.') . ' untuk main bareng ' . ($playTogether->name ?? 'di ' . $playTogether->location) . '.',
                    'icon' => 'fas fa-user-check',
                    'color' => 'info',
                    'action_url' => route('buyer.main_bareng_saya.show', $playTogether->id),
                    'action_text' => 'Lihat Peserta',
                ]);
            }

        } else {
            // Status lain
            $participant->update([
                'midtrans_transaction_status' => $status
            ]);
        }

        // === CEK SEMUA PESERTA LUNAS ===
        $allPaid = $playTogether->participants
            ->where('payment_status', 'paid')
            ->count() === $playTogether->max_participants;

        if ($allPaid && $playTogether->booking) {
            $playTogether->booking->update([
                'booking_payment' => 'full',
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now(),
                'midtrans_order_id' => $participant->midtrans_order_id,
            ]);

            // Optional: update semua booking participant payment jadi settled
            BookingParticipantPayment::where('booking_id', $playTogether->booking_id)
                ->update(['midtrans_transaction_status' => 'settlement']
            );
        }

        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Recalculate price per person if participants not full on H-1 or Today
     * Modified: Allows recalculation only once by setting payment_type to 'adjusted'
     */
    public function recalculatePrice($id)
    {
        try {
            DB::beginTransaction();
            
            $playTogether = PlayTogether::with('participants')->findOrFail($id);
            
            if ($playTogether->created_by != Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Hanya host yang bisa merubah biaya.'], 403);
            }

            // CEK APAKAH SUDAH PERNAH DISESUAIKAN (FLAG: payment_type === 'adjusted')
            if ($playTogether->payment_type === 'adjusted') {
                return response()->json(['success' => false, 'message' => 'Penyesuaian biaya hanya dapat dilakukan satu kali.'], 400);
            }

            $approvedParticipants = $playTogether->participants()->where('approval_status', 'approved')->get();
            $count = $approvedParticipants->count();

            if ($count == 0) {
                return response()->json(['success' => false, 'message' => 'Belum ada peserta yang disetujui untuk dibagi biayanya.'], 400);
            }

            // Hitung total target biaya yang harus dicover (Tarif awal per orang * Kapasitas Maksimal)
            $totalCost = (float)$playTogether->price_per_person * $playTogether->max_participants;
            
            // Harga baru per orang agar total biaya tercapai dengan jumlah peserta saat ini
            $newPricePerPerson = ceil($totalCost / $count);

            // Update harga di table play_together dan pasang flag adjusted di payment_type
            $playTogether->update([
                'price_per_person' => $newPricePerPerson,
                'payment_type' => 'adjusted'
            ]);

            // Reset status payment semua participant menjadi pending agar mereka bayar sisa/kekurangan
            foreach ($approvedParticipants as $p) {
                $alreadyPaid = $p->total_paid ?? 0;
                $remaining = max(0, $newPricePerPerson - $alreadyPaid);
            
                $p->update([
                    'amount' => $newPricePerPerson,
                    'payment_status' => $remaining > 0 ? 'pending' : 'paid',
                ]);
            }            

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Biaya berhasil disesuaikan menjadi Rp ' . number_format($newPricePerPerson, 0, ',', '.') . ' per orang.',
                'new_price' => $newPricePerPerson
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        
        $playTogether = PlayTogether::with([
            'booking.venue.category',
            'creator',
            'participants.user',
            'invitations.invitedUser'
        ])->findOrFail($id);

        // Check if user is host
        $isHost = $playTogether->created_by == $user->id;

        // Get participant info
        $userParticipant = $playTogether->participants()
            ->where('user_id', $user->id)
            ->first();

        // Get all participants
        $participants = $playTogether->participants()
            ->with('user')
            ->get();

        // Pending participants (for host)
        $pendingParticipants = $participants->where('approval_status', 'pending');

        // Approved participants
        $approvedParticipants = $participants->where('approval_status', 'approved');

        // Get invitations (for host)
        $invitations = $playTogether->invitations()
            ->with('invitedUser')
            ->orderBy('created_at', 'desc')
            ->get();

        $setting = \App\Models\Setting::first();

        return view('buyer.main_bareng_saya.show', compact(
            'playTogether',
            'isHost',
            'userParticipant',
            'participants',
            'pendingParticipants',
            'approvedParticipants',
            'invitations',
            'setting'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        
        $playTogether = PlayTogether::with([
            'booking.venue.category'
        ])->findOrFail($id);

        // Check if user is host
        if ($playTogether->created_by != $user->id) {
            return redirect()->route('buyer.main_bareng_saya.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit main bareng ini');
        }

        return view('buyer.main_bareng_saya.edit', compact('playTogether'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $playTogether = PlayTogether::findOrFail($id);

        if ($playTogether->created_by != $user->id) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa edit'], 403);
        }

        $validated = $request->validate([
            'max_participants' => 'required|integer|min:2',
            'type' => 'required|in:free,paid',
            'price_per_person' => 'required_if:type,paid|nullable|numeric|min:0',
            'privacy' => 'required|in:public,private,community',
            'gender' => 'required|in:male,female,mixed,laki-laki,perempuan,campur,campuran',
            'host_approval' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $oldPrice = $playTogether->price_per_person;

            $playTogether->update([
                'max_participants' => $validated['max_participants'],
                'type' => $validated['type'],
                'price_per_person' => $validated['type'] === 'paid' ? $validated['price_per_person'] : null,
                'privacy' => $validated['privacy'],
                'gender' => $validated['gender'],
                'host_approval' => $request->has('host_approval') ? 1 : 0,
                'description' => $validated['description'],
            ]);

            // ===== Update peserta jika harga berubah =====
            if ($validated['type'] === 'paid') {
                $newPrice = $validated['price_per_person'];

                if ($newPrice != $oldPrice) {
                    $participants = $playTogether->participants()
                        ->where('approval_status', 'approved')
                        ->get();

                    foreach ($participants as $p) {
                        $alreadyPaid = $p->total_paid ?? 0;
                        $remaining = max(0, $newPrice - $alreadyPaid);

                        $p->update([
                            'amount' => $newPrice,
                            'payment_status' => $remaining > 0 ? 'pending' : 'paid'
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Main bareng berhasil diperbarui',
                'data' => $playTogether
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        
        $playTogether = PlayTogether::findOrFail($id);

        // Check if user is host
        if ($playTogether->created_by != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus main bareng ini'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $playTogether->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Main bareng berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus main bareng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users for invitation
     */
    public function searchUsers(Request $request, $id)
    {
        $search = $request->get('search', '');
        $playTogether = PlayTogether::findOrFail($id);
        
        // Get already invited or participating user IDs
        $participantUserIds = $playTogether->participants()->pluck('user_id')->toArray();
        $invitedUserIds = $playTogether->invitations()->pluck('invited_user_id')->toArray();
        $excludedIds = array_merge($participantUserIds, $invitedUserIds, [Auth::id()]);

        $users = User::where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->whereNotIn('id', $excludedIds)
            ->limit(10)
            ->get(['id', 'name', 'phone', 'avatar']);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Send invitation to user
     */
    public function inviteUser(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $playTogether = PlayTogether::findOrFail($id);
        
        // Check if user is host
        if ($playTogether->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya host yang bisa mengundang peserta'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Create invitation
            $invitation = PlayTogetherInvitation::create([
                'play_together_id' => $id,
                'invited_user_id' => $validated['user_id'],
                'invited_by' => Auth::id(),
                'status' => 'pending'
            ]);

            // Kirim Notifikasi ke User yang diundang
            Notification::sendMainBarengInvitationNotification(
                $validated['user_id'],
                Auth::user()->name,
                $playTogether->location,
                $playTogether->id,
                $invitation->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil dikirim',
                'data' => $invitation->load('invitedUser')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim undangan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve participant
     */
    public function approveParticipant(Request $request, $id, $participantId)
    {
        $playTogether = PlayTogether::findOrFail($id);
        
        // Check if user is host
        if ($playTogether->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya host yang bisa menyetujui peserta'
            ], 403);
        }

        $participant = PlayTogetherParticipant::findOrFail($participantId);

        try {
            DB::beginTransaction();

            $participant->update([
                'approval_status' => 'approved',
                'joined_at' => now()
            ]);

            // Update notification data for persistence
            if ($request->has('notification_id')) {
                $notification = Notification::find($request->notification_id);
                if ($notification) {
                    $notifData = $notification->data;
                    $notifData['action_taken'] = true;
                    $notifData['status'] = 'approved';
                    $notification->update(['data' => $notifData]);
                }
            }

            // Kirim notifikasi balasan ke User
            Notification::sendMainBarengJoinResponseNotification(
                $participant->user_id,
                'approved',
                $playTogether->location,
                $playTogether->id,
                Auth::id()
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil disetujui',
                'data' => $participant->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui peserta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject participant
     */
    public function rejectParticipant(Request $request, $id, $participantId)
    {
        $playTogether = PlayTogether::findOrFail($id);
        
        // Check if user is host
        if ($playTogether->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya host yang bisa menolak peserta'
            ], 403);
        }

        $participant = PlayTogetherParticipant::findOrFail($participantId);

        try {
            DB::beginTransaction();

            $participant->update([
                'approval_status' => 'rejected'
            ]);

            // Update notification data for persistence
            if ($request->has('notification_id')) {
                $notification = Notification::find($request->notification_id);
                if ($notification) {
                    $notifData = $notification->data;
                    $notifData['action_taken'] = true;
                    $notifData['status'] = 'rejected';
                    $notification->update(['data' => $notifData]);
                }
            }

            // Kirim notifikasi balasan ke User
            Notification::sendMainBarengJoinResponseNotification(
                $participant->user_id,
                'rejected',
                $playTogether->location,
                $playTogether->id,
                Auth::id()
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil ditolak',
                'data' => $participant->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak peserta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept invitation from notification
     */
    public function acceptInvitation(Request $request, $invitationId)
    {
        $user = Auth::user();
        $invitation = PlayTogetherInvitation::with('playTogether')->findOrFail($invitationId);

        if ($invitation->invited_user_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        if ($invitation->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Undangan sudah diproses'], 400);
        }

        try {
            DB::beginTransaction();

            // Update invitation status
            $invitation->update([
                'status' => 'accepted',
                'responded_at' => now()
            ]);

            // Update notification data for persistence
            if ($request->has('notification_id')) {
                $notification = Notification::find($request->notification_id);
                if ($notification) {
                    $notifData = $notification->data;
                    $notifData['action_taken'] = true;
                    $notifData['status'] = 'accepted';
                    $notification->update(['data' => $notifData]);
                }
            }

            // Join the play together
            PlayTogetherParticipant::updateOrCreate([
                'play_together_id' => $invitation->play_together_id,
                'user_id' => $user->id
            ], [
                'invited_by' => $invitation->invited_by,
                'invitation_id' => $invitation->id,
                'invitation_status' => 'accepted',
                'approval_status' => 'approved',
                'joined_at' => now(),
                'payment_status' => 'pending'
            ]);

            // Kirim notifikasi balasan ke Host
            Notification::sendMainBarengInvitationResponseNotification(
                $invitation->invited_by,
                $user->name,
                'accepted',
                $invitation->playTogether->location,
                $invitation->playTogether->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil diterima'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reject invitation from notification
     */
    public function rejectInvitation(Request $request, $invitationId)
    {
        $user = Auth::user();
        $invitation = PlayTogetherInvitation::with('playTogether')->findOrFail($invitationId);

        if ($invitation->invited_user_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        if ($invitation->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Undangan sudah diproses'], 400);
        }

        try {
            DB::beginTransaction();

            // Update invitation status
            $invitation->update([
                'status' => 'rejected',
                'responded_at' => now()
            ]);

            // Update notification data for persistence
            if ($request->has('notification_id')) {
                $notification = Notification::find($request->notification_id);
                if ($notification) {
                    $notifData = $notification->data;
                    $notifData['action_taken'] = true;
                    $notifData['status'] = 'rejected';
                    $notification->update(['data' => $notifData]);
                }
            }

            // Kirim notifikasi balasan ke Host
            Notification::sendMainBarengInvitationResponseNotification(
                $invitation->invited_by,
                $user->name,
                'rejected',
                $invitation->playTogether->location,
                $invitation->playTogether->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showQr($id)
    {
        // Ambil PlayTogether
        $playTogether = PlayTogether::findOrFail($id);
    
        // Ambil booking terkait
        $booking = $playTogether->booking; // pake relasi booking() di model
    
        // Bisa juga pakai participant jika ticket_code per user:
        $participant = $playTogether->participants()
            ->where('user_id', auth()->id())
            ->first();
    
        // Kalau ticket_code di participant
        $ticketCode = $participant->ticket_code ?? ($booking->ticket_code ?? 'N/A');
    
        // Buat URL QR
        $url = route('buyer.main_bareng_saya.show', $playTogether->id) . '?ticket=' . $ticketCode;
    
        // Kirim semua ke view
        return view('buyer.main_bareng_saya.qr', compact('playTogether', 'booking', 'participant', 'url'));
    }

    // ===============================
    // Adjust Price (Host only)
    // ===============================
    public function adjustPrice(Request $request, $id)
{
    $user = Auth::user();
    $playTogether = PlayTogether::with('participants.booking')->findOrFail($id);

    // Cek host
    if ($playTogether->created_by != $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'Hanya host yang bisa menyesuaikan biaya.'
        ], 403);
    }

    // Cek sudah adjust sebelumnya
    if ($playTogether->payment_type === 'adjusted') {
        return response()->json([
            'success' => false,
            'message' => 'Penyesuaian biaya hanya bisa dilakukan 1x.'
        ], 400);
    }

    $approvedParticipants = $playTogether->participants()
        ->where('approval_status', 'approved')
        ->get();

    $count = $approvedParticipants->count();
    if ($count == 0) {
        return response()->json([
            'success' => false,
            'message' => 'Belum ada peserta yang disetujui.'
        ], 400);
    }

   // =========================
    // Hitung lapang fee & join fee
    // =========================
    $lapangFeePerParticipant = 0;
    if ($playTogether->booking && $playTogether->booking->pay_by === 'participant') {
        $lapangFeePerParticipant = ceil($playTogether->booking->amount / $playTogether->max_participants);
    }

    $joinFeePerParticipant = ($playTogether->type === 'paid') ? $playTogether->price_per_person : 0;

    // Total biaya per peserta = join + lapang
    $newTotalPerParticipant = $lapangFeePerParticipant + $joinFeePerParticipant;

    // Total target biaya untuk semua peserta approved
    $totalTarget = ($joinFeePerParticipant + $lapangFeePerParticipant) * $count;

    // Harga baru per peserta approved
    $newTotalPerParticipant = ceil($totalTarget / $count);

    try {
        DB::beginTransaction();

        // Update play_together
        $playTogether->update([
            'price_per_person' => $newTotalPerParticipant,
            'payment_type' => 'adjusted'
        ]);

        // Update semua peserta approved
        foreach ($approvedParticipants as $p) {
            $alreadyPaid = (int)($p->total_paid ?? 0);

            // Hitung remaining berdasarkan pembayaran sebelumnya
            $remaining = max(0, $newTotalPerParticipant - $alreadyPaid);

            $p->update([
                'amount' => $newTotalPerParticipant,
                'payment_status' => $remaining > 0 ? 'pending' : 'paid',
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'new_price' => $newTotalPerParticipant,
            'message' => 'Biaya berhasil disesuaikan menjadi Rp ' . number_format($newTotalPerParticipant, 0, ',', '.')
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

}