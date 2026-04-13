<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\Otp;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $balanceDetails = Deposit::getLandownerBalanceDetails($userId);
        $balance = $balanceDetails['available_balance'];
        
        return view('landowner.deposit.index', compact('balance'));
    }

    public function getHistory(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $balanceDetails = Deposit::getLandownerBalanceDetails($userId);
            $balance = $balanceDetails['available_balance'];
            $totalDeposit = $balanceDetails['total_deposit'];
            
            $deposits = Deposit::getDepositHistoryForLandowner($userId, 50);
            
            $formattedDeposits = $deposits->map(function ($deposit) {
                return [
                    'id' => $deposit->id,
                    'amount' => (float)$deposit->amount,
                    'source_type' => $deposit->source_type,
                    'source_type_name' => $deposit->getSourceName(),
                    'source_type_icon' => $deposit->getSourceIcon(),
                    'description' => $deposit->description,
                    'status' => $deposit->status,
                    'status_label' => $deposit->getStatusLabel(),
                    'created_at' => $deposit->created_at->toDateTimeString(),
                    'formatted_date' => $deposit->created_at->format('d M Y H:i'),
                    'is_positive' => $deposit->isPositive(),
                    'is_negative' => $deposit->isNegative(),
                    'booking_info' => $deposit->booking ? [
                        'id' => $deposit->booking->id,
                        'ticket_code' => $deposit->booking->ticket_code,
                        'venue_name' => $deposit->booking->venue->venue_name ?? 'Lapangan',
                        'user_name' => $deposit->booking->user->name ?? 'Customer',
                        'booking_payment' => $deposit->booking->booking_payment,
                        'amount' => (float)$deposit->booking->amount,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'balance' => (float)$balance,
                'total_deposit' => (float)$totalDeposit,
                'deposits' => $formattedDeposits,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getHistory: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data.',
                'balance' => 0,
                'total_deposit' => 0,
                'deposits' => [],
                'balance_details' => [
                    'total_deposit' => 0,
                    'available_balance' => 0,
                ],
            ]);
        }
    }

    public function create()
    {
        return view('landowner.deposit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000', 'max:50000000'],
            'payment_method' => ['required', 'in:bank_transfer,e_wallet,card'],
        ]);

        $user = Auth::user();
        $amount = $validated['amount'];

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'source_type' => 'manual_deposit',
            'source_id' => $user->id,
            'status' => 'pending',
            'description' => 'Top up saldo via ' . $this->getPaymentMethodName($validated['payment_method']),
        ]);

        return redirect()->route('landowner.deposit.show', $deposit->id)
            ->with('success', 'Deposit berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function show(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        return view('landowner.deposit.show', [
            'deposit' => $deposit,
        ]);
    }

    public function confirm(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        if ($deposit->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Deposit ini sudah diproses.',
            ]);
        }

        $deposit->update(['status' => 'completed']);

        return redirect()->route('landowner.deposit.index')
            ->with('success', 'Deposit berhasil! Saldo Anda telah ditambahkan.');
    }

    public function cancel(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        if ($deposit->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Deposit ini sudah diproses.',
            ]);
        }

        $deposit->update(['status' => 'cancelled']);

        return redirect()->route('landowner.deposit.index')
            ->with('info', 'Deposit dibatalkan.');
    }

    private function getPaymentMethodName($method)
    {
        return match($method) {
            'bank_transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'card' => 'Kartu Kredit',
            default => 'Unknown'
        };
    }

    public function checkHasVenues()
    {
        $userId = Auth::id();
        $hasVenues = Venue::where('created_by', $userId)->exists();
        
        return response()->json([
            'hasVenues' => $hasVenues
        ]);
    }
}