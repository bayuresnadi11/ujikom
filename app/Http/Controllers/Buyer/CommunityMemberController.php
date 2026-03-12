<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommunityMemberController extends Controller
{
    /**
     * ✅ UPDATED: Join community dengan auto-create conversation
     */
    public function join(Request $request, $communityId)
    {
        try {
            $community = Community::findOrFail($communityId);
            $userId = Auth::id();

            // Cek apakah sudah member
            if ($community->isMember($userId)) {
                return back()->with('info', 'Anda sudah menjadi anggota komunitas ini');
            }

            DB::beginTransaction();

            // Create member record
            CommunityMember::create([
                'community_id' => $community->id,
                'user_id' => $userId,
                'role' => 'anggota',
                'status' => 'active',
                'joined_at' => now()
            ]);

            // ✅ AUTO-CREATE conversation untuk community ini (jika belum ada)
            $conversation = Conversation::firstOrCreate(
                [
                    'community_id' => $community->id,
                    'type' => 'community'
                ],
                [
                    'last_message_at' => now()
                ]
            );

            DB::commit();

            // ✅ Chat grup akan OTOMATIS MUNCUL di chat list
            // karena query whereHas('community.members') akan menemukan user ini

            return back()->with('success', 'Berhasil bergabung dengan komunitas');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error joining community: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat bergabung');
        }
    }

    /**
     * ✅ UPDATED: Leave community
     */
    public function leave(Request $request, $communityId)
    {
        try {
            $community = Community::findOrFail($communityId);
            $userId = Auth::id();

            // Cek apakah user adalah member
            $member = CommunityMember::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$member) {
                return back()->with('error', 'Anda bukan anggota komunitas ini');
            }

            DB::beginTransaction();

            // ✅ Update status jadi inactive/removed
            $member->update(['status' => 'removed']);

            // Hapus juga request jika ada
            \App\Models\CommunityRequest::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->delete();

            DB::commit();

            // ✅ Chat grup akan OTOMATIS HILANG dari chat list
            // karena query whereHas('community.members') tidak akan menemukan user ini
            // (relasi members() sudah filter wherePivot('status', 'active'))

            // Clear cache
            cache()->forget("user.{$userId}.unread_messages");

            return redirect()->route('buyer.communities.index')
                ->with('success', 'Anda telah keluar dari komunitas');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error leaving community: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat keluar dari komunitas');
        }
    }

    /**
     * ✅ UPDATED: Remove member (by admin)
     */
    public function removeMember(Request $request, $community, $memberId)
    {
        try {
            Log::info('removeMember called', [
                'community' => $community,
                'memberId' => $memberId,
                'userId' => Auth::id()
            ]);

            // Cek apakah user yang request adalah admin
            $currentUser = CommunityMember::where('community_id', $community)
                ->where('user_id', Auth::id())
                ->where('role', 'admin')
                ->where('status', 'active')
                ->first();
            
            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat mengeluarkan anggota'
                ], 403);
            }

            $member = CommunityMember::where('community_id', $community)
                ->where('id', $memberId)
                ->firstOrFail();

            // Tidak boleh mengeluarkan diri sendiri
            if ($member->user_id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengeluarkan diri sendiri'
                ], 400);
            }

            DB::beginTransaction();

            // Simpan user_id sebelum update
            $removedUserId = $member->user_id;

            // ✅ Update status member jadi removed
            $member->update(['status' => 'removed']);

            // Hapus juga data request agar status di UI reset
            \App\Models\CommunityRequest::where('community_id', $community)
                ->where('user_id', $removedUserId)
                ->delete();

            DB::commit();

            // ✅ Chat grup akan OTOMATIS HILANG dari chat list member yang dikeluarkan
            // Clear cache untuk user yang dikeluarkan
            cache()->forget("user.{$removedUserId}.unread_messages");

            Log::info('removeMember success', [
                'memberId' => $memberId,
                'memberUserId' => $member->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dikeluarkan dari komunitas'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in removeMember: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aksi jadikan manager
     */
    public function makeAdmin(Request $request, $community, $memberId)
    {
        try {
            Log::info('makeAdmin called', [
                'community' => $community,
                'memberId' => $memberId,
                'userId' => Auth::id()
            ]);

            // Cek apakah user yang request adalah admin komunitas ini
            $currentUser = CommunityMember::where('community_id', $community)
                ->where('user_id', Auth::id())
                ->where('role', 'admin')
                ->first();
            
            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat menjadikan member sebagai admin'
                ], 403);
            }

            $member = CommunityMember::where('community_id', $community)
                ->where('id', $memberId)
                ->firstOrFail();

            // Cek apakah member yang akan dijadikan admin adalah diri sendiri
            if ($member->user_id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah role diri sendiri'
                ], 400);
            }

            // Update role menjadi admin - TIDAK gunakan updated_at karena kolom tidak ada
            $member->role = 'admin';
            $member->save();

            Log::info('makeAdmin success', [
                'memberId' => $memberId,
                'newRole' => $member->role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dijadikan admin',
                'role' => $member->role
            ]);

        } catch (\Exception $e) {
            Log::error('Error in makeAdmin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aksi ubah admin menjadi anggota
     */
    public function removeAdmin(Request $request, $community, $memberId)
    {
        try {
            Log::info('removeAdmin called', [
                'community' => $community,
                'memberId' => $memberId,
                'userId' => Auth::id()
            ]);

            // Cek apakah user yang request adalah admin
            $currentUser = CommunityMember::where('community_id', $community)
                ->where('user_id', Auth::id())
                ->where('role', 'admin')
                ->first();
            
            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat mengubah admin menjadi anggota'
                ], 403);
            }

            $member = CommunityMember::where('community_id', $community)
                ->where('id', $memberId)
                ->firstOrFail();

            // Tidak boleh mengubah diri sendiri
            if ($member->user_id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah role diri sendiri'
                ], 400);
            }

            // Update role menjadi anggota - TIDAK gunakan updated_at karena kolom tidak ada
            $member->role = 'anggota';
            $member->save();

            Log::info('removeAdmin success', [
                'memberId' => $memberId,
                'newRole' => $member->role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diubah menjadi anggota',
                'role' => $member->role
            ]);

        } catch (\Exception $e) {
            Log::error('Error in removeAdmin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function untuk mendapatkan achievements member
     */
    private function getMemberAchievements($userId)
    {
        // Data dummy achievements (bisa diganti dengan data dari database)
        $achievements = [
            'Juara 1 Tournament Lokal',
            'MVP Bulanan 3x',
            'Rookie of the Month'
        ];

        // Jika ada data achievements di database, gunakan yang asli
        $user = User::find($userId);
        if ($user && $user->achievements) {
            // Jika achievements disimpan sebagai JSON string
            if (is_string($user->achievements)) {
                $achievements = json_decode($user->achievements, true) ?? $achievements;
            } else {
                $achievements = $user->achievements;
            }
        }

        return $achievements;
    }

    /**
     * Search members
     */
    public function search(Request $request, $community)
    {
        $keyword = $request->q ?? '';
        
        $members = CommunityMember::with('user')
            ->where('community_id', $community)
            ->where('status', 'active')
            ->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->get()
            ->map(function ($member) {
                $nameParts = explode(' ', $member->user->name);
                $initials = '';
                
                if (count($nameParts) >= 2) {
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($member->user->name, 0, 2));
                }
                
                $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];
                $colorIndex = crc32($member->user->name) % count($colors);
                
                return [
                    'id' => $member->id,
                    'user_id' => $member->user_id,
                    'name' => $member->user->name,
                    'username' => $member->user->username ?? Str::slug($member->user->name),
                    'role' => $member->role,
                    'avatar_initials' => $initials,
                    'avatar_color' => $colors[$colorIndex],
                    'joined_at' => $member->joined_at ? $member->joined_at->format('d M Y') : '',
                    'avatar' => $member->user->avatar 
                        ? asset('storage/' . $member->user->avatar)
                        : null,
                ];
            });

        return response()->json($members);
    }

    public function searchUsers(Request $request, $communityId)
    {
        $q = trim($request->q);
    
        // ambil user yang sudah join komunitas
        $joinedUserIds = \DB::table('community_members')
            ->where('community_id', $communityId)
            ->pluck('user_id')
            ->toArray();
    
        $users = \App\Models\User::query()
            ->where('id', '!=', auth()->id()) // ⬅️ JANGAN munculin diri sendiri
            ->whereNotIn('id', $joinedUserIds) // ⬅️ BELUM JOIN
            ->where('name', 'like', "%{$q}%") // ⬅️ SEARCH
            ->get(['id', 'name', 'avatar']);
    
        return response()->json($users);
    }    
}