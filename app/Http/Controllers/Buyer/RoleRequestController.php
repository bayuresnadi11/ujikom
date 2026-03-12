<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleRequestController extends Controller
{

public function approve($id)
{
    $request = RoleRequest::findOrFail($id);

    $request->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);

    // UPDATE ROLE USER
    $user = $request->user;
    $user->role = 'landowner';
    $user->save();

    // 🔥 PAKSA LOGOUT
    Auth::logoutOtherDevices($user->password);
}

public function reject(Request $request)
{
    $roleRequest = RoleRequest::findOrFail($request->id);

    if ($roleRequest->status !== 'pending') {
        return back()->with('error', 'Request sudah diproses.');
    }

    $roleRequest->update(['status' => 'rejected']);

    return back()->with('success', 'Permintaan ditolak.');
}

}
