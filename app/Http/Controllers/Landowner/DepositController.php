<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\WithdrawalRequest;
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
        $withdrawableAmount = $balanceDetails['withdrawable_amount'];
        
        return view('landowner.deposit.index', compact('balance', 'withdrawableAmount'));
    }

    public function getHistory(Request $request)
    {
        try {
            $userId = Auth::id();
            
            $balanceDetails = Deposit::getLandownerBalanceDetails($userId);
            $balance = $balanceDetails['available_balance'];
            $withdrawableAmount = $balanceDetails['withdrawable_amount'];
            $totalDeposit = $balanceDetails['total_deposit'];
            $totalWithdrawalProcessed = $balanceDetails['total_withdrawal_processed'];
            $totalWithdrawalPending = $balanceDetails['total_withdrawal_pending'];
            
            $deposits = Deposit::getDepositHistoryForLandowner($userId, 50);
            
            $withdrawals = Deposit::getWithdrawalHistoryForLandowner($userId);
            
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

            $formattedWithdrawals = $withdrawals->map(function ($withdrawal) {
                return [
                    'id' => $withdrawal->id,
                    'amount' => (float)$withdrawal->amount,
                    'bank_name' => $withdrawal->bank_name,
                    'account_number' => $withdrawal->account_number,
                    'account_holder_name' => $withdrawal->account_holder_name,
                    'status' => $withdrawal->status,
                    'status_label' => $withdrawal->status_label,
                    'status_class' => $withdrawal->status_class,
                    'created_at' => $withdrawal->created_at->toDateTimeString(),
                    'formatted_date' => $withdrawal->created_at->format('d M Y H:i'),
                    'processed_at' => $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y H:i') : null,
                    'rejection_reason' => $withdrawal->rejection_reason,
                ];
            });

            return response()->json([
                'success' => true,
                'balance' => (float)$balance,
                'withdrawable_amount' => (float)$withdrawableAmount,
                'total_deposit' => (float)$totalDeposit,
                'total_withdrawal_processed' => (float)$totalWithdrawalProcessed,
                'total_withdrawal_pending' => (float)$totalWithdrawalPending,
                'deposits' => $formattedDeposits,
                'withdrawals' => $formattedWithdrawals,
                'balance_details' => $balanceDetails,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getHistory: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data.',
                'balance' => 0,
                'withdrawable_amount' => 0,
                'total_deposit' => 0,
                'total_withdrawal_processed' => 0,
                'total_withdrawal_pending' => 0,
                'deposits' => [],
                'withdrawals' => [],
                'balance_details' => [
                    'total_deposit' => 0,
                    'total_withdrawal_processed' => 0,
                    'total_withdrawal_pending' => 0,
                    'available_balance' => 0,
                    'withdrawable_amount' => 0,
                    'has_pending_withdrawal' => false,
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

    public function withdrawIndex()
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $balanceDetails = Deposit::getLandownerBalanceDetails($userId);
        $hasPendingOrApproved = $balanceDetails['has_pending_withdrawal'];
        $withdrawableAmount = $balanceDetails['withdrawable_amount'];
        
        $withdrawals = WithdrawalRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Cek kelengkapan data bank
        $isBankDataComplete = !empty($user->bank_name) && !empty($user->account_number) && !empty($user->account_holder_name);

        return view('landowner.deposit.withdraw', compact('withdrawableAmount', 'withdrawals', 'hasPendingOrApproved', 'balanceDetails', 'user', 'isBankDataComplete'));
    }

    public function withdrawStore(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $balanceDetails = Deposit::getLandownerBalanceDetails($userId);
        $hasPendingOrApproved = $balanceDetails['has_pending_withdrawal'];
            
        if ($hasPendingOrApproved) {
            throw ValidationException::withMessages([
                'amount' => 'Anda masih memiliki permintaan penarikan yang sedang diproses. Tunggu hingga selesai atau ditolak sebelum membuat permintaan baru.',
            ]);
        }
        
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:5000', 'max:100000000'],
        ]);

        $withdrawableAmount = $balanceDetails['withdrawable_amount'];
        
        if ($validated['amount'] > $withdrawableAmount) {
            throw ValidationException::withMessages([
                'amount' => 'Jumlah penarikan melebihi saldo yang tersedia untuk ditarik (Rp ' . number_format($withdrawableAmount, 0, ',', '.') . ')',
            ]);
        }

        // Validasi data bank user
        if (empty($user->bank_name) || empty($user->account_number) || empty($user->account_holder_name)) {
            throw ValidationException::withMessages([
                'amount' => 'Data bank Anda belum lengkap. Silakan lengkapi data bank di profil terlebih dahulu.',
            ]);
        }

        // Simpan data withdraw request di session untuk digunakan setelah OTP valid
        session()->put('pending_withdraw', [
            'user_id' => $userId,
            'amount' => $validated['amount'],
            'bank_name' => $user->bank_name,
            'account_number' => $user->account_number,
            'account_holder_name' => $user->account_holder_name,
        ]);

        // Generate dan kirim OTP
        $this->kirimOtpWithdraw($user->phone, $user->name);

        // ROUTE BARU: landowner.withdraw.otp (tanpa prefix deposit)
        return redirect()->route('landowner.withdraw.otp')
            ->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    private function kirimOtpWithdraw($phone, $name)
    {
        $otp = rand(100000, 999999);

        // Hapus OTP lama untuk phone ini
        Otp::where('phone', $phone)->delete();

        // Buat OTP baru
        Otp::create([
            'user_id' => Auth::id(),
            'phone' => $phone,
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);

        $pesan = "Halo $name,\n\nKode OTP untuk verifikasi penarikan saldo Anda adalah: *$otp*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        kirimWa($phone, $pesan);
    }

    public function withdrawOtpForm()
    {
        if (!session()->has('pending_withdraw')) {
            return redirect()->route('landowner.withdraw.saldo')
                ->with('error', 'Session habis, silakan ulangi proses penarikan.');
        }

        $withdrawData = session('pending_withdraw');

        return view('landowner.deposit.withdraw-otp', compact('withdrawData'));
    }

    public function withdrawOtpSubmit(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $withdrawData = session('pending_withdraw');
        if (!$withdrawData) {
            return redirect()->route('landowner.withdraw.saldo')
                ->with('error', 'Session habis, silakan ulangi proses penarikan.');
        }

        $user = Auth::user();

        // Cari OTP yang valid
        $otpRecord = Otp::where('phone', $user->phone)
            ->where('code', $request->otp)
            ->where('expired_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah atau sudah kedaluwarsa.');
        }

        // OTP valid, hapus semua OTP untuk phone ini
        Otp::where('phone', $user->phone)->delete();

        // Buat withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $withdrawData['user_id'],
            'amount' => $withdrawData['amount'],
            'bank_name' => $withdrawData['bank_name'],
            'account_number' => $withdrawData['account_number'],
            'account_holder_name' => $withdrawData['account_holder_name'],
            'status' => 'pending',
        ]);

        // Buat deposit negatif
        Deposit::create([
            'user_id' => $withdrawData['user_id'],
            'amount' => -$withdrawData['amount'],
            'source_type' => 'withdraw',
            'source_id' => $withdrawal->id,
            'status' => 'pending',
            'description' => 'Penarikan saldo ke ' . $withdrawData['bank_name'] . ' - ' . $withdrawData['account_number'],
        ]);

        // Hapus session pending withdraw
        session()->forget('pending_withdraw');

        return redirect()->route('landowner.deposit.index')
            ->with('success', 'Permintaan penarikan berhasil diajukan. Akan diproses dalam 1-3 hari kerja.');
    }

    public function withdrawOtpResend()
    {
        $withdrawData = session('pending_withdraw');
        if (!$withdrawData) {
            return redirect()->route('landowner.withdraw.saldo')
                ->with('error', 'Session habis, silakan ulangi proses penarikan.');
        }

        $user = Auth::user();
        $this->kirimOtpWithdraw($user->phone, $user->name);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }

    public function withdrawShow(WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('landowner.deposit.withdraw-history', compact('withdrawal'));
    }

    public function withdrawHistory()
    {
        $userId = Auth::id();
        
        $withdrawals = WithdrawalRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landowner.deposit.withdraw-history', compact('withdrawals'));
    }

    public function updateWithdrawalStatus(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:cancelled',
        ]);

        if ($withdrawal->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Hanya permintaan penarikan yang pending yang dapat dibatalkan.',
            ]);
        }

        $withdrawal->update([
            'status' => $validated['status'],
            'rejection_reason' => 'Dibatalkan oleh pengguna',
        ]);

        $deposit = Deposit::where('source_type', 'withdraw')
            ->where('source_id', $withdrawal->id)
            ->first();

        if ($deposit) {
            $deposit->update([
                'status' => 'cancelled',
                'description' => $deposit->description . ' (Dibatalkan)',
            ]);
        }

        return redirect()->route('landowner.deposit.index')
            ->with('success', 'Penarikan berhasil dibatalkan.');
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