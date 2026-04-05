<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\DepositService;

class ProfileController extends Controller
{
    /**
     * Display the profile page for pemilik.
     */
    public function index()
    {
        // Logika Pengambilan Data: Mengambil data user yang sedang login beserta saldo dompetnya
        $user = Auth::user();
        
        Log::info('Pemilik Profile accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role
        ]);
        
        $balance = DepositService::getBalance($user->id);
        // Mengembalikan view dengan menyertakan data profil dan saldo terkini
        return view('landowner.profile.index', [
            'user' => $user,
            'balance' => $balance,
            'title' => 'Profil Pemilik'
        ]);
    }

    /**
     * Update the pemilik profile.
     */
    // ProfileController.php
    public function edit()
    {
        $user = auth()->user();

        // Logika Status Verifikasi: Mengecek apakah user memiliki permintaan perubahan nomor telepon yang belum diverifikasi
        $pendingPhone = DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->exists();

        return view('landowner.profile.edit', compact('user', 'pendingPhone'));
    }

    // ProfileController.php
    public function update(Request $request)
    {
        $user = auth()->user();

        // =====================
        // 1. VALIDASI
        // =====================
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255', // TAMBAHKAN
            'account_number' => 'nullable|string|max:255', // TAMBAHKAN
            'account_holder_name' => 'nullable|string|max:255', // TAMBAHKAN
            'gender' => 'nullable|string|in:male,female,other|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // =====================
        // 2. LOGIKA MANAJEMEN AVATAR
        // =====================
        if ($request->hasFile('avatar')) {
            // Menyimpan file gambar baru ke storage publik dan menghapus avatar lama jika ada (physical deletion)
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
            
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        // =====================
        // 3. PASSWORD
        // =====================
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Kata sandi saat ini salah'
                ]);
            }

            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // =====================
        // 4. PHONE → JANGAN LANGSUNG UPDATE
        // =====================
        $phoneChanged = $validated['phone'] !== $user->phone;
        $newPhone = $validated['phone'];

        // hapus phone dari data update
        unset($validated['phone']);

        // =====================
        // 5. UPDATE DATA AMAN
        // =====================
        $user->update($validated);

        // =====================
        // 6. LOGIKA VERIFIKASI WHATSAPP
        // =====================
        if ($phoneChanged) {
            // Jika ada perubahan nomor hp, buat token verifikasi unik dan kirimkan tautan konfirmasi via WhatsApp
            $token = Str::random(64);

            DB::table('phone_verify_tokens')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'phone_new' => $newPhone,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );

            $link = route('landowner.phone.verify', [
                'token' => $token,
                'user' => $user->id
            ]);

            $pesan =
                "Halo {$user->name},\n\n".
                "Kami mendeteksi perubahan nomor WhatsApp.\n".
                "Klik link berikut untuk verifikasi nomor baru Anda:\n\n".
                "{$link}\n\n".
                "Jika ini bukan Anda, abaikan pesan ini.";

            kirimWa($newPhone, $pesan);
        }

        return redirect()
            ->route('landowner.profile.edit')
            ->with(
                'success',
                $phoneChanged
                    ? 'Profil diperbarui. Silakan verifikasi nomor baru via WhatsApp.'
                    : 'Profil berhasil diperbarui.'
            );
    }

    /**
     * Get pemilik statistics.
     */
    public function getStats()
    {
        $user = Auth::user();
        
        Log::info('Getting pemilik stats', [
            'user_id' => $user->id
        ]);

        // Example statistics - bisa disesuaikan dengan database Anda
        // Ini hanya contoh data dummy
        $stats = [
            'total_venues' => 3,
            'active_bookings' => 24,
            'monthly_revenue' => 5200000,
            'average_rating' => 4.7,
            'total_customers' => 89,
            'pending_bookings' => 5,
            'confirmed_bookings' => 19,
            'cancelled_bookings' => 2,
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Update profile background image.
     */
    public function updateBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = auth()->user();

        try {
            // Delete old background if exists
            if ($user->landowner_background) {
                Storage::disk('public')->delete($user->landowner_background);
            }

            // Store new background in specific folder
            $path = $request->file('background')->store('landowner-backgrounds', 'public');
            $user->update(['landowner_background' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Background berhasil diperbarui!',
                'background_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload background: ' . $e->getMessage()
            ], 500);
        }
    }
}