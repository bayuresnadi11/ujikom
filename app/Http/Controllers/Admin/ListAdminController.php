<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ListAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil hanya user dengan role admin
        $admins = User::where('role', 'admin')
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        return view('admin.listadmin.index', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin'
        ];

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return response()->json($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update avatar jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $admin->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $currentUser = Auth::user();
        
        // 1. Cek jika admin yang ingin dihapus adalah admin yang sedang login
        if ($admin->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri!'
            ], 422);
        }
        
        // 2. Cek jumlah total admin - MINIMAL 1 ADMIN
        $totalAdmin = User::where('role', 'admin')->count();
        
        if ($totalAdmin <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus admin! Minimal harus ada 1 admin dalam sistem.'
            ], 422);
        }
        
        // 3. Hapus avatar jika ada
        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }
        
        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus!'
        ]);
    }

    /**
     * Check current user for validation
     */
    public function checkCurrentUser()
    {
        return response()->json([
            'user_id' => Auth::id()
        ]);
    }
}