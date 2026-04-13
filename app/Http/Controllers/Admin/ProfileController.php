<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

/**
 * Class ProfileController
 * 
 * Mengelola profil pengguna dengan peran Admin.
 * Ini mencakup pembaruan informasi profil, penggantian foto profil (avatar),
 * perubahan nomor telepon dengan verifikasi OTP, dan pembaruan kata sandi.
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil Admin.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('gagal', 'Silakan login terlebih dahulu.');
        }

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Menampilkan formulir pengeditan profil.
     * 
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('gagal', 'Silakan login terlebih dahulu.');
        }

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi profil Admin (Nama dan Avatar).
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255', // SESUAI DB: 'name' bukan 'nama'
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan avatar baru ke folder 'avatars' di storage public
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Simpan path lengkap (avatars/filename.jpg) ke database
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('admin.profile.index')->with('sukses', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan formulir perubahan nomor telepon.
     * 
     * @return \Illuminate\View\View
     */
    public function phone()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('gagal', 'Silakan login terlebih dahulu.');
        }

        return view('admin.profile.phone', compact('user'));
    }

    /**
     * Mengirimkan kode OTP ke nomor telepon baru untuk proses verifikasi perubahan nomor.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPhoneOtp(Request $request)
    {
        $request->validate([
            'new_phone' => 'required|string|min:10|unique:users,phone'
        ]);

        $user = Auth::user();
        $newPhone = $request->new_phone;

        // Cek apakah nomor baru sama dengan nomor lama
        if ($newPhone === $user->phone) {
            return back()->with('gagal', 'Nomor telepon baru tidak boleh sama dengan nomor lama.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Hapus OTP lama untuk user ini
        Otp::where('user_id', $user->id)->delete();

        // Simpan OTP baru
        Otp::create([
            'user_id' => $user->id,
            'phone' => $newPhone,
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);

        // Simpan data di session untuk verifikasi nanti
        session()->put('pending_phone_change', [
            'user_id' => $user->id,
            'new_phone' => $newPhone,
        ]);

        // Kirim OTP via WhatsApp
        $pesan = "Halo {$user->name},\n\nKode OTP untuk perubahan nomor telepon Anda adalah: *{$otp}*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        // Asumsi fungsi kirimWa() sudah ada
        if (function_exists('kirimWa')) {
            kirimWa($newPhone, $pesan);
        }

        return redirect()->route('admin.profile.verify-phone-otp')->with('sukses', 'Kode OTP telah dikirim ke nomor baru Anda.');
    }

    /**
     * Menampilkan formulir verifikasi kode OTP untuk perubahan nomor telepon.
     * 
     * @return \Illuminate\View\View
     */
    public function showVerifyPhoneOtp()
    {
        if (!session()->has('pending_phone_change')) {
            return redirect()->route('admin.profile.phone')->with('gagal', 'Session habis, silakan mulai ulang proses perubahan nomor telepon.');
        }

        return view('admin.profile.verify-phone-otp');
    }

    /**
     * Memverifikasi kode OTP dan memperbarui nomor telepon pengguna di database.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $data = session('pending_phone_change');
        
        if (!$data) {
            return redirect()->route('admin.profile.phone')->with('gagal', 'Session habis, silakan mulai ulang proses perubahan nomor telepon.');
        }

        // Cari OTP yang valid
        $otpRecord = Otp::where('user_id', $data['user_id'])
            ->where('phone', $data['new_phone'])
            ->where('code', $request->otp)
            ->where('expired_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->with('gagal', 'Kode OTP salah atau sudah kedaluwarsa.');
        }

        // OTP valid, update nomor telepon user
        $user = User::find($data['user_id']);
        
        if (!$user) {
            return redirect()->route('admin.profile.phone')->with('gagal', 'User tidak ditemukan.');
        }

        // Update nomor telepon
        $user->phone = $data['new_phone'];
        $user->save();

        // Hapus OTP yang digunakan
        Otp::where('user_id', $user->id)->delete();

        // Hapus session
        session()->forget('pending_phone_change');

        return redirect()->route('admin.profile.index')->with('sukses', 'Nomor telepon berhasil diperbarui.');
    }

    /**
     * Mengirim ulang kode OTP jika pengguna belum menerimanya di nomor baru.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendPhoneOtp()
    {
        $data = session('pending_phone_change');
        
        if (!$data) {
            return redirect()->route('admin.profile.phone')->with('gagal', 'Session habis, silakan mulai ulang proses perubahan nomor telepon.');
        }

        $user = User::find($data['user_id']);
        
        if (!$user) {
            return redirect()->route('admin.profile.phone')->with('gagal', 'User tidak ditemukan.');
        }

        // Generate OTP baru
        $otp = rand(100000, 999999);

        // Update OTP
        Otp::where('user_id', $user->id)->where('phone', $data['new_phone'])->delete();
        
        Otp::create([
            'user_id' => $user->id,
            'phone' => $data['new_phone'],
            'code' => $otp,
            'expired_at' => Carbon::now()->addMinutes(1),
        ]);

        // Kirim OTP via WhatsApp
        $pesan = "Halo {$user->name},\n\nKode OTP untuk perubahan nomor telepon Anda adalah: *{$otp}*\n\nKode ini berlaku selama 1 menit.\n\nJangan bagikan kode ini kepada siapapun.\n\nTim SewaLapangan";
        if (function_exists('kirimWa')) {
            kirimWa($data['new_phone'], $pesan);
        }

        return back()->with('sukses', 'Kode OTP baru telah dikirim.');
    }

    /**
     * Memperbarui kata sandi pengguna setelah memverifikasi kata sandi lama.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('gagal', 'Password saat ini salah.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.profile.index')->with('sukses', 'Password berhasil diubah.');
    }
}