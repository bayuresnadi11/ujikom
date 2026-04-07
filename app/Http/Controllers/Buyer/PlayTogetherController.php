<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\PlayTogether;
use App\Models\PlayTogetherParticipant;
use App\Models\PlayTogetherInvitation;
use App\Models\User;
use App\Models\BookingParticipantPayment; 
use App\Models\Notification;
use App\Models\Setting;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlayTogetherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Ambil data dengan paginate
        $playTogethers = PlayTogether::with([
                'booking.venue.category',
                'creator',
                'participants' => function ($q) {
                    $q->where('approval_status', '!=', 'rejected');
                }
            ])
            ->whereIn('status', ['pending', 'active'])
            ->whereDate('date', '>=', $today)
            ->whereHas('booking.schedule', function ($q) use ($today) {
                $q->whereDate('date', '>=', $today);
            })
            // Filter Privacy
            ->where(function($query) use ($user) {
                $query->where('privacy', 'public')
                    ->orWhere(function($q) use ($user) {
                        $q->where('privacy', 'community');
                        if ($user) {
                            $q->whereHas('community.members', function($m) use ($user) {
                                $m->where('user_id', $user->id)
                                    ->where('status', 'active');
                            });
                        } else {
                            $q->whereRaw('1 = 0'); // guest tidak bisa lihat
                        }
                    });
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->search;
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('booking.venue', function ($venueQuery) use ($search) {
                        $venueQuery->where('venue_name', 'like', "%{$search}%")
                                ->orWhere('location', 'like', "%{$search}%");
                    })
                    ->orWhereHas('creator', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10);

        // Hitung biaya per orang untuk tiap playTogether
        foreach ($playTogethers->items() as $pt) { // <-- items() ambil collection murni
            $pt->biayaPerOrang = 0;
            $pt->displayTotal = 0;

            if ($pt->booking && $pt->max_participants > 0) {
                $totalFee = $pt->booking->total_fee ?? 0; 
                $pt->biayaPerOrang = $totalFee / $pt->max_participants;
                $pt->displayTotal = $totalFee;
            }
        }

        // Ambil participant user
        $userParticipant = null;
        if ($user) {
            $userId = $user->id;
            // flatMap hanya bisa dipanggil dari Collection
            $allParticipants = collect($playTogethers->items())->flatMap->participants;
            $userParticipant = $allParticipants->firstWhere('user_id', $userId);
        }

        return view('buyer.main_bareng.index', compact('playTogethers', 'today', 'userParticipant'));
    }

    public function show($id)
    {
        $playTogether = PlayTogether::with([
            'booking.venue.category',
            'booking.schedule',
            'participants.user',
            'creator',
            'invitations.invitedUser',
            'community'
        ])->findOrFail($id);

        $user = Auth::user();
        
        // Cek akses view jika privacy community
        if ($playTogether->privacy === 'community') {
             // Jika guest, tolak atau suruh login (tapi biasanya show view handle ini)
             // Jika user login tapi bukan member, idealnya redirect atau show limited info.
             // Namun, untuk konsistensi, kita biarkan show tapi tombol join disable di view (logic line 88-97)
             // Tapi lebih aman kita cek membership di sini
             if ($user) {
                 $isMember = false;
                 if ($playTogether->community) {
                     $isMember = \App\Models\CommunityMember::where('community_id', $playTogether->community_id)
                         ->where('user_id', $user->id)
                         ->where('status', 'active')
                         ->exists();
                 }
                 // Logic tambahan jika bukan member tapi mencoba akses? 
                 // Kita biarkan dulu agar user bisa lihat "Harus join komunitas dulu"
             }
        }

        // Inisialisasi variabel untuk guest
        $hasJoined = false;
        $hasApplied = false;
        $isCreator = false;
        $showJoinButton = false;
        $showApplyButton = false;
        $showHostJoinButton = false;
        
        // Hanya cek jika user login
        if ($user) {
            $participant = PlayTogetherParticipant::where('play_together_id', $id)
                ->where('user_id', $user->id)
                ->first();
        
            $hasJoined = $participant && $participant->approval_status === 'approved';
            $hasApplied = $participant && $participant->approval_status === 'pending';    
            
            $isCreator = $playTogether->created_by == $user->id;
            
            if (!$hasJoined) {
                if ($isCreator) {
                    $showHostJoinButton = true;
                } else if ($playTogether->status !== 'active') {
                    if ($playTogether->host_approval) {
                        $showApplyButton = true;
                    } else {
                        $showJoinButton = true;
                    }
                }
            }
        } else {
            // Untuk guest, tampilkan tombol join/apply (akan redirect ke login)
            $showJoinButton = true;
            $showApplyButton = $playTogether->host_approval;
        }
        
        $approvedParticipantsCount = $playTogether->participants()
            ->where('approval_status', 'approved')
            ->count();
        
        $approvedParticipants = $playTogether->participants()
            ->with('user')
            ->where('approval_status', 'approved')
            ->get();
        
        $pendingParticipants = $playTogether->participants()
            ->with('user')
            ->where('approval_status', 'pending')
            ->get();

        // Check if quota is full
        $isFull = $approvedParticipantsCount >= $playTogether->max_participants;

        $setting = \App\Models\Setting::first();

        return view('buyer.main_bareng.show', compact(
            'playTogether',
            'hasJoined',
            'hasApplied',
            'participant', // Ubah dari userParticipant jadi participant agar sesuai variabel di atas
            'isCreator',
            'showJoinButton',
            'showApplyButton',
            'showHostJoinButton',
            'approvedParticipantsCount',
            'approvedParticipants',
            'pendingParticipants',
            'isFull', // Add this
            'setting' // Tambahkan ini
        ));
    }

    public function join(Request $request, $id)
    {
        // Hanya buyer yang bisa join
        if (!auth()->check() || auth()->user()->role !== 'buyer') {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk bergabung.'
            ], 401);
        }

        $user = auth()->user();
        $playTogether = PlayTogether::with(['booking.venue', 'community'])->findOrFail($id);
        
        // ENFORCE PRIVACY CHECK
        if ($playTogether->privacy === 'community' && $playTogether->community_id) {
            $isMember = \App\Models\CommunityMember::where('community_id', $playTogether->community_id)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();
            
            if (!$isMember) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Anda harus menjadi anggota komunitas ' . ($playTogether->community->name ?? '') . ' untuk bergabung.'
                ], 403);
            }
        }




        // Cek apakah sudah bergabung
        $existingParticipant = PlayTogetherParticipant::where('play_together_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingParticipant) {
            if ($existingParticipant->payment_status === 'paid' || $existingParticipant->approval_status === 'approved') {
                return response()->json(['success' => false, 'message' => 'Anda sudah bergabung.'], 400);
            }
            // Jika status pending/belum bayar, kita bisa lanjutkan untuk retry payment (logic di bawah)
            // Hapus yang lama atau update? Lebih aman update, tapi untuk simplifikasi kita handle fresh join dulu.
            // Jika user me-retry join, kita pakai record yg ada.
        }

        $isHost = $request->input('is_host', false);
        
        // Logika Approval
        // Jika join sebagai host -> auto approved
        // Jika perlu approval host -> pending
        // Jika auto join -> approved
        $needsApproval = $request->input('needs_approval', false);
        
        // Default Cost Calculation
        $booking = $playTogether->booking;


        // Hitung biaya untuk referensi (disimpan di participant tapi belum bayar)


        $lapangFee = 0;
        $joinFee = 0;
        
        // Hitung Lapang Fee (Split Bill)
        if ($booking->pay_by === 'participant') {
            $lapangFee = ceil($booking->amount / $playTogether->max_participants);
        }
        
        // Hitung Join Fee (Paid Activity)
        if ($playTogether->type === 'paid') {
            $joinFee = (int) $playTogether->price_per_person;
        }
        
        $totalAmount = $lapangFee + $joinFee;



        $needsPayment = $totalAmount > 0;

        DB::beginTransaction();
        try {
            // Determine initial status
            // Kalau butuh bayar, status payment 'pending'. Approval status terserah rule, tapi biasanya 'pending' sampai dibayar.
            // Kita set approval_status sesuai rule dulu.
            $approvalStatus = ($needsApproval && !$isHost) ? 'pending' : 'approved';
            
            // Generate Order ID unik
            $orderId = 'JOIN-' . $id . '-' . $user->id . '-' . time();

            // Create or Update Participant
            $participant = PlayTogetherParticipant::updateOrCreate(
                ['play_together_id' => $id, 'user_id' => $user->id],
                [
                    'approval_status' => $approvalStatus, 
                    'joined_at' => (!$needsPayment && $approvalStatus === 'approved') ? now() : null, // Set joined_at only if fully done
                    'amount' => $totalAmount,
                    'payment_status' => $needsPayment ? 'pending' : 'free',
                    'midtrans_order_id' => $orderId,
                    'total_paid' => 0
                ]
            );

            $snapToken = null;

            // REMOVED immediate payment logic to allow "Join First, Pay Later"
            // Payment will be handled via the "Bayar" button in the detail page
            
            // If Pending Approval -> Notify Host
            if ($approvalStatus === 'pending') {
                Notification::sendMainBarengJoinRequestNotification(
                    $playTogether->created_by,
                    $user->name,
                    $playTogether->booking->venue->venue_name ?? $playTogether->booking->venue->location,
                    $playTogether->id,
                    $participant->id,
                    $user->id
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $approvalStatus === 'pending' ? 'Permintaan bergabung telah dikirim. Menunggu persetujuan host.' : 'Berhasil bergabung! Silakan lakukan pembayaran jika diperlukan.',
                'needs_payment' => false, // Set to false to prevent immediate Midtrans popup
                'amount' => $totalAmount,
                'snap_token' => null,
                'order_id' => $orderId,
                'needs_approval' => $approvalStatus === 'pending'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }    

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'buyer') {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $playTogether = PlayTogether::findOrFail($id);
        
        if ($playTogether->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus Main Bareng ini.'
            ], 403);
        }

        PlayTogetherParticipant::where('play_together_id', $id)->delete();
        PlayTogetherInvitation::where('play_together_id', $id)->delete();
        $playTogether->delete();

        return response()->json([
            'success' => true,
            'message' => 'Main Bareng berhasil dihapus.'
        ]);
    }

    public function processPayment(Request $request, $id)
    {
        $user = Auth::user();
        $playTogether = PlayTogether::with('booking')->findOrFail($id);
        
        $participant = PlayTogetherParticipant::where('play_together_id', $id)
            ->where('user_id', $user->id)
            ->first();

        // Jika participant belum ada (harusnya sudah dibuat di join), buat baru
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Silakan klik Gabung terlebih dahulu.'], 400);
        }

        // Hitung Amount
        $lapangFee = 0;
        if ($playTogether->booking && $playTogether->booking->pay_by === 'participant') {
            $lapangFee = ceil($playTogether->booking->amount / $playTogether->max_participants);
        }
        $joinFee = ($playTogether->type === 'paid') ? (int)$playTogether->price_per_person : 0;
        $totalAmount = $lapangFee + $joinFee;

        // Config Midtrans
        $setting = Setting::first();
        if (!$setting || !$setting->midtrans_server_key) {
            return response()->json(['success' => false, 'message' => 'Konfigurasi Midtrans belum diatur.'], 500);
        }

        Config::$serverKey = $setting->midtrans_server_key;
        Config::$isProduction = (bool) $setting->midtrans_is_production;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'JOIN-' . $id . '-' . $user->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'item_details' => []
        ];

        if ($lapangFee > 0) {
            $params['item_details'][] = [
                'id' => 'lapang-fee',
                'price' => (int) $lapangFee,
                'quantity' => 1,
                'name' => 'Patungan Lapangan'
            ];
        }
        if ($joinFee > 0) {
            $params['item_details'][] = [
                'id' => 'join-fee',
                'price' => (int) $joinFee,
                'quantity' => 1,
                'name' => 'Biaya Join'
            ];
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            $participant->update([
                'midtrans_order_id' => $orderId,
                'payment_token' => $snapToken,
                'amount' => $totalAmount,
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createParticipant(Request $request)
    {
        $orderId = $request->order_id;
        $participant = PlayTogetherParticipant::where('midtrans_order_id', $orderId)->first();

        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Peserta tidak ditemukan.'], 404);
        }

        $playTogether = $participant->playTogether;
        $joinFee = 0;
        if ($playTogether->type === 'paid') {
            $joinFee = (float)$playTogether->price_per_person;
        }

        $lapangFee = 0;
        if ($playTogether->booking && $playTogether->booking->payment_type === 'split') {
            $totalVenueCost = (float)$playTogether->booking->total_amount;
            $maxParticipants = (int)$playTogether->max_participants;
            $lapangFee = $totalVenueCost / $maxParticipants;
        }

        // Update status become paid
        $participant->update([
            'payment_status' => 'paid',
            'total_paid' => $participant->amount,
            'paid_at' => now(),
            'midtrans_transaction_status' => $request->transaction_status ?? 'settlement'
        ]);

        // Create Payment Record
        if ($participant->amount > 0) {
            BookingParticipantPayment::create([
                'user_id' => $participant->user_id,
                'booking_id' => $participant->playTogether->booking_id,
                'play_together_participant_id' => $participant->id,
                'amount' => $lapangFee,
                'status' => 'paid',
                'description' => 'Pembayaran Join Main Bareng',
                'midtrans_order_id' => $orderId,
                'paid_at' => now()
            ]);
        }

        // -------------------------
        // NOTIFICATION LOGIC
        // -------------------------
        try {
            // 1. Notify User (Payer) - Payment Success
            Notification::sendPaymentSuccessNotification(
                $participant->user_id,
                $participant->amount,
                $participant->playTogether->booking_id,
                $participant->play_together_id
            );

            // 2. Notify Host - New Paid Participant
            // If needs approval or not, host should know someone paid and joined/requested.
            // If it's Auto-Join ($participant->approval_status === 'approved'), user is already IN.
            // If it's Manual Approval ($participant->approval_status === 'pending'), host must approve but user paid.
            
            $hostId = $participant->playTogether->created_by;
            
            // Avoid notifying self if host pays for themselves (rare but possible)
            if ($hostId != $participant->user_id) {
                // Determine Venue Name/Location
                $location = 'Lapangan'; // Default fallback
                if ($participant->playTogether->booking && $participant->playTogether->booking->venue) {
                    $location = $participant->playTogether->booking->venue->venue_name;
                }

                if ($participant->approval_status === 'pending') {
                    // Send "Join Request Paid" notification
                    Notification::sendMainBarengJoinRequestPaidNotification(
                        $hostId,
                        $participant->user->name,
                        $location,
                        $participant->play_together_id,
                        $participant->id,
                        $participant->user_id
                    );
                } else {
                    // Auto-joined, maybe just standard notification or also "Paid" one?
                    // User requested "notif untuk si pemilik... tidak bias menolak".
                    // This implies "Request" context. But for auto-join, it's just info.
                    // We'll use the same "Paid" notification but maybe title changes dynamically 
                    // in Notification model, but for now stick to "sendMainBarengJoinRequestPaidNotification"
                    // because it emphasizes "Sudah Bayar".
                     Notification::sendMainBarengJoinRequestPaidNotification(
                        $hostId,
                        $participant->user->name,
                        $location,
                        $participant->play_together_id,
                        $participant->id,
                        $participant->user_id
                    );
                }
            }

            $this->updateBookingPaymentStatus(
                $participant->playTogether->booking_id
            );
            
        } catch (\Exception $e) {
            // Log error but don't fail the response
            \Illuminate\Support\Facades\Log::error('Failed to send payment notifications: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dan Anda telah bergabung.',
            'needs_approval' => $participant->approval_status === 'pending'
        ]);
    }

    public function checkPaymentStatus($orderId)
    {
        $participant = PlayTogetherParticipant::where('midtrans_order_id', $orderId)->first();
        
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Peserta tidak ditemukan'], 404);
        }

        if ($participant->payment_status === 'paid') {
            return response()->json(['success' => true, 'status' => 'completed', 'message' => 'Pembayaran berhasil.']);
        }

        // Optional: Check Midtrans Status API logic here
        // For now return pending
        return response()->json(['success' => true, 'status' => 'pending']);
    }

    private function updateBookingPaymentStatus($bookingId)
    {
        $booking = \App\Models\Booking::find($bookingId);

        $totalLapangPaid = BookingParticipantPayment::where('booking_id', $bookingId)
            ->where('status', 'paid')
            ->sum('amount');

        $booking->paid_amount = $totalLapangPaid;

        if ($totalLapangPaid <= 0) {
            $booking->booking_payment = 'pending';
        } elseif ($totalLapangPaid < $booking->amount) {
            $booking->booking_payment = 'partial';
        } else {
            $booking->booking_payment = 'full';
        }

        $booking->save();
    }
}