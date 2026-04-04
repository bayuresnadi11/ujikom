<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function submitLogin(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard.index')->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
            } elseif ($user->role === 'landowner') {
                return redirect()->route('landowner.home')->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
            } elseif ($user->role === 'buyer') {
                return redirect()->route('buyer.home')->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
            } elseif ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard.index')->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Nomor telepon atau password salah.');
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Register'
        ]);
    }

    public function submitRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $dataToStore = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => 'buyer',
        ];

        session()->put('pending_register', $dataToStore);

        $this->kirimOtp($validated['phone'], $validated['name']);

        return redirect()->route('otp.form')->with('success', 'Kode OTP dikirim ke WhatsApp.');
    }

    private function kirimOtp($phone, $name)
    {
        $otp = rand(100000, 999999);

        // Hapus OTP lama untuk phone ini
        Otp::where('phone', $phone)->delete();

        // Buat OTP baru
        Otp::create([
            'user_id' => 0,
            'phone' => $phone,
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);

        $pesan = "Halo $name,\n\nKode OTP untuk verifikasi registrasi Anda adalah: *$otp*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        kirimWa($phone, $pesan);
    }

    public function formOtp()
    {
        if (!session()->has('pending_register')) {
            return redirect()->route('register')->with('error', 'Session habis, silakan daftar ulang.');
        }

        return view('auth.otp', [
            'title' => 'Verifikasi OTP'
        ]);
    }

    public function submitOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $data = session('pending_register');
        if (!$data) {
            return redirect()->route('register')->with('error', 'Session habis, silakan daftar ulang.');
        }

        // Cari OTP yang valid
        $otpRecord = Otp::where('phone', $data['phone'])
            ->where('code', $request->otp)
            ->where('expired_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah atau sudah kedaluwarsa.');
        }

        // OTP valid, hapus semua OTP untuk phone ini
        Otp::where('phone', $data['phone'])->delete();

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'buyer',
        ]);

        session()->forget('pending_register');
        Auth::login($user);

        return redirect()->route('buyer.home')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . ' 🎉');
    }

    public function resendOtp()
    {
        $data = session('pending_register');
        if (!$data) {
            return redirect()->route('register')->with('gagal', 'Session habis, silakan daftar ulang.');
        }

        $this->kirimOtp($data['phone'], $data['name']);
        return back()->with('success', 'Kode OTP baru sudah dikirim ke WhatsApp.');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout. Sampai jumpa! 👋');
    }
}