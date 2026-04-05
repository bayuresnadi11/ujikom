<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\WithdrawalRequest;
use App\Models\Otp;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepositController extends Controller
{
    /**
     * Display deposit index page with transaction history
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all deposits for display
        $deposits = $this->getUserDeposits($user->id);
        
        // Calculate balance
        $balanceData = $this->calculateUserBalance($user->id);
        
        // Get withdrawal history
        $withdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($withdrawal) {
                $withdrawal->status_label = match($withdrawal->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'processed' => 'Diproses',
                    'rejected' => 'Ditolak',
                    default => 'Tidak Diketahui'
                };
                
                $withdrawal->status_class = match($withdrawal->status) {
                    'pending' => 'status-pending',
                    'approved' => 'status-approved',
                    'processed' => 'status-processed',
                    'rejected' => 'status-rejected',
                    default => ''
                };
                
                return $withdrawal;
            });
        
        return view('buyer.deposit.index', [
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
            'totalBalance' => $balanceData['total_balance'],
            'withdrawableAmount' => $balanceData['withdrawable_amount'],
        ]);
    }

    /**
     * Get deposits for user based on complex business rules
     */
    private function getUserDeposits($userId)
    {
        // Get bookings created by this user
        $userBookingIds = Booking::where('user_id', $userId)->pluck('id')->toArray();
        
        // Get deposits
        $deposits = Deposit::where(function($query) use ($userId, $userBookingIds) {
                // Case 1: Deposits with booking_id from user's bookings (any user_id can pay)
                if (!empty($userBookingIds)) {
                    $query->whereIn('booking_id', $userBookingIds);
                }
                
                // Case 2: Deposits where this user paid (but booking not theirs)
                $query->orWhere('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($deposit) use ($userId, $userBookingIds) {
                // Determine ownership
                $deposit->is_own_booking = in_array($deposit->booking_id, $userBookingIds);
                $deposit->is_own_payment = $deposit->user_id == $userId;
                
                // Determine if affects balance
                $deposit->affects_balance = $deposit->is_own_booking;
                
                // Add display info
                if ($deposit->is_own_booking && !$deposit->is_own_payment) {
                    if ($deposit->source_type == 'booking') {
                        $deposit->payment_info = 'Pembayaran booking oleh participant';
                    } else {
                        $deposit->payment_info = 'Pembayaran main bareng/sparring oleh participant';
                    }
                } elseif (!$deposit->is_own_booking && $deposit->is_own_payment) {
                    $deposit->payment_info = 'Pembayaran Anda untuk booking orang lain';
                } elseif ($deposit->is_own_booking && $deposit->is_own_payment) {
                    $deposit->payment_info = 'Booking Anda';
                } else {
                    $deposit->payment_info = '';
                }
                
                return $deposit;
            });
        
        return $deposits;
    }

    /**
     * Calculate user balance based on complex business rules
     */
    private function calculateUserBalance($userId)
    {
        return \App\Services\DepositService::getDepositBalance($userId);
    }

    /**
     * Get deposit history (AJAX)
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();
        $deposits = $this->getUserDeposits($user->id);
        $balanceData = $this->calculateUserBalance($user->id);
        
        return response()->json([
            'success' => true,
            'deposits' => $deposits,
            'totalBalance' => $balanceData['total_balance'],
            'withdrawableAmount' => $balanceData['withdrawable_amount'],
        ]);
    }

    /**
     * Show withdraw form
     */
    public function withdrawIndex()
    {
        $user = Auth::user();
        
        // Ambil balance data
        $balanceData = $this->calculateUserBalance($user->id);
        
        // Ambil semua nominal deposit yang tersedia dari booking user
        $userBookingIds = Booking::where('user_id', $user->id)->pluck('id')->toArray();
        
        $deposits = Deposit::whereIn('booking_id', $userBookingIds)
            ->whereIn('status', ['pending', 'completed'])
            ->where('amount', '>', 0)
            ->orderBy('amount')
            ->get()
            ->pluck('amount')
            ->unique()
            ->values();
        
        // Check if user has pending or approved withdrawal
        $hasPendingOrApproved = WithdrawalRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        return view('buyer.deposit.withdraw', [
            'withdrawableAmount' => $balanceData['withdrawable_amount'],
            'hasPendingOrApproved' => $hasPendingOrApproved,
            'depositNominals' => $deposits,
            'user' => $user,
        ]);
    }

    /**
     * Store withdrawal request (send OTP first)
     */
    public function withdrawStore(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has pending or approved withdrawal
        $hasPendingOrApproved = WithdrawalRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        if ($hasPendingOrApproved) {
            return redirect()->back()->with('error', 'Anda masih memiliki permintaan penarikan yang sedang diproses. Harap tunggu hingga selesai.');
        }
        
        // Validate input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:5000|max:100000000',
        ], [
            'amount.required' => 'Jumlah penarikan harus diisi',
            'amount.min' => 'Jumlah penarikan minimal Rp 5.000',
            'amount.max' => 'Jumlah penarikan maksimal Rp 100.000.000',
        ]);
        
        // Check withdrawable balance
        $balanceData = $this->calculateUserBalance($user->id);
        
        if ($validated['amount'] > $balanceData['withdrawable_amount']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Saldo yang dapat ditarik tidak mencukupi. Saldo tersedia: Rp ' . number_format($balanceData['withdrawable_amount'], 0, ',', '.'));
        }
        
        // Check bank data
        if (!$user->bank_name || !$user->account_number || !$user->account_holder_name) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data bank belum lengkap. Silakan lengkapi data bank terlebih dahulu.');
        }
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Hapus OTP lama untuk user ini
        Otp::where('user_id', $user->id)->delete();
        
        // Buat OTP baru
        Otp::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);
        
        // Simpan data withdraw ke session
        session()->put('pending_withdraw', [
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'bank_name' => $user->bank_name,
            'account_number' => $user->account_number,
            'account_holder_name' => $user->account_holder_name,
        ]);
        
        // Kirim OTP ke WhatsApp
        $pesan = "Halo {$user->name},\n\nKode OTP untuk verifikasi penarikan saldo sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . " adalah: *{$otp}*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        kirimWa($user->phone, $pesan);
        
        return redirect()->route('buyer.deposit.withdraw.otp')->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Show OTP verification form
     */
    public function withdrawOtp()
    {
        if (!session()->has('pending_withdraw')) {
            return redirect()->route('buyer.deposit.withdraw.saldo')->with('error', 'Session habis, silakan isi form withdraw kembali.');
        }
        
        return view('buyer.deposit.withdraw-otp', [
            'title' => 'Verifikasi OTP Withdraw'
        ]);
    }

    /**
     * Verify OTP and create withdrawal request
     */
    public function withdrawVerifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);
        
        $data = session('pending_withdraw');
        if (!$data) {
            return redirect()->route('buyer.deposit.withdraw.saldo')->with('error', 'Session habis, silakan isi form withdraw kembali.');
        }
        
        $user = Auth::user();
        
        // Cari OTP yang valid
        $otpRecord = Otp::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('expired_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah atau sudah kedaluwarsa.');
        }
        
        // OTP valid, hapus semua OTP untuk user ini
        Otp::where('user_id', $user->id)->delete();
        
        // Create withdrawal request
        try {
            DB::beginTransaction();
            
            WithdrawalRequest::create([
                'user_id' => $data['user_id'],
                'amount' => $data['amount'],
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder_name' => $data['account_holder_name'],
                'status' => 'pending',
            ]);
            
            DB::commit();
            
            session()->forget('pending_withdraw');
            
            return redirect()->route('buyer.deposit.withdraw.riwayat')
                ->with('success', 'Permintaan penarikan berhasil dibuat. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat permintaan penarikan. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP
     */
    public function withdrawResendOtp()
    {
        $data = session('pending_withdraw');
        if (!$data) {
            return redirect()->route('buyer.deposit.withdraw.saldo')->with('error', 'Session habis, silakan isi form withdraw kembali.');
        }
        
        $user = Auth::user();
        
        // Generate OTP baru
        $otp = rand(100000, 999999);
        
        // Hapus OTP lama
        Otp::where('user_id', $user->id)->delete();
        
        // Buat OTP baru
        Otp::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);
        
        // Kirim OTP ke WhatsApp
        $pesan = "Halo {$user->name},\n\nKode OTP untuk verifikasi penarikan saldo sebesar Rp " . number_format($data['amount'], 0, ',', '.') . " adalah: *{$otp}*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        kirimWa($user->phone, $pesan);
        
        return back()->with('success', 'Kode OTP baru sudah dikirim ke WhatsApp.');
    }

    /**
     * Show withdrawal history
     */
    public function withdrawHistory()
    {
        $user = Auth::user();
        
        $withdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($withdrawal) {
                $withdrawal->status_label = match($withdrawal->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'processed' => 'Diproses',
                    'rejected' => 'Ditolak',
                    default => 'Tidak Diketahui'
                };
                
                $withdrawal->status_class = match($withdrawal->status) {
                    'pending' => 'status-pending',
                    'approved' => 'status-approved',
                    'processed' => 'status-processed',
                    'rejected' => 'status-rejected',
                    default => ''
                };
                
                return $withdrawal;
            });
        
        return view('buyer.deposit.history_withdraw', [
            'withdrawals' => $withdrawals,
        ]);
    }
}