<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        return view('buyer.withdraw.index', [
            'balance'    => DepositService::getBalance($userId),
            'withdrawals' => WithdrawalRequest::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create()
    {
        $userId = Auth::id();
        $balance = DepositService::getBalance($userId);

        return view('buyer.withdraw.create', [
            'balance' => $balance,
        ]);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $balance = DepositService::getBalance($userId);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:50000', 'max:' . $balance],
            'account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'in:BCA,BNI,BRI,MANDIRI,CIMB,OVO,DANA,GOPAY'],
            'account_holder_name' => ['required', 'string', 'max:255'],
        ]);

        if ($balance < $validated['amount']) {
            throw ValidationException::withMessages([
                'amount' => 'Saldo tidak cukup untuk melakukan penarikan.',
            ]);
        }

        // Create withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $userId,
            'amount' => $validated['amount'],
            'account_number' => $validated['account_number'],
            'bank_name' => $validated['bank_name'],
            'account_holder_name' => $validated['account_holder_name'],
            'status' => 'pending',
        ]);

        // Debit from deposit balance
        DepositService::debit(
            userId: $userId,
            amount: $validated['amount'],
            sourceType: 'withdrawal',
            sourceId: $withdrawal->id,
            description: 'Penarikan dana ke ' . $validated['bank_name'] . ' (' . $validated['account_number'] . ')'
        );

        return redirect()->route('buyer.withdraw.show', $withdrawal->id)
            ->with('success', 'Permintaan penarikan berhasil dibuat. Kami akan mempreses dalam 1-2 hari kerja.');
    }

    public function show(WithdrawalRequest $withdrawalRequest)
    {
        // Check if user owns this withdrawal
        if ($withdrawalRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('buyer.withdraw.show', [
            'withdrawal' => $withdrawalRequest,
        ]);
    }

    public function cancel(WithdrawalRequest $withdrawalRequest)
    {
        // Check if user owns this withdrawal
        if ($withdrawalRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel pending withdrawals
        if ($withdrawalRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya permintaan penarikan yang tertunda yang dapat dibatalkan.');
        }

        $withdrawalRequest->update(['status' => 'rejected']);

        // Refund the amount back to balance
        DepositService::credit(
            userId: $withdrawalRequest->user_id,
            amount: $withdrawalRequest->amount,
            sourceType: 'withdrawal_canceled',
            sourceId: $withdrawalRequest->id,
            description: 'Pembatalan penarikan dana'
        );

        return redirect()->route('buyer.withdraw.index')
            ->with('success', 'Permintaan penarikan berhasil dibatalkan. Saldo telah dikembalikan.');
    }
}
