<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class PhoneChangeController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = auth()->user();
        // Logika Normalisasi: Mengubah format awal 0 menjadi 62 (Kode Negara Indonesia)
        $phoneNew = preg_replace('/^0/', '62', $request->phone);

        if ($phoneNew === $user->phone) {
            return back()->with('error', 'Nomor tidak berubah.');
        }

        // Logika Sekuritas: Membersihkan token lama sebelum membuat permintaan verifikasi baru
        DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->delete();

        // Membuat token acak baru untuk link verifikasi
        $token = Str::random(64);

        DB::table('phone_verify_tokens')->insert([
            'user_id'    => $user->id,
            'phone_new'  => $phoneNew,
            'token'      => $token,
            'created_at' => now(),
        ]);

        $link = route('landowner.phone.verify', [
            'token' => $token,
            'user'  => $user->id,
        ]);

        $pesan =
            "Halo {$user->name},\n\n" .
            "Kami menerima permintaan perubahan nomor WhatsApp.\n\n" .
            "Klik link berikut untuk verifikasi nomor baru Anda:\n\n" .
            "{$link}\n\n" .
            "Jika ini bukan Anda, abaikan pesan ini.\n\n" .
            "Tim SewaLapangan";

        // Mengirim notifikasi WA ke nomor baru untuk proses konfirmasi kepemilikan
        kirimWa($phoneNew, $pesan);

        return back()->with('success', 'Link verifikasi dikirim ke nomor baru.');
    }

    public function resend()
    {
        $user = auth()->user();

        $data = DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->first();

        if (!$data) {
            return back()->with('error', 'Tidak ada verifikasi tertunda.');
        }

        // ❗ HAPUS TOKEN LAMA
        DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->delete();

        // ❗ BUAT TOKEN BARU
        $token = Str::random(64);

        DB::table('phone_verify_tokens')->insert([
            'user_id'    => $user->id,
            'phone_new'  => $data->phone_new,
            'token'      => $token,
            'created_at' => now(),
        ]);

        $link = route('landowner.phone.verify', [
            'token' => $token,
            'user'  => $user->id,
        ]);

        $pesan =
            "Halo {$user->name},\n\n" .
            "Ini adalah link verifikasi terbaru nomor WhatsApp Anda:\n\n" .
            "{$link}\n\n" .
            "Link sebelumnya sudah tidak berlaku.\n\n" .
            "Tim SewaLapangan";

        kirimWa($data->phone_new, $pesan);

        return back()->with('success', 'Link verifikasi terbaru berhasil dikirim.');
    }

    public function verify(Request $request)
    {
        $data = DB::table('phone_verify_tokens')
            ->where('token', $request->token)
            ->where('user_id', $request->user)
            ->first();

        if (!$data) {
            return redirect()->route('landowner.profile.phone-invalid')
                ->with('error', 'Link tidak valid atau kedaluwarsa.');
        }

        $user = User::findOrFail($request->user);
        $user->phone = $data->phone_new;
        $user->save();

        DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->delete();

        return redirect()->route('landowner.profile.phone-verified')
            ->with('success', 'Nomor berhasil diverifikasi.');
    }

    public function cancel()
    {
        $user = auth()->user();

        DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'Perubahan nomor dibatalkan.');
    }
}