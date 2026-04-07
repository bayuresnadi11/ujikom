<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * Class PencairanController
 * 
 * Mengelola permintaan pencairan dana (withdrawal) dari pengguna.
 * Admin dapat menyetujui, memproses, atau menolak permintaan pencairan dana.
 */
class PencairanController extends Controller
{
    /**
     * Menampilkan daftar semua permintaan pencairan dana dengan filter status.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with(['user', 'processedBy']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('created_at', 'desc');

        $withdrawals = $query->paginate(10);
        
        // Get status counts
        $statusCounts = [
            'all' => WithdrawalRequest::count(),
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'approved' => WithdrawalRequest::where('status', 'approved')->count(),
            'processed' => WithdrawalRequest::where('status', 'processed')->count(),
            'rejected' => WithdrawalRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.pencairan.index', compact('withdrawals', 'statusCounts'));
    }

    /**
     * Menampilkan halaman edit untuk memproses permintaan pencairan dana tertentu.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $withdrawal = WithdrawalRequest::with(['user', 'processedBy'])->findOrFail($id);
        return view('admin.pencairan.edit', compact('withdrawal'));
    }

    /**
     * Memperbarui status dan informasi permintaan pencairan dana di database.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed',
            'rejection_reason' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $withdrawal = WithdrawalRequest::findOrFail($id);
        
        // Simpan foto baru jika diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($withdrawal->photo && Storage::exists($withdrawal->photo)) {
                Storage::delete($withdrawal->photo);
            }
            
            $photoPath = $request->file('photo')->store('withdrawal-proofs', 'public');
            $withdrawal->photo = $photoPath;
        }

        // Update status
        $withdrawal->status = $request->status;

        // Jika status rejected, simpan alasan penolakan
        if ($request->status === 'rejected') {
            $withdrawal->rejection_reason = $request->rejection_reason;
        } else {
            $withdrawal->rejection_reason = null;
        }

        // Jika status processed, set processed_at dan processed_by
        if ($request->status === 'processed') {
            $withdrawal->processed_at = now();
            $withdrawal->processed_by = Auth::id();
        } elseif ($withdrawal->status !== 'processed' && $request->status !== 'processed') {
            // Reset jika bukan processed
            $withdrawal->processed_at = null;
            $withdrawal->processed_by = null;
        }

        $withdrawal->save();

        return redirect()->route('admin.pencairan.index')
            ->with('success', 'Status pencairan dana berhasil diperbarui!');
    }
}