<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Deposit;
use App\Models\PlayTogetherParticipant;
use App\Models\PlayTogether;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepositService
{
    /**
     * Get user's balance including unpaid bookings and play together
     */
    public static function getBalance($userId): float
    {
        try {
            // Total deposit yang sudah completed
            $totalDeposit = Deposit::where('user_id', $userId)
                ->where('status', 'completed')
                ->sum('amount');

            // Hitung total kekurangan dari booking yang belum dibayar
            $bookingDeficit = self::calculateBookingDeficit($userId);
            
            // Hitung total kekurangan dari play together yang belum dibayar
            $playTogetherDeficit = self::calculatePlayTogetherDeficit($userId);

            // Saldo = total deposit - total kekurangan
            return (float)$totalDeposit - $bookingDeficit - $playTogetherDeficit;
        } catch (\Exception $e) {
            Log::error('Error calculating balance for user ' . $userId . ': ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate booking deficit (belum bayar)
     */
    private static function calculateBookingDeficit($userId): float
    {
        try {
            $bookings = Booking::where('user_id', $userId)
                ->where(function ($query) {
                    // Booking dengan status pembayaran pending atau partial
                    $query->where('booking_payment', 'pending')
                        ->orWhere('booking_payment', 'partial');
                })
                ->get();

            $totalDeficit = 0;

            foreach ($bookings as $booking) {
                $amount = (float)($booking->amount ?? 0);
                $totalPaid = 0;
                
                if ($booking->pay_by === 'participant') {
                    $totalPaid = (float)($booking->total_paid ?? 0);
                } elseif ($booking->pay_by === 'host') {
                    $totalPaid = (float)($booking->paid_amount ?? 0);
                } else {
                    // Jika belum ada setting, anggap sebagai host
                    $totalPaid = (float)($booking->paid_amount ?? 0);
                }
                
                $deficit = $amount - $totalPaid;
                if ($deficit > 0) {
                    $totalDeficit += $deficit;
                }
            }

            return $totalDeficit;
        } catch (\Exception $e) {
            Log::error('Error calculating booking deficit: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate play together deficit (belum bayar)
     */
    private static function calculatePlayTogetherDeficit($userId): float
    {
        try {
            $deficit = 0;

            // 1. Kekurangan sebagai host (membuat play together yang paid)
            $hostPlayTogether = PlayTogether::where('created_by', $userId)
                ->where('type', 'paid')
                ->with(['booking'])
                ->get();

            foreach ($hostPlayTogether as $playTogether) {
                if ($playTogether->booking) {
                    $booking = $playTogether->booking;
                    $amount = (float)($booking->amount ?? 0);
                    $totalPaid = 0;
                    
                    if ($booking->pay_by === 'participant') {
                        $totalPaid = (float)($booking->total_paid ?? 0);
                    } elseif ($booking->pay_by === 'host') {
                        $totalPaid = (float)($booking->paid_amount ?? 0);
                    }
                    
                    $bookingDeficit = $amount - $totalPaid;
                    if ($bookingDeficit > 0) {
                        $deficit += $bookingDeficit;
                    }
                }
            }

            // 2. Kekurangan sebagai participant (join play together yang belum bayar)
            $participants = PlayTogetherParticipant::where('user_id', $userId)
                ->whereHas('playTogether', function ($query) {
                    $query->where('type', 'paid');
                })
                ->where('approval_status', 'approved')
                ->get();

            foreach ($participants as $participant) {
                $amount = (float)($participant->amount ?? 0);
                $totalPaid = (float)($participant->total_paid ?? 0);
                
                $participantDeficit = $amount - $totalPaid;
                if ($participantDeficit > 0) {
                    $deficit += $participantDeficit;
                }
            }

            return $deficit;
        } catch (\Exception $e) {
            Log::error('Error calculating play together deficit: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get user's actual deposit balance (as calculated in DepositController)
     */
    public static function getDepositBalance($userId): array
    {
        // Get bookings created by this user
        $userBookingIds = Booking::where('user_id', $userId)->pluck('id')->toArray();
        
        if (empty($userBookingIds)) {
            return [
                'total_balance' => 0,
                'withdrawable_amount' => 0,
            ];
        }
        
        // Total balance
        $totalBalance = Deposit::whereIn('booking_id', $userBookingIds)
            ->whereIn('status', ['pending', 'completed'])
            ->sum('amount');
        
        // Subtract processed withdrawals
        $processedWithdrawals = WithdrawalRequest::where('user_id', $userId)
            ->where('status', 'processed')
            ->sum('amount');
        
        $totalBalance = $totalBalance - $processedWithdrawals;
        $withdrawableAmount = max(0, $totalBalance);
        
        return [
            'total_balance' => $totalBalance,
            'withdrawable_amount' => $withdrawableAmount,
        ];
    }

    /**
     * Get deposit history with booking and play together deficits
     */
    public static function history($userId): array
    {
        try {
            $deposits = [];
            
            // 1. Deposit biasa
            $regularDeposits = Deposit::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($deposit) {
                    return self::formatDepositRecord($deposit);
                });

            $deposits = array_merge($deposits, $regularDeposits->toArray());

            // 2. Booking yang belum dibayar penuh (sebagai deficit record)
            $unpaidBookings = self::getUnpaidBookings($userId);
            $deposits = array_merge($deposits, $unpaidBookings);

            // 3. Play together yang belum dibayar (sebagai deficit record)
            $unpaidPlayTogether = self::getUnpaidPlayTogether($userId);
            $deposits = array_merge($deposits, $unpaidPlayTogether);

            // Sort by created_at descending
            usort($deposits, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            return $deposits;
        } catch (\Exception $e) {
            Log::error('Error getting deposit history: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unpaid bookings as deficit records
     */
    private static function getUnpaidBookings($userId): array
    {
        try {
            $bookings = Booking::where('user_id', $userId)
                ->where(function ($query) {
                    $query->where('booking_payment', 'pending')
                        ->orWhere('booking_payment', 'partial')
                        ->orWhere(function ($q) {
                            $q->whereColumn('total_paid', '<', 'amount')
                                ->orWhereColumn('paid_amount', '<', 'amount');
                        });
                })
                ->with(['venue:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            $deficitRecords = [];

            foreach ($bookings as $booking) {
                $totalAmount = (float)($booking->amount ?? 0);
                $totalPaid = 0;
                
                if ($booking->pay_by === 'participant') {
                    $totalPaid = (float)($booking->total_paid ?? 0);
                } elseif ($booking->pay_by === 'host') {
                    $totalPaid = (float)($booking->paid_amount ?? 0);
                }
                
                $deficitAmount = max(0, $totalAmount - $totalPaid);
                
                if ($deficitAmount > 0) {
                    $deficitRecords[] = [
                        'id' => 'booking_deficit_' . $booking->id,
                        'amount' => -$deficitAmount,
                        'source_type' => 'booking',
                        'source_type_name' => 'Booking Lapangan',
                        'source_type_icon' => 'fas fa-calendar-alt',
                        'description' => 'Kekurangan pembayaran booking ' . ($booking->venue->name ?? 'Lapangan'),
                        'status' => 'pending',
                        'created_at' => $booking->created_at->toDateTimeString(),
                        'reference' => [
                            'type' => 'booking',
                            'id' => $booking->id,
                            'ticket_code' => $booking->ticket_code,
                            'booking_payment' => $booking->booking_payment,
                            'pay_by' => $booking->pay_by,
                            'total_amount' => $totalAmount,
                            'total_paid' => $totalPaid
                        ]
                    ];
                }
            }

            return $deficitRecords;
        } catch (\Exception $e) {
            Log::error('Error getting unpaid bookings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unpaid play together as deficit records
     */
    private static function getUnpaidPlayTogether($userId): array
    {
        try {
            $deficitRecords = [];

            // 1. Sebagai host
            $hostPlayTogether = PlayTogether::where('created_by', $userId)
                ->where('type', 'paid')
                ->whereHas('booking', function ($query) {
                    $query->where(function ($q) {
                        $q->where('booking_payment', 'pending')
                            ->orWhere('booking_payment', 'partial');
                    });
                })
                ->with(['booking', 'venue'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            foreach ($hostPlayTogether as $playTogether) {
                if ($playTogether->booking) {
                    $booking = $playTogether->booking;
                    $totalAmount = (float)($booking->amount ?? 0);
                    $totalPaid = 0;
                    
                    if ($booking->pay_by === 'participant') {
                        $totalPaid = (float)($booking->total_paid ?? 0);
                    } elseif ($booking->pay_by === 'host') {
                        $totalPaid = (float)($booking->paid_amount ?? 0);
                    }
                    
                    $deficitAmount = max(0, $totalAmount - $totalPaid);
                    
                    if ($deficitAmount > 0) {
                        $deficitRecords[] = [
                            'id' => 'play_host_deficit_' . $playTogether->id,
                            'amount' => -$deficitAmount,
                            'source_type' => 'play_together_host',
                            'source_type_name' => 'Main Bareng (Host)',
                            'source_type_icon' => 'fas fa-users',
                            'description' => 'Kekurangan pembayaran sebagai host: ' . $playTogether->name,
                            'status' => 'pending',
                            'created_at' => $playTogether->created_at->toDateTimeString(),
                            'reference' => [
                                'type' => 'play_together_host',
                                'id' => $playTogether->id,
                                'name' => $playTogether->name,
                                'booking_id' => $playTogether->booking_id
                            ]
                        ];
                    }
                }
            }

            // 2. Sebagai participant
            $participants = PlayTogetherParticipant::where('user_id', $userId)
                ->whereHas('playTogether', function ($query) {
                    $query->where('type', 'paid');
                })
                ->where('approval_status', 'approved')
                ->with(['playTogether:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            foreach ($participants as $participant) {
                $amount = (float)($participant->amount ?? 0);
                $totalPaid = (float)($participant->total_paid ?? 0);
                $deficitAmount = max(0, $amount - $totalPaid);
                
                if ($deficitAmount > 0) {
                    $deficitRecords[] = [
                        'id' => 'play_participant_deficit_' . $participant->id,
                        'amount' => -$deficitAmount,
                        'source_type' => 'play_together_participant',
                        'source_type_name' => 'Main Bareng (Participant)',
                        'source_type_icon' => 'fas fa-user-friends',
                        'description' => 'Kekurangan pembayaran sebagai peserta: ' . ($participant->playTogether->name ?? 'Main Bareng'),
                        'status' => 'pending',
                        'created_at' => $participant->created_at->toDateTimeString(),
                        'reference' => [
                            'type' => 'play_together_participant',
                            'id' => $participant->id,
                            'play_together_id' => $participant->play_together_id,
                            'play_together_name' => $participant->playTogether->name ?? '',
                            'amount' => $amount,
                            'total_paid' => $totalPaid,
                            'payment_status' => $participant->payment_status
                        ]
                    ];
                }
            }

            return $deficitRecords;
        } catch (\Exception $e) {
            Log::error('Error getting unpaid play together: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format deposit record
     */
    private static function formatDepositRecord($deposit): array
    {
        try {
            $sourceInfo = self::getSourceInfo($deposit);

            return [
                'id' => $deposit->id,
                'amount' => (float)$deposit->amount,
                'source_type' => $deposit->source_type,
                'source_type_name' => $sourceInfo['name'],
                'source_type_icon' => $sourceInfo['icon'],
                'description' => $deposit->description,
                'status' => $deposit->status,
                'created_at' => $deposit->created_at->toDateTimeString(),
                'reference' => [
                    'id' => $deposit->id,
                    'booking_id' => $deposit->booking_id
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error formatting deposit record: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get source type info
     */
    private static function getSourceInfo($deposit): array
    {
        $types = [
            'booking' => ['name' => 'Booking Lapangan', 'icon' => 'fas fa-calendar-alt'],
            'play_together' => ['name' => 'Main Bareng', 'icon' => 'fas fa-users'],
            'sparring' => ['name' => 'Sparring', 'icon' => 'fas fa-fist-raised'],
            'manual_deposit' => ['name' => 'Top Up Saldo', 'icon' => 'fas fa-money-bill-wave'],
            'withdraw' => ['name' => 'Penarikan Saldo', 'icon' => 'fas fa-hand-holding-usd'],
            'refund' => ['name' => 'Pengembalian Dana', 'icon' => 'fas fa-undo'],
        ];

        return $types[$deposit->source_type] ?? ['name' => 'Transaksi', 'icon' => 'fas fa-exchange-alt'];
    }
}