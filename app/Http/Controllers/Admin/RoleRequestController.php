<?php
// app/Http/Controllers/Admin/RoleRequestController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRequestController extends Controller
{
    /**
     * Display a listing of role requests.
     */
    public function index()
    {
        $roleRequests = RoleRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.rolerequest.index', compact('roleRequests'));
    }

    /**
     * Approve a role request.
     */
    public function approve(Request $request)
    {
        try {
            $validated = $request->validate([
                'request_id' => 'required|exists:role_requests,id',
            ]);

            DB::transaction(function () use ($validated) {
                $roleRequest = RoleRequest::findOrFail($validated['request_id']);
                
                if ($roleRequest->status !== 'pending') {
                    throw new \Exception('Permintaan ini sudah diproses sebelumnya.');
                }

                // Update user role
                $user = User::findOrFail($roleRequest->user_id);

                // Update role request with approved_at timestamp
                $roleRequest->status = 'approved';
                $roleRequest->approved_at = now();
                $roleRequest->save();

                // Send notification to user
                $user->notify(new \App\Notifications\RoleRequestStatusNotification(
                    $roleRequest,
                    'approved'
                ));
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan role berhasil disetujui. Role pengguna telah diubah menjadi landowner.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reject a role request.
     */
    public function reject(Request $request)
    {
        try {
            $validated = $request->validate([
                'request_id' => 'required|exists:role_requests,id',
            ]);

            DB::transaction(function () use ($validated) {
                $roleRequest = RoleRequest::findOrFail($validated['request_id']);
                
                if ($roleRequest->status !== 'pending') {
                    throw new \Exception('Permintaan ini sudah diproses sebelumnya.');
                }

                // Update role request
                $roleRequest->status = 'rejected';
                $roleRequest->save();

                // Send notification to user
                $user = User::findOrFail($roleRequest->user_id);
                $user->notify(new \App\Notifications\RoleRequestStatusNotification(
                    $roleRequest,
                    'rejected'
                ));
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan role berhasil ditolak.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}