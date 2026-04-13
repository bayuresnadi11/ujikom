<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\RoleRequest;
use App\Services\DepositService;


class ProfileController extends Controller
{
    /**
     * Display the profile page for penyewa.
     */
public function profile()
{
    $user = Auth::user(); // bisa null (guest)

    $pendingRequest = false;
    $balance = 0;

    if ($user) {
        $pendingRequest = RoleRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        // 🔥 AMBIL SALDO DARI SERVICE (SUMBER KEBENARAN)
        $balanceData = DepositService::getDepositBalance($user->id);
        $balance = $balanceData['total_balance'];
    }

    return view('buyer.profile.index', [
        'user' => $user,
        'title' => 'Profile',
        'pendingRequest' => $pendingRequest,
        'balance' => $balance,
    ]);
}


    /**
     * Update the penyewa profile.
     */
    public function edit()
    {
        $user = auth()->user();

        $pendingPhone = DB::table('phone_verify_tokens')
            ->where('user_id', $user->id)
            ->exists();

        return view('buyer.profile.edit', compact('user', 'pendingPhone'));
    }

    // ProfileController.php
    public function update(Request $request)
    {
        $user = auth()->user();

        // =====================
        // 1. VALIDASI - TAMBAHKAN FIELD BANK DI SINI
        // =====================
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id, // ✅ TAMBAH INI
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'nullable|string|in:male,female,other|max:255',
            // TAMBAHKAN INI: Field bank information
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // =====================
        // 2. AVATAR
        // =====================
        if ($request->hasFile('avatar')) {
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
        // 5. UPDATE DATA AMAN - INI AKAN UPDATE SEMUA FIELD TERMASUK BANK
        // =====================
        $user->update($validated);

        // =====================
        // 6. KIRIM VERIFIKASI WA
        // =====================
        if ($phoneChanged) {
            $token = Str::random(64);

            DB::table('phone_verify_tokens')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'phone_new' => $newPhone,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );

            $link = route('buyer.phone.verify', [
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
            ->route('buyer.profile.edit')
            ->with(
                'success',
                $phoneChanged
                    ? 'Profil diperbarui. Silakan verifikasi nomor baru via WhatsApp.'
                    : 'Profil berhasil diperbarui.'
            );
    }

    /**
     * Change password for penyewa.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        Log::info('Changing password for penyewa', [
            'user_id' => $user->id
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama tidak sesuai'
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diubah!'
        ]);
    }

    /**
     * Get penyewa statistics.
     */
    public function getStats()
    {
        $user = Auth::user();

        Log::info('Getting penyewa stats', [
            'user_id' => $user->id
        ]);

        // Example statistics - bisa disesuaikan dengan database Anda
        // Ini hanya contoh data dummy
        $stats = [
            'total_bookings' => 12,
            'active_bookings' => 3,
            'completed_bookings' => 9,
            'total_spent' => 2450000,
            'favorite_sport' => 'Futsal',
            'membership_level' => 'Silver',
            'loyalty_points' => 450,
            'average_rating' => 4.8,
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function pengajuan()
    {
        $user = auth()->user();

        $approveRequest = $user->roleRequests()->where('status', 'approved')->first();

        if ($approveRequest) {
            $user->update([
                'role' => $approveRequest->requested_role,
                'can_switch_to_landowner' => true
            ]);

            $approveRequest->delete();

            return redirect()->route('landowner.home');
        }
            


        return view('buyer.profile.pengajuan');
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
            if ($user->background) {
                Storage::disk('public')->delete($user->background);
            }

            // Store new background
            $path = $request->file('background')->store('backgrounds', 'public');
            $user->update(['background' => $path]);

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