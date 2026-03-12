<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showForgetForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = url('/reset-password?token=' . $token . '&phone=' . $request->phone);

        try {
            $pesan = "Halo {$user->name},\n\nAnda meminta reset password. Klik link berikut untuk mereset password Anda:\n\n{$resetLink}\n\nJika Anda tidak meminta reset password, abaikan pesan ini.\n\nTim SewaLapangan";
            kirimWa($request->phone, $pesan);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim link reset via WhatsApp.');
        }

        return back()->with('success', 'Link reset password telah dikirim via WhatsApp.');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'phone' => $request->phone,
            'title' => 'Reset Password'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('phone', $request->phone)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('phone', $request->phone)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
    }
}