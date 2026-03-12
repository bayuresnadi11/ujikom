<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RoleRequest;
use App\Models\User;

class RoleController extends Controller
{
    // ==================== BUYER: Submit Landowner Request ====================
    public function submitLandownerRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|min:50|max:500'
        ]);
        
        $user = auth()->user();
        
        // Check if already approved
        if ($user->canSwitchToLandowner()) {
            return redirect()->route('buyer.profile')
                ->with('info', 'Anda sudah disetujui sebagai pemilik lapangan. Gunakan tombol "Switch to Landowner".');
        }
        
        // Check if has pending request
        if ($user->hasPendingLandownerRequest()) {
            return back()->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }
        
        // Create new request
        RoleRequest::create([
            'user_id' => $user->id,
            'requested_role' => 'landowner',
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        
        return redirect()->route('buyer.profile')
            ->with('success', 'Pengajuan berhasil dikirim! Tunggu review admin (1-3 hari kerja).');
    }

    // ==================== BUYER: Switch to Landowner (NO LOGOUT!) ====================
    public function switchToLandowner()
    {
        $user = auth()->user();
        
        // Validate permission
        if (!$user->canSwitchToLandowner()) {
            return back()->with('error', 'Anda belum disetujui sebagai pemilik lapangan.');
        }
        
        try {
            // Switch role (updates 'role' column)
            $user->switchRole('landowner');
            
            return redirect()->route('landowner.home')
                ->with('success', 'Selamat datang di Mode Pemilik Lapangan!');
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== LANDOWNER: Switch to Buyer (NO LOGOUT!) ====================
    public function switchToBuyer()
    {
        $user = auth()->user();
        
        try {
            // Switch role (updates 'role' column)
            $user->switchRole('buyer');
            
            return redirect()->route('buyer.home')
                ->with('success', 'Kembali ke Mode Penyewa! ⚽');
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== ADMIN: Approve Request ====================
    public function approveRequest($id)
    {
        $roleRequest = RoleRequest::findOrFail($id);
        
        // Validate status
        if ($roleRequest->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses sebelumnya.');
        }
        
        try {
            DB::transaction(function() use ($roleRequest) {
                // Update request
                $roleRequest->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id(),
                ]);
                
                // Grant permission to user AND switch them to landowner immediately
                $roleRequest->user->update([
                    'can_switch_to_landowner' => true,
                    'landowner_approved_at' => now(),
                    'role' => 'landowner', // Switch them now so they see the change
                ]);
            });
            
            // TODO: Send notification to user
            
            return back()->with('success', 'Request berhasil disetujui! User sekarang bisa switch ke mode landowner.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve request: ' . $e->getMessage());
        }
    }

    // ==================== ADMIN: Reject Request ====================
    public function rejectRequest(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:role_requests,id',
            'admin_notes' => 'required|min:10|max:500',
        ]);
        
        $roleRequest = RoleRequest::findOrFail($request->id);
        
        // Validate status
        if ($roleRequest->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses sebelumnya.');
        }
        
        try {
            $roleRequest->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);
            
            // TODO: Send notification to user with rejection reason
            
            return back()->with('success', 'Request berhasil ditolak.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reject request: ' . $e->getMessage());
        }
    }
}