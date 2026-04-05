<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\CommunityRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CommunityInvitation;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\CommunityGallery;
use Illuminate\Support\Facades\Storage;
use App\Models\PlayTogether;
use App\Models\PlayTogetherParticipant;
use App\Models\Setting;

class CommunitiesController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Communities where user is a regular member
        $memberCommunities = Community::withCount('members')
            ->whereHas('members', function ($q) use ($userId) {
                $q->where('community_members.user_id', $userId)
                ->where('community_members.role', 'anggota')
                ->where('community_members.status', 'active');
            })
            ->with('category')
            ->get()
            ->map(function($c) {
                $c->is_manager = false;
                return $c;
            });
        
        // Communities where user is an admin/manager
        $managerCommunities = Community::withCount([
                'members',
                'pendingRequests as pending_count'
            ])
            ->whereHas('members', function($q) use ($userId) {
                $q->where('community_members.user_id', $userId)
                ->where('community_members.role', 'admin')
                ->where('community_members.status', 'active');
            })
            ->with('category')
            ->get()
            ->map(function($c) {
                $c->is_manager = true;
                return $c;
            });        

        // Merge and sort by name
        $allCommunities = $memberCommunities->merge($managerCommunities)->sortBy('name');

        $invitedCommunities = CommunityInvitation::with('community.category')
            ->where('invited_user_id', $userId)
            ->where('status', 'pending')
            ->get();        

        return view('buyer.communities.index', compact(
            'allCommunities',
            'invitedCommunities'
        ));
    }

    public function join()
    {
        $userId = auth()->id(); // Bisa null untuk guest

        $communities = Community::withCount('members')
            ->with([
                'memberRecords' => fn($q) => $q->where('user_id', $userId),
                'communityRequests' => fn($q) => $q->where('user_id', $userId),
            ])
            ->get()
            ->map(function ($community) use ($userId) {
                if ($userId) {
                    // USER LOGIN
                    $member = $community->memberRecords->first();
                    $community->role = $member ? $member->role : null;
                    
                    if ($member) {
                        if ($member->status === 'removed') {
                            $community->membership_status = 'removed';
                        } else {
                            $community->membership_status = 'approved';
                        }
                    } else {
                        $request = $community->communityRequests->first();
                        $community->membership_status = $request->status ?? 'none';
                    }
                } else {
                    // GUEST
                    $community->role = null;
                    $community->membership_status = 'none';
                }
                return $community;
            });            

        return view('buyer.communities.join', compact('communities'));
    }

    public function storeJoin(Community $community)
    {
        $userId = auth()->id();

        // 1. PUBLIC + NO APPROVAL REQUIRED => Auto Join
        if ($community->type === 'public' && !$community->requires_approval) {

            CommunityMember::updateOrCreate(
                [
                    'community_id' => $community->id,
                    'user_id' => $userId,
                ],
                [
                    'role' => 'anggota',
                    'joined_at' => now(),
                    'status' => 'active',
                ]
            );

            CommunityRequest::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'status' => 'approved',
                'message' => 'Berhasil bergabung ke komunitas'
            ]);
        }

        // 2. PRIVATE OR (PUBLIC + REQUIRE APPROVAL) => Request Join
        CommunityRequest::updateOrCreate(
            [
                'community_id' => $community->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'pending',
            ]
        );

        CommunityMember::where('community_id', $community->id)
            ->where('user_id', $userId)
            ->delete();

        try {
            $applicant = User::find($userId);

            $managerIds = $community->memberRecords()->where('role', 'admin')->pluck('user_id');

            if ($managerIds->isEmpty() && $community->created_by) {
                $managerIds = collect([$community->created_by]);
            }

            $managers = User::whereIn('id', $managerIds)->get();

            foreach ($managers as $manager) {
                $manager->notify(new \App\Notifications\CommunityMemberStatusNotification(
                    $community,
                    $applicant,
                    $applicant,
                    'pending'
                ));
            }
        } catch (\Exception $e) {
            Log::error('Notify storeJoin error: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'status' => 'pending',
            'message' => 'Permintaan join dikirim'
        ]);
    }

    public function requestRejoin(Community $community)
    {
        $userId = auth()->id();

        // Cek apakah user ada di tabel member dengan status removed
        $removedMember = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $userId)
            ->where('status', 'removed')
            ->first();

        if (!$removedMember) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dalam status dikeluarkan dari komunitas ini.'
            ], 403);
        }

        // Hapus record member yang status removed
        $removedMember->delete();

        // Buat request baru
        CommunityRequest::updateOrCreate(
            [
                'community_id' => $community->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Notifikasi ke manager/admin
        try {
            $applicant = User::find($userId);
            $managerIds = $community->memberRecords()
                ->where('role', 'admin')
                ->where('status', 'active')
                ->pluck('user_id');

            $managers = User::whereIn('id', $managerIds)->get();
             foreach ($managers as $manager) {
                 $manager->notify(new \App\Notifications\CommunityMemberStatusNotification(
                     $community,
                     $applicant,
                     $applicant, 
                     'pending' 
                 ));
             }
        } catch (\Exception $e) {
            Log::error('Notify requestRejoin error: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'status' => 'pending', // Add status pending for frontend handler
            'message' => 'Permintaan bergabung kembali berhasil dikirim.'
        ]);
    }



    public function search(Request $request)
    {
        $keyword = $request->q;
        $userId = auth()->id();

        $communities = Community::withCount('members')
            ->with([
                'memberRecords' => fn($q) => $q->where('user_id', $userId),
                'communityRequests' => fn($q) => $q->where('user_id', $userId),
            ])
            ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->get()
            ->map(function ($community) use ($userId) {
                $member = $community->memberRecords->first();
                $community->role = $member ? $member->role : null;
                
                if ($member) {
                    if ($member->status === 'removed') {
                        $community->membership_status = 'removed';
                    } else {
                        $community->membership_status = 'approved';
                    }
                } else {
                    $request = $community->communityRequests->first();
                    $community->membership_status = $request->status ?? 'none';
                }
            
                return $community;
            });

        if ($request->ajax()) {
            return view('buyer.communities.join', compact('communities'))->render();
        }

        return view('buyer.communities.join', compact('communities'));
    }

    public function searchJoined(Request $request)
    {
        $keyword = $request->q;
        $userId = auth()->id();

        $communities = Community::whereHas('members', function ($q) use ($userId) {
                $q->where('community_members.user_id', $userId)
                ->where('community_members.status', 'active');
            })
            ->where('name', 'like', "%{$keyword}%")
            ->withCount([
                'members' => fn($q) => $q->where('community_members.status', 'active'),
                'pendingRequests as pending_count'
            ])
            ->with(['category', 'members' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get()
            ->map(function($c) {
                $member = $c->members->first();
                $c->is_manager = $member && $member->pivot->role === 'admin';
                return $c;
            });

        return response()->json([
            'communities' => $communities
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('buyer.communities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'type' => 'required|in:public,private',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
        ]);

        $logoPath = $request->file('logo') 
            ? $request->file('logo')->store('community-logos', 'public') 
            : null;

        $community = Community::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logoPath ?? 'default.png',
            'type' => $request->type,
            'requires_approval' => $request->has('requires_approval') ? true : false,
            'location' => $request->location,
            'created_by' => auth()->id(),
            'category_id' => $request->category_id, 
        ]);

        \App\Models\CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => auth()->id(),
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        // Create Community Conversation
        \App\Models\Conversation::findOrCreateCommunityConversation($community->id);

        return redirect()->route('buyer.communities.index')
            ->with('success', 'Komunitas berhasil dibuat!');
    }

    public function show(Community $community)
    {
        // ✅ FIX: Query langsung dari CommunityMember, bukan lewat relasi members
        $members = CommunityMember::where('community_id', $community->id)
            ->where('status', 'active')
            ->with('user')
            ->get();

        // 🔥 INI NIH TEMPATNYA
        $memberIds = CommunityMember::where('community_id', $community->id)
            ->where('status', 'active')
            ->pluck('user_id');

        $activities = \App\Models\PlayTogether::with([
                'booking.schedule', 
                'booking.venue', 
                'creator',
                'participants' => function ($q) {
                    $q->where('approval_status', '!=', 'rejected');
                }
            ])
            ->where(function ($q) use ($community, $memberIds) {
                $q->where('community_id', $community->id)
                    ->orWhere(function ($sq) use ($memberIds) {
                        $sq->where('privacy', 'public')
                            ->whereIn('created_by', $memberIds);
                    });
            })
            ->whereIn('status', ['pending', 'active'])
            ->whereDate('date', '>=', now()->toDateString())
            ->whereHas('booking.schedule', function ($q) {
                $q->whereDate('date', '>=', now()->toDateString());
            })
            ->orderBy('date', 'asc')
            ->get();

        $inviteUsers = User::whereDoesntHave('communityMembers', function ($q) use ($community) {
            $q->where('community_id', $community->id);
        })
        ->get()
        ->map(function ($user) use ($community) {

            $isPending = CommunityInvitation::where('community_id', $community->id)
                ->where('invited_user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'is_pending' => $isPending
            ];
        });

        return view('buyer.communities.show', compact(
            'community',
            'members',
            'inviteUsers',
            'activities'
        ));
    }

    /**
     * Tampilkan halaman aktivitas komunitas
     */
    public function aktivitas(Community $community)
    {
        // Cek apakah user adalah member dari komunitas
        $currentMember = $community->members()
            ->where('user_id', auth()->id())
            ->first();

        if (!$currentMember) {
            abort(403, 'Anda bukan member dari komunitas ini');
        }

        $isMember = $community->isMember(auth()->id());
        $isManager = $community->isManager(auth()->id());
        $isRemoved = $currentMember && $currentMember->status === 'removed';

        $members = CommunityMember::where('community_id', $community->id)
            ->where('status', 'active')
            ->with('user')
            ->get();

        $memberIds = $members->pluck('user_id');

        $activities = \App\Models\PlayTogether::with([
                'booking.schedule', 
                'booking.venue', 
                'creator',
                'participants' => function ($q) {
                    $q->where('approval_status', '!=', 'rejected');
                }
            ])
            ->where(function ($q) use ($community, $memberIds) {
                $q->where('community_id', $community->id)
                    ->orWhere(function ($sq) use ($memberIds) {
                        $sq->where('privacy', 'public')
                            ->whereIn('created_by', $memberIds);
                    });
            })
            ->whereIn('status', ['pending', 'active'])
            ->whereDate('date', '>=', now()->toDateString())
            ->whereHas('booking.schedule', function ($q) {
                $q->whereDate('date', '>=', now()->toDateString());
            })
            ->orderBy('date', 'asc')
            ->get();

        return view('buyer.communities.aktivitas', compact(
            'community',
            'members',
            'activities',
            'isManager',
            'isMember',
            'isRemoved'
        ));
    }

    /**
     * Tampilkan halaman galeri komunitas
     */
    public function galeri(Community $community)
    {
        // Cek apakah user adalah member dari komunitas
        $currentMember = $community->members()
            ->where('user_id', auth()->id())
            ->first();

        if (!$currentMember) {
            abort(403, 'Anda bukan member dari komunitas ini');
        }

        $isMember = $community->isMember(auth()->id());
        $isManager = $community->isManager(auth()->id());
        $isRemoved = $currentMember && $currentMember->status === 'removed';

        $galleries = $community->galleries;

        return view('buyer.communities.galeri', compact(
            'community',
            'isManager',
            'isMember',
            'isRemoved',
            'galleries'
        ));
    }

    /**
     * Simpan gambar ke galeri komunitas
     */
    public function storeGallery(Request $request, Community $community)
    {
        abort_unless($community->isManager(auth()->id()), 403);

        $request->validate([
            'image' => 'required|image|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('community-galleries', 'public');
            
            $gallery = $community->galleries()->create([
                'image_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'image_url' => asset('storage/' . $gallery->image_path),
                'gallery_id' => $gallery->id
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Hapus gambar dari galeri komunitas
     */
    public function destroyGallery(Community $community, CommunityGallery $gallery)
    {
        abort_unless($community->isManager(auth()->id()), 403);

        if ($gallery->community_id !== $community->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Hapus file fisik
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return response()->json(['success' => true]);
    }

    public function edit(Community $community)
    {
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat mengedit komunitas'
        );

        $categories = Category::all();

        return view('buyer.communities.edit', compact('community', 'categories'));
    }

    /**
     * Tampilkan halaman undang anggota (halaman baru)
     */
    public function inviteAnggota(Community $community)
    {
        // ✅ FIX 1: Pakai helper method isManager() yang lebih clean
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat mengundang member'
        );

        // Ambil data permintaan bergabung yang pending
        $pendingRequests = $community->requests()
            ->where('status', 'pending')
            ->with('user')
            ->get();

        // ✅ FIX 2: Ambil removed members menggunakan memberRecords()
        $removedMembers = $community->memberRecords()
            ->where('status', 'removed')
            ->with(['user', 'user.communityInvitations' => function($query) use ($community) {
                $query->where('community_id', $community->id)
                    ->where('status', 'pending');
            }])
            ->get();
            
        // Tambahkan atribut has_pending_invite
        foreach ($removedMembers as $member) {
            $member->has_pending_invite = $member->user->communityInvitations->isNotEmpty();
        }

        // Generate token undangan
        $inviteToken = Str::random(32);
        
        // Simpan token ke database jika belum ada
        if (!$community->invite_token) {
            $community->update(['invite_token' => $inviteToken]);
        } else {
            $inviteToken = $community->invite_token;
        }

        return view('buyer.communities.invite-anggota', compact(
            'community', 
            'pendingRequests', 
            'removedMembers', 
            'inviteToken'
        ));
    }

    public function reinviteMember(Community $community, $memberId)
    {
        // ✅ FIX: Pakai isManager()
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat mengundang kembali member'
        );

        try {
            $member = CommunityMember::where('id', $memberId)
                ->where('community_id', $community->id)
                ->where('status', 'removed')
                ->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data member tidak ditemukan'
                ], 404);
            }

            $user = $member->user;

            // Hapus semua undangan lama
            CommunityInvitation::where('community_id', $community->id)
                ->where('invited_user_id', $user->id)
                ->delete();

            // Buat undangan baru
            $invitation = CommunityInvitation::create([
                'community_id'    => $community->id,
                'invited_user_id' => $user->id,
                'invited_by'      => auth()->id(),
                'status'          => 'pending',
                'created_at'      => now(),
                'updated_at'      => now()
            ]);

            // Kirim notifikasi
            try {
                $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                    $community,
                    auth()->user(),
                    $user,
                    'community_invitation' 
                ));
            } catch (\Exception $e) {
                Log::error('Notify reinviteMember error: '.$e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengirim ulang undangan'
            ]);

        } catch (\Exception $e) {
            Log::error('Reinvite member error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelInvite(Community $community, $memberId)
    {
        // ✅ FIX: Pakai isManager()
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat membatalkan undangan'
        );

        try {
            $member = CommunityMember::where('id', $memberId)
                ->where('community_id', $community->id)
                ->where('status', 'removed')
                ->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data member tidak ditemukan'
                ], 404);
            }

            // Hapus undangan pending
            $deleted = CommunityInvitation::where('community_id', $community->id)
                ->where('invited_user_id', $member->user_id)
                ->where('status', 'pending')
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Undangan berhasil dibatalkan'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada undangan pending untuk dibatalkan'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Cancel invite error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle request dari halaman invite-anggota
     */
    public function handleInviteRequest(Request $request, Community $community)
    {
        // ✅ FIX: Pakai isManager()
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat menangani permintaan'
        );

        $validated = $request->validate([
            'request_id' => 'required|exists:community_requests,id',
            'action' => 'required|in:accept,reject'
        ]);

        $joinRequest = CommunityRequest::findOrFail($validated['request_id']);

        if ($validated['action'] === 'accept') {
            // Terima permintaan
            CommunityMember::updateOrCreate([
                'community_id' => $joinRequest->community_id,
                'user_id' => $joinRequest->user_id,
            ], [
                'role' => 'anggota',
                'joined_at' => now(),
                'status' => 'active',
            ]);

            $joinRequest->update(['status' => 'approved']);

            // ✅ Auto-create conversation jika belum ada
            \App\Models\Conversation::firstOrCreate(
                [
                    'community_id' => $community->id,
                    'type' => 'community'
                ],
                [
                    'last_message_at' => now()
                ]
            );

            // Kirim notifikasi ke user yang mengajukan
            try {
                $user = $joinRequest->user;
                $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                    $community,
                    auth()->user(),
                    $user,
                    'accepted'
                ));
            } catch (\Exception $e) {
                Log::error('Notify handleInviteRequest accept error: '.$e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Permintaan join disetujui'
            ]);
        } else {
            // Tolak permintaan
            $joinRequest->update(['status' => 'rejected']);

            // Kirim notifikasi ke user yang mengajukan
            try {
                $user = $joinRequest->user;
                $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                    $community,
                    auth()->user(),
                    $user,
                    'rejected'
                ));
            } catch (\Exception $e) {
                Log::error('Notify handleInviteRequest reject error: '.$e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Permintaan join ditolak'
            ]);
        }
    }

    /**
     * Kirim undangan via email dari halaman invite-anggota
     */
    public function sendEmailInvitation(Request $request, Community $community)
    {
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat mengundang member'
        );

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User dengan email tersebut tidak ditemukan'
            ], 404);
        }

        // Cek apakah user sudah menjadi member
        if (CommunityMember::where('community_id', $community->id)
            ->where('user_id', $user->id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah menjadi member komunitas'
            ], 409);
        }

        // Cek apakah sudah ada undangan pending
        $existingInvitation = CommunityInvitation::where('community_id', $community->id)
            ->where('invited_user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah ada undangan pending untuk user ini'
            ], 409);
        }

        // Buat undangan baru
        CommunityInvitation::create([
            'community_id' => $community->id,
            'invited_user_id' => $user->id,
            'invited_by' => auth()->id(),
            'status' => 'pending',
            'invite_token' => Str::random(32),
        ]);

        // TODO: Kirim email notifikasi

        return response()->json([
            'success' => true,
            'message' => 'Undangan berhasil dikirim ke ' . $validated['email']
        ]);
    }

    /**
     * Generate invite link baru
     */
    public function generateInviteLink(Community $community)
    {
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat generate invite link'
        );

        $newToken = Str::random(32);
        $community->update(['invite_token' => $newToken]);

        $inviteLink = route('communities.join', ['community' => $community->id, 'token' => $newToken]);

        return response()->json([
            'success' => true,
            'invite_link' => $inviteLink,
            'message' => 'Link undangan baru berhasil dibuat'
        ]);
    }

    public function invite(Community $community)
    {
        // ✅ Cara 1: Pakai helper method isManager() (RECOMMENDED)
        abort_unless(
            $community->isManager(auth()->id()),
            403,
            'Hanya manager/admin yang dapat mengundang member'
        );

        $inviteUsers = collect();

        return view('buyer.communities.invite', compact('community', 'inviteUsers'));
    }

    public function addToDraft(Request $request, Community $community)
    {
        $userIds = $request->input('users', []);
        
        // Simpan ke session
        $sessionKey = 'invite_draft_' . $community->id;
        $currentDraft = session()->get($sessionKey, []);
        
        // Merge unique IDs
        $newDraft = array_unique(array_merge($currentDraft, $userIds));
        
        session()->put($sessionKey, $newDraft);
        
        return response()->json([
            'success' => true,
            'count' => count($newDraft)
        ]);
    }

    public function showDraft(Community $community)
    {
        $sessionKey = 'invite_draft_' . $community->id;
        $draftUserIds = session()->get($sessionKey, []);
        
        $users = User::whereIn('id', $draftUserIds)->get();
        
        return view('buyer.communities.invite-draft', compact('community', 'users'));
    }

    public function removeFromDraft(Community $community, $userId)
    {
        $sessionKey = 'invite_draft_' . $community->id;
        $draftUserIds = session()->get($sessionKey, []);
        
        // Remove user ID
        $updatedDraft = array_values(array_diff($draftUserIds, [$userId]));
        
        session()->put($sessionKey, $updatedDraft);
        
        return response()->json([
            'success' => true,
            'count' => count($updatedDraft)
        ]);
    }

    public function sendBulkInvite(Request $request, Community $community)
    {
        $sessionKey = 'invite_draft_' . $community->id;
        $draftUserIds = session()->get($sessionKey, []);
        
        $count = 0;
        foreach ($draftUserIds as $userId) {
            // Cek apakah sudah ada invite pending
            $exists = CommunityInvitation::where('community_id', $community->id)
                ->where('invited_user_id', $userId)
                ->exists();
                
            // Cek apakah sudah jadi member
            $isMember = CommunityMember::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists && !$isMember) {
                $invitation = CommunityInvitation::create([
                    'community_id' => $community->id,
                    'invited_user_id' => $userId,
                    'invited_by' => auth()->id(),
                    'status' => 'pending',
                ]);

                // Kirim notifikasi
                try {
                    $user = User::find($userId);
                    if ($user) {
                        $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                            $community,
                            auth()->user(),
                            $user,
                            'community_invitation'
                        ));
                    }
                } catch (\Exception $e) {
                    Log::error('Bulk invite notification error: ' . $e->getMessage());
                }
                
                $count++;
            }
        }
        
        // Clear session after sending
        session()->forget($sessionKey);
        
        return redirect()->route('buyer.communities.show', $community->id)
            ->with('success', $count . ' undangan berhasil dikirim!');
    }

    public function requests(Community $community)
    {
        abort_unless(
            $community->isManager(auth()->id()),
            403
        );
    
        $requests = $community->requests()
            ->where('status', 'pending')
            ->with('user')
            ->get();
    
        return view('buyer.communities.requests', compact('community', 'requests'));
    }
        
    public function approveRequest(CommunityRequest $request)
    {
        // Cek izin (manager atau admin)
        abort_unless(
            CommunityMember::where('community_id', $request->community_id)
                ->where('user_id', auth()->id())
                ->where('role', 'admin')
                ->where('status', 'active')
                ->exists(),
            403,
            'Anda tidak memiliki izin untuk menyetujui permintaan'
        );

        CommunityMember::updateOrCreate([
            'community_id' => $request->community_id,
            'user_id' => $request->user_id,
        ], [
            'role' => 'anggota',
            'joined_at' => now(),
            'status' => 'active',
        ]);
    
        $request->update(['status' => 'approved']);
    
        // Kirim notifikasi
        try {
            $user = $request->user;
            $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                $request->community,
                auth()->user(),
                $user,
                'accepted'
            ));
        } catch (\Exception $e) {
            Log::error('Notify approveRequest error: '.$e->getMessage());
        }

        return back()->with('success', 'Permintaan join disetujui');
    }    

    public function rejectRequest(CommunityRequest $request)
    {
        // Cek izin (manager atau admin)
        abort_unless(
            CommunityMember::where('community_id', $request->community_id)
                ->where('user_id', auth()->id())
                ->where('role', 'admin')
                ->exists(),
            403,
            'Anda tidak memiliki izin untuk menolak permintaan'
        );

        $request->update([
            'status' => 'rejected'
        ]);
    
        // Kirim notifikasi
        try {
            $user = $request->user;
            $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                $request->community,
                auth()->user(),
                $user,
                'rejected'
            ));
        } catch (\Exception $e) {
            Log::error('Notify rejectRequest error: '.$e->getMessage());
        }

        CommunityMember::where('community_id', $request->community_id)
            ->where('user_id', $request->user_id)
            ->delete();
    
        return back()->with('success', 'Permintaan ditolak');
    }    

    public function approveMembershipAjax(Request $request)
    {
        $data = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Cek izin (manager atau admin)
        abort_unless(
            CommunityMember::where('community_id', $data['community_id'])
                ->where('user_id', auth()->id())
                ->where('role', 'admin')
                ->exists(),
            403
        );

        $cRequest = CommunityRequest::where('community_id', $data['community_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'pending')
            ->first();

        if (!$cRequest) {
            return response()->json(['success' => false, 'message' => 'Permintaan tidak ditemukan'], 404);
        }

        CommunityMember::firstOrCreate([
            'community_id' => $cRequest->community_id,
            'user_id' => $cRequest->user_id,
        ], [
            'role' => 'anggota',
            'joined_at' => now(),
        ]);

        $cRequest->update(['status' => 'approved']);

        // Kirim notifikasi
        try {
            $user = $cRequest->user;
            $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                $cRequest->community,
                auth()->user(),
                $user,
                'accepted'
            ));
        } catch (\Exception $e) {
            Log::error('Notify approveMembershipAjax error: '.$e->getMessage());
        }

        return response()->json(['success' => true]);
    }

    public function rejectMembershipAjax(Request $request)
    {
        $data = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Cek izin (manager atau admin)
        abort_unless(
            CommunityMember::where('community_id', $data['community_id'])
                ->where('user_id', auth()->id())
                ->where('role', 'admin')
                ->exists(),
            403
        );

        $cRequest = CommunityRequest::where('community_id', $data['community_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'pending')
            ->first();

        if (!$cRequest) {
            return response()->json(['success' => false, 'message' => 'Permintaan tidak ditemukan'], 404);
        }

        $cRequest->update(['status' => 'rejected']);

        // Kirim notifikasi
        try {
            $user = $cRequest->user;
            $user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                $cRequest->community,
                auth()->user(),
                $user,
                'rejected'
            ));
        } catch (\Exception $e) {
            Log::error('Notify rejectMembershipAjax error: '.$e->getMessage());
        }

        CommunityMember::where('community_id', $cRequest->community_id)
            ->where('user_id', $cRequest->user_id)
            ->delete();

        return response()->json(['success' => true]);
    }
    
    public function update(Request $request, Community $community)
    {
        abort_unless(
            $community->isManager(auth()->id()),
            403
        );

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:public,private',
            'location' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('community-logos', 'public');
            $community->logo = $logoPath;
        }

        $community->name = $request->name;
        $community->location = $request->location;
        $community->description = $request->description;
        $community->type = $request->type;
        $community->requires_approval = ($request->type === 'public' && $request->has('requires_approval'));
        $community->save();

        return redirect()->route('buyer.communities.show', $community->id)
            ->with('success', 'Komunitas berhasil diperbarui!');
    }

    /**
     * Hapus komunitas (Hanya pembuat)
     */
    public function destroy(Community $community)
    {
        // Hanya pembuat komunitas yang bisa menghapus
        abort_unless(
            $community->created_by == auth()->id(),
            403,
            'Hanya pembuat komunitas yang dapat menghapus komunitas ini'
        );

        try {
            DB::beginTransaction();

            // 1. Ambil semua member aktif (kecuali pembuat) untuk dikirim notifikasi
            $members = CommunityMember::where('community_id', $community->id)
                ->where('user_id', '!=', auth()->id())
                ->where('status', 'active')
                ->with('user')
                ->get();

            // 2. Kirim notifikasi "Komunitas Dibubarkan"
            foreach ($members as $member) {
                try {
                    $member->user->notify(new \App\Notifications\CommunityMemberStatusNotification(
                        $community,
                        auth()->user(),
                        $member->user,
                        'disbanded'
                    ));
                } catch (\Exception $e) {
                    Log::error('Notify disbanded member error: ' . $e->getMessage());
                }
            }

            // 3. Hapus relasi-relasi
            
            // Hapus undangan
            \App\Models\CommunityInvitation::where('community_id', $community->id)->delete();
            
            // Hapus permintaan gabung
            \App\Models\CommunityRequest::where('community_id', $community->id)->delete();
            
            // Hapus data main bareng (dan pesertanya jika ada logic cascade atau hapus manual)
            $playTogethers = \App\Models\PlayTogether::where('community_id', $community->id)->get();
            foreach ($playTogethers as $pt) {
                // Hapus peserta main bareng
                \App\Models\PlayTogetherParticipant::where('play_together_id', $pt->id)->delete();
                // Hapus undangan main bareng
                \App\Models\PlayTogetherInvitation::where('play_together_id', $pt->id)->delete();
                $pt->delete();
            }

            // Hapus Percakapan Komunitas
            $conversation = \App\Models\Conversation::where('community_id', $community->id)
                ->where('type', 'community')
                ->first();
            
            if ($conversation) {
                // Hapus pesan-pesan dalam percakapan
                \App\Models\Message::where('conversation_id', $conversation->id)->delete();
                $conversation->delete();
            }

            // Hapus Galeri Komunitas
            $galleries = \App\Models\CommunityGallery::where('community_id', $community->id)->get();
            foreach ($galleries as $gallery) {
                if ($gallery->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($gallery->image_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($gallery->image_path);
                }
                $gallery->delete();
            }

            // Hapus member
            \App\Models\CommunityMember::where('community_id', $community->id)->delete();

            // 4. Hapus Logo & Background (opsional: jika bukan default)
            if ($community->logo && $community->logo !== 'default.png') {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($community->logo);
            }
            if ($community->background_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($community->background_image);
            }

            // 5. Hapus Komunitas
            $community->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Komunitas berhasil dibubarkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete community error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komunitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Keluar dari komunitas
     */
    public function leave(Community $community)
    {
        // Prevent creator from leaving
        if ($community->created_by == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Pembuat komunitas tidak dapat keluar. Bubarkan komunitas jika ingin menghapus.'
            ], 403);
        }

        $userId = auth()->id();
        $member = \App\Models\CommunityMember::where('community_id', $community->id)
                    ->where('user_id', $userId)
                    ->first();

        if (!$member) {
             return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota komunitas ini.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Notify Creator
            $creator = \App\Models\User::find($community->created_by);
            if ($creator) {
                 try {
                     $creator->notify(new \App\Notifications\CommunityMemberStatusNotification(
                            $community,
                            auth()->user(),
                            $creator,
                            'left_community'
                     ));
                 } catch (\Exception $e) {
                     Log::error('Notify creator leave error: ' . $e->getMessage());
                 }
            }

            // Delete Member and associated requests
            $member->delete();

            \App\Models\CommunityRequest::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil keluar dari komunitas.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave community error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal keluar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchUsers(Request $request, Community $community)
    {
        $query = $request->q ?? '';

        if (empty($query)) {
            return response()->json([]);
        }

        $users = User::where('role', 'buyer')
        ->where('name', 'like', "%{$query}%")
            ->whereDoesntHave('communityMembers', function ($q) use ($community) {
                $q->where('community_id', $community->id);
            })
            ->get()
            ->map(function ($user) use ($community) {

                $isPendingInvite = $user->communityInvitations()
                    ->where('community_id', $community->id)
                    ->where('status', 'pending')
                    ->exists();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : asset('images/default-avatar.png'),
                    'is_pending' => $isPendingInvite
                ];
            });

        return response()->json($users);
    }    

    public function inviteUser(Request $request, Community $community)
    {
        $isManager = $community->isManager(auth()->id());

        if (!$isManager) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya izin'
            ], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $invitedUserId = $validated['user_id'];

        if (CommunityMember::where('community_id', $community->id)
            ->where('user_id', $invitedUserId)
            ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah menjadi member'
            ], 409);
        }

        CommunityInvitation::where('community_id', $community->id)
            ->where('invited_user_id', $invitedUserId)
            ->delete();

        CommunityInvitation::create([
            'community_id'    => $community->id,
            'invited_user_id' => $invitedUserId,
            'invited_by'      => auth()->id(),
            'status'          => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Undangan berhasil dikirim'
        ]);
    }

    public function joinInvite(Request $request, Community $community)
    {
        $userId = auth()->id();
        
        $invitation = CommunityInvitation::where('community_id', $community->id)
            ->where('invited_user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan tidak ditemukan atau sudah diproses'
                ], 404);
            }
            return back()->with('error', 'Undangan tidak ditemukan');
        }

        DB::transaction(function () use ($community, $userId, $invitation) {
            // Hapus undangan karena sudah diterima (sesuai request user)
            $invitation->delete();

            // Update status CommunityMember menjadi active jika sudah ada
            CommunityMember::updateOrCreate([
                'community_id' => $community->id,
                'user_id' => $userId,
            ], [
                'role' => 'anggota',
                'status' => 'active',
                'joined_at' => now(),
            ]);

             // Kirim notifikasi ke inviter bahwa undangan diterima
             try {
                if ($invitation->invited_by) {
                    $inviter = \App\Models\User::find($invitation->invited_by);
                    if ($inviter) {
                        $user = \App\Models\User::find($userId);
                        $inviter->notify(new \App\Notifications\CommunityMemberStatusNotification(
                            $community,
                            $user, // Actor is the user who accepted
                            $inviter, // Target is the inviter
                            'invitation_accepted' // Status specifically for invitation
                        ));
                    }
                }
            } catch (\Exception $e) {
                // Ignore notification error
            }

            // Hapus request jika ada
            CommunityRequest::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->delete();

            // Update notification data to hide buttons (persistency)
            try {
                $user = auth()->user();
                $notifications = $user->notifications()
                    ->where('data->community_id', $community->id)
                    ->get();

                foreach ($notifications as $notification) {
                    if (in_array($notification->data['notification_type'] ?? '', ['community_reinvitation', 'community_invitation'])) {
                        $data = $notification->data;
                        $data['action_taken'] = true;
                        $data['status'] = 'accepted';
                        $notification->data = $data;
                        $notification->save();
                    }
                }
            } catch (\Exception $e) {
                // Ignore
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil bergabung ke komunitas'
            ]);
        }
        return back()->with('success', 'Berhasil bergabung ke komunitas');
    }

    public function rejectInvite(Request $request, Community $community)
    {
        $userId = auth()->id();
        $invitation = CommunityInvitation::where('community_id', $community->id)
            ->where('invited_user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan tidak ditemukan'
                ], 404);
            }
            return back()->with('error', 'Undangan tidak ditemukan');
        }

        DB::transaction(function() use ($invitation, $community, $userId) {
            // Hapus undangan karena ditolak (sesuai request user)
            $invitation->delete();
            
            // Jika dia adalah member yang dikeluarkan (status=removed), 
            // Hapus data member tersebut (hard delete dari community_members)
            $member = CommunityMember::where('community_id', $community->id)
                ->where('user_id', $userId)
                ->where('status', 'removed')
                ->first();
                
            if ($member) {
                $member->delete();
            }

             // Update notification data to hide buttons (persistency)
             try {
                $user = auth()->user();
                $notifications = $user->notifications()
                    ->where('data->community_id', $community->id)
                    ->get();

                foreach ($notifications as $notification) {
                    if (in_array($notification->data['notification_type'] ?? '', ['community_reinvitation', 'community_invitation'])) {
                        $data = $notification->data;
                        $data['action_taken'] = true;
                        $data['status'] = 'rejected';
                        $notification->data = $data;
                        $notification->save();
                    }
                }
            } catch (\Exception $e) {
                // Ignore
            }

             // Kirim notifikasi ke inviter bahwa undangan ditolak
             try {
                if ($invitation->invited_by) {
                    $inviter = \App\Models\User::find($invitation->invited_by);
                    if ($inviter) {
                        $user = \App\Models\User::find($userId);
                        $inviter->notify(new \App\Notifications\CommunityMemberStatusNotification(
                            $community,
                            $user, // Actor
                            $inviter, // Target
                            'invitation_rejected' 
                        ));
                    }
                }
            } catch (\Exception $e) {
                // Ignore notification error
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil ditolak'
            ]);
        }
        return back()->with('success', 'Undangan ditolak');
    }

    public function chat(Community $community)
    {
        // Ensure user is member
        abort_unless(
            $community->isMember(auth()->id()),
            403,
            'Anda harus menjadi anggota komunitas untuk masuk ke chat.'
        );

        $conversation = \App\Models\Conversation::findOrCreateCommunityConversation($community->id);
        
        return redirect()->route('chat.show', $conversation->id);
    }
    // ================= NEW: MEMBERS MANAGEMENT =================
    /**
     * Tampilkan halaman daftar member komunitas
     */
    public function members(Community $community)
    {
        // ✅ FIX 1: Cek current member menggunakan isMember()
        if (!$community->isMember(auth()->id())) {
            abort(403, 'Anda bukan member dari komunitas ini');
        }

        // ✅ FIX 2: Ambil current member record untuk cek role
        $currentMember = $community->memberRecords()
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        // ✅ FIX 3: Ambil semua members menggunakan memberRecords() bukan members()
        $members = $community->memberRecords()
            ->where('status', 'active')
            ->with('user') // ⬅️ Sekarang bisa with('user') karena CommunityMember punya relasi user
            ->orderByRaw("CASE WHEN role = 'admin' THEN 1 ELSE 2 END")
            ->orderBy('joined_at', 'asc')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'user_id' => $member->user_id,
                    'role' => $member->role,
                    'joined_at' => $member->joined_at,
                    'user' => [
                        'name' => optional($member->user)->name ?? 'Unknown',
                        'username' => optional($member->user)->username ?? optional($member->user)->email ?? '',
                        'email' => optional($member->user)->email ?? '',
                        'avatar' => optional($member->user)->avatar 
                            ? asset('storage/' . $member->user->avatar)
                            : null,
                    ]
                ];
            });

        // Siapkan data community
        $communityData = [
            'id' => $community->id,
            'name' => $community->name,
            'description' => $community->description,
            'logo' => $community->logo 
                ? asset('storage/' . $community->logo)
                : null,
            'image' => $community->image ?? $community->logo,
            'background_image' => $community->background_image 
                ? asset('storage/' . $community->background_image)
                : null,
            'type' => $community->type,
            'created_by' => $community->created_by,
            'is_manager' => $currentMember ? $currentMember->role === 'admin' : false,
            'current_user_role' => $currentMember ? $currentMember->role : null,
            'location' => $community->location,
            'category' => $community->category->category_name ?? 'Olahraga',
        ];

        return view('buyer.communities.members.index', [
            'community' => $communityData,
            'members' => $members
        ]);
    }

    /**
     * Jadikan member sebagai admin
     */
    public function makeAdmin(Community $community, $memberId)
    {
        // Cek apakah user adalah admin
        if (!$community->isManager(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat mengubah role member'
            ], 403);
        }

        // Cari member berdasarkan ID
        $member = CommunityMember::where('id', $memberId)
            ->where('community_id', $community->id)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ], 404);
        }

        // Cek apakah member yang akan diubah adalah diri sendiri
        if ($member->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah role Anda sendiri'
            ], 400);
        }

        try {
            DB::transaction(function () use ($member, $community) {
                $oldRole = $member->role;
                
                // Update role menjadi admin
                $member->update(['role' => 'admin']);

                // Kirim notifikasi (jika ada sistem notifikasi)
                if (class_exists('\App\Models\Notification')) {
                    try {
                        \App\Models\Notification::sendRoleChangedNotification(
                            $member->user_id,
                            $community->name,
                            'admin',
                            $oldRole,
                            auth()->id(),
                            $community->id
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send notification: ' . $e->getMessage());
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dijadikan admin'
            ]);

        } catch (\Exception $e) {
            Log::error('Make admin error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ubah admin menjadi member biasa
     */
    public function removeAdmin(Community $community, $memberId)
    {
        // Cek apakah user adalah admin
        if (!$community->isManager(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat mengubah role member'
            ], 403);
        }

        // Cari member berdasarkan ID
        $member = CommunityMember::where('id', $memberId)
            ->where('community_id', $community->id)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ], 404);
        }

        // Cek apakah member yang akan diubah adalah diri sendiri
        if ($member->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah role Anda sendiri'
            ], 400);
        }

        try {
            DB::transaction(function () use ($member, $community) {
                $oldRole = $member->role;
                
                // Update role menjadi anggota
                $member->update(['role' => 'anggota']);

                // Kirim notifikasi (jika ada sistem notifikasi)
                if (class_exists('\App\Models\Notification')) {
                    try {
                        \App\Models\Notification::sendRoleChangedNotification(
                            $member->user_id,
                            $community->name,
                            'anggota',
                            $oldRole,
                            auth()->id(),
                            $community->id
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send notification: ' . $e->getMessage());
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diubah menjadi anggota'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove admin error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeMember(Request $request, Community $community, $memberId)
    {
        Log::info('Attempting to remove member', [
            'community_id' => $community->id,
            'member_id' => $memberId,
            'auth_id' => auth()->id()
        ]);

        // Validasi input
        $validated = $request->validate([
            'message' => 'required|string|min:5|max:500',
            'reason' => 'nullable|string|in:pelanggaran_aturan,tidak_aktif,perilaku_tidak_sopan,lainnya'
        ]);

        // Cari member berdasarkan ID
        $member = CommunityMember::where('id', $memberId)
            ->where('community_id', $community->id)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ], 404);
        }

        // Cek apakah user adalah manager atau admin
        if (!$community->isManager(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengeluarkan anggota'
            ], 403);
        }

        // Cek apakah member yang akan dikeluarkan adalah diri sendiri
        if ($member->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengeluarkan diri sendiri'
            ], 400);
        }

        // Cek apakah anggota yang akan dikeluarkan adalah admin yang lain? 
        // Sebaiknya admin bisa mengeluarkan admin lain jika dia pembuat komunitas?
        // Tapi untuk simplifikasi sesuai request user, kita biarkan logicnya.
        if ($member->role === 'admin' && $community->created_by !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengeluarkan admin lain'
            ], 400);
        }

        try {
            DB::transaction(function () use ($member, $community, $validated) {
                // Simpan data user sebelum dihapus
                $removedUserId = $member->user_id;
                
                // Update status member jadi removed daripada dihapus
                $member->update(['status' => 'removed']);

                // Bersihkan data di community_requests agar status di UI join juga reset
                CommunityRequest::where('community_id', $community->id)
                    ->where('user_id', $removedUserId)
                    ->delete();

                // Kirim notifikasi ke user yang dikeluarkan (jika ada sistem notifikasi)
                if (class_exists('\App\Models\Notification')) {
                    try {
                        \App\Models\Notification::sendMemberRemovedNotification(
                            $removedUserId,
                            $community->name,
                            $validated['message'],
                            $validated['reason'] ?? null,
                            auth()->id(),
                            $community->id
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send removal notification: ' . $e->getMessage());
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dikeluarkan dari komunitas'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove member error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Kirim pesan broadcast ke semua member komunitas
     */
    public function sendCommunityMessage(Request $request, Community $community)
    {
        // Validasi input
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'type' => 'required|string|in:announcement,reminder'
        ]);

        // Cek apakah user adalah admin
        if (!$community->isManager(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat mengirim pesan ke komunitas'
            ], 403);
        }

        try {
            // Ambil semua member kecuali pengirim
            $memberUserIds = $community->memberRecords()
                ->where('status', 'active')
                ->where('user_id', '!=', auth()->id())
                ->pluck('user_id');

            // Kirim notifikasi ke semua member (jika ada sistem notifikasi)
            if (class_exists('\App\Models\Notification')) {
                foreach ($memberUserIds as $userId) {
                    try {
                        \App\Models\Notification::sendCommunityMessageNotification(
                            $userId,
                            $community->name,
                            $validated['message'],
                            $validated['type'],
                            auth()->id(),
                            $community->id
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send message notification: ' . $e->getMessage());
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke ' . $memberUserIds->count() . ' member'
            ]);

        } catch (\Exception $e) {
            Log::error('Send community message error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateBackground(Request $request, Community $community)
    {
        \Illuminate\Support\Facades\Log::info('Background Update Request:', [
            'community_id' => $community->id,
            'user_id' => auth()->id(),
            'has_file' => $request->hasFile('image'),
            'all_input' => $request->all()
        ]);

        // Cek izin (admin)
        if (!$community->isManager(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah background'
            ], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Increased to 10MB
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($community->background_image) {
                     \Illuminate\Support\Facades\Storage::disk('public')->delete($community->background_image);
                }

                $path = $request->file('image')->store('community-backgrounds', 'public');
                
                // Explicitly set and save to ensure persistence
                $community->background_image = $path;
                $community->save();
                
                // Refresh to get the latest data from database
                $community->refresh();

                \Illuminate\Support\Facades\Log::info('Background Update Success:', [
                    'community_id' => $community->id,
                    'path' => $path,
                    'db_value' => $community->background_image,
                    'full_url' => asset('storage/' . $path)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Background berhasil diperbarui',
                    'url' => asset('storage/' . $path),
                    'path' => $path
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada gambar yang diupload'
            ], 400);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Background Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SHOW MAIN BARENG - Community specific detail page
     */
    public function showMainBareng($communityId, $id)
    {
        $community = Community::findOrFail($communityId);
        
        $playTogether = PlayTogether::with([
            'booking.venue.category',
            'booking.schedule',
            'participants.user',
            'creator',
            'invitations.invitedUser'
        ])->findOrFail($id);

        $user = Auth::user();
        
        // Cek apakah user sudah join
        $hasJoined = PlayTogetherParticipant::where('play_together_id', $id)
            ->where('user_id', $user->id)
            ->whereIn('approval_status', ['pending', 'approved'])
            ->exists();
        
        // Cek apakah user adalah creator
        $isCreator = $playTogether->created_by == $user->id;
        
        // Tentukan apakah tombol bergabung/ajukan bergabung ditampilkan
        $showJoinButton = false;
        $showApplyButton = false;
        $showHostJoinButton = false;
        
        if (!$hasJoined) {
            // Jika user adalah creator/host
            if ($isCreator) {
                $showHostJoinButton = true; // Tombol khusus untuk host bergabung
            } 
            // Jika user bukan creator
            else {
                if ($playTogether->host_approval) {
                    $showApplyButton = true; // Tombol "Ajukan Bergabung"
                } else {
                    $showJoinButton = true; // Tombol "Bergabung"
                }
            }
        }
        
        // Hitung peserta yang approved
        $approvedParticipantsCount = $playTogether->participants()
            ->where('approval_status', 'approved')
            ->count();
        
        // Ambil peserta yang approved
        $approvedParticipants = $playTogether->participants()
            ->with('user')
            ->where('approval_status', 'approved')
            ->get();
        
        // Ambil peserta yang pending
        $pendingParticipants = $playTogether->participants()
            ->with('user')
            ->where('approval_status', 'pending')
            ->get();

        // Check if quota is full
        $isFull = $approvedParticipantsCount >= $playTogether->max_participants;

        // Ambil setting untuk midtrans
        $setting = Setting::first();

        return view('buyer.communities.show-main-bareng', compact(
            'community',
            'playTogether',
            'hasJoined',
            'isCreator',
            'showJoinButton',
            'showApplyButton',
            'showHostJoinButton',
            'approvedParticipantsCount',
            'approvedParticipants',
            'pendingParticipants',
            'isFull',
            'setting'
        ));
    }
}