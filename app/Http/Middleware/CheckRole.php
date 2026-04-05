<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Debug info (helpful for troubleshooting)
        // Log::info('CheckRole', ['user' => $user->id, 'role' => $user->role, 'active' => $user->getActiveRole(), 'required' => $role]);

        // 1. ADMIN & CASHIER: Strict check
        if (in_array($user->role, ['admin', 'cashier'])) {
            if ($user->role === $role) {
                return $next($request);
            }
            abort(403, 'Akses Ditolak! Anda login sebagai ' . $user->role);
        }

        // 2. BUYER & LANDOWNER: Check 'role' column directly
        // The 'role' column now represents the ACTIVE state
        if ($user->role === $role) {
            return $next($request);
        }
        
        // Error Handling & Helpers
        if ($role === 'landowner') {
             // If trying to access landowner but currently in buyer mode (and has permission)
             if ($user->canSwitchToLandowner()) {
                 return redirect()->route('buyer.profile')->with('info', 'Silakan switch ke mode Landowner terlebih dahulu dari Profile.');
             }
             abort(403, 'Akses Ditolak! Anda belum menjadi Landowner.');
        }
        
        if ($role === 'buyer') {
             // If trying to access buyer but currently in landowner mode
             return redirect()->route('landowner.home')->with('info', 'Silakan switch ke mode Penyewa terlebih dahulu dari Menu.');
        }

        abort(403, 'UNAUTHORIZED ACCESS. Role anda saat ini: ' . $user->role);
    }

}