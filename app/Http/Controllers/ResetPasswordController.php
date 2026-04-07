<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Class ResetPasswordController
 * 
 * Mengelola proses pemulihan kata sandi (reset password) melalui WhatsApp.
 * Ini mencakup pengiriman link reset dan pembaruan kata sandi di database.
 */
class ResetPasswordController extends Controller
{
    /**
     * Menampilkan formulir permintaan lupa kata sandi.
     * 
     * @return \Illuminate\View\View
     */
    public function showForgetForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    /**
     * Membuat token reset password dan mengirimkan link pemulihan ke WhatsApp pengguna.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        // Validasi input nomor HP
        $request->validate(['phone' => 'required|string']);

        // Cari user berdasarkan nomor HP
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        // Generate token random untuk reset password
        $token = Str::random(64);

        // Simpan atau update token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => $token, 'created_at' => now()]
        );

        // Membuat link reset password
        $resetLink = url('/reset-password?token=' . $token . '&phone=' . $request->phone);

        try {
            // Isi pesan WhatsApp
            $pesan = "Halo {$user->name},\n\nAnda meminta reset password. Klik link berikut untuk mereset password Anda:\n\n{$resetLink}\n\nJika Anda tidak meminta reset password, abaikan pesan ini.\n\nTim SewaLapangan";
            
            // Kirim pesan WhatsApp (function custom)
            kirimWa($request->phone, $pesan);
        } catch (\Exception $e) {
            // Jika gagal kirim WA
            return back()->with('error', 'Gagal mengirim link reset via WhatsApp.');
        }

        // Jika berhasil
        return back()->with('success', 'Link reset password telah dikirim via WhatsApp.');
    }

    /**
     * Menampilkan formulir untuk mengatur ulang kata sandi baru.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,   // Ambil token dari URL
            'phone' => $request->phone,   // Ambil nomor HP dari URL
            'title' => 'Reset Password'
        ]);
    }

    /**
     * Memproses pengaturan ulang kata sandi pengguna setelah verifikasi token.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'phone' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed', // harus ada password_confirmation
        ]);

        // Cek token di database
        $reset = DB::table('password_reset_tokens')
            ->where('phone', $request->phone)
            ->where('token', $request->token)
            ->first();

        // Jika token tidak valid
        if (!$reset) {
            return back()->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        // Cari user berdasarkan nomor HP
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        // Update password user (di-hash biar aman)
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('phone', $request->phone)->delete();

        // Redirect ke login
        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
    }
}