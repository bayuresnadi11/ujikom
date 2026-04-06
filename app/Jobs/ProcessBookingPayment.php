<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Deposit;
use App\Models\BookingParticipantPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessBookingPayment implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $bookingId;
    protected $payload;

    public function __construct($bookingId, $payload)
    {
        $this->bookingId = $bookingId;
        $this->payload = $payload;
    }

    public function handle()
    {
        $booking = Booking::with(['playTogether.participants', 'schedule'])->find($this->bookingId);
        if (!$booking) return;

        DB::transaction(function () use ($booking) {

            // ❗ CEK BIAR GA DOUBLE PROSES
            if ($booking->booking_payment === 'full') {
                return;
            }

            if ($booking->type === 'play_together') {

                $playTogether = $booking->playTogether;
                if (!$playTogether) return;

                foreach ($playTogether->participants as $participant) {

                    $amount = app(\App\Http\Controllers\Buyer\BookingController::class)
                        ->calculateParticipantAmount($booking, $playTogether, $participant->user_id);

                    // update participant
                    $participant->update([
                        'payment_status' => 'paid',
                        'amount' => $amount,
                        'paid_at' => now(),
                    ]);

                    // booking participant payment
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

                    $rentalPrice = $booking->schedule->rental_price;
                    $maxParticipants = max(1, (int) $playTogether->max_participants);
                    $joinFee = (float) ($playTogether->price_per_person ?? 0);

                    $lapangPerPerson = $booking->pay_by === 'participant'
                        ? $rentalPrice / $maxParticipants
                        : 0;

                    // ✅ ANTI DUPLICATE
                    if ($lapangPerPerson > 0) {
                        Deposit::firstOrCreate([
                            'booking_id' => $booking->id,
                            'user_id' => $participant->user_id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $lapangPerPerson,
                        ], [
                            'status' => 'completed',
                            'description' => 'Pembayaran lapang Main Bareng',
                        ]);
                    }

                    if ($joinFee > 0) {
                        Deposit::firstOrCreate([
                            'booking_id' => $booking->id,
                            'user_id' => $participant->user_id,
                            'source_type' => 'play_together',
                            'source_id' => $playTogether->id,
                            'amount' => $joinFee,
                        ], [
                            'status' => 'completed',
                            'description' => 'Pembayaran join fee Main Bareng',
                        ]);
                    }
                }

                // ================= HOST =================
                if ($booking->pay_by === 'host') {

                    Deposit::firstOrCreate([
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'amount' => $booking->schedule->rental_price,
                    ], [
                        'source_type' => 'play_together',
                        'source_id' => $playTogether->id,
                        'status' => 'completed',
                        'description' => 'Pembayaran host - lapang',
                    ]);
                }

                // ================= TOTAL =================
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
                    'paid_at' => now(),
                ]);

            } else {

                // REGULAR / SPARRING
                Deposit::firstOrCreate([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                ], [
                    'source_type' => $booking->type === 'sparring' ? 'sparring' : 'booking',
                    'source_id' => $booking->id,
                    'amount' => $booking->amount,
                    'status' => 'completed',
                    'description' => 'Pembayaran Booking via Midtrans',
                ]);

                $booking->update([
                    'booking_payment' => 'full',
                    'total_paid' => $booking->amount,
                    'paid_amount' => $booking->amount,
                    'paid_at' => now(),
                ]);
            }
        });
    }
}