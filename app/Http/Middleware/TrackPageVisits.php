<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track for authenticated users
        if (Auth::check()) {
            $this->trackPageVisit($request);
        }

        return $next($request);
    }

    /**
     * Track the current page visit
     */
    private function trackPageVisit(Request $request)
    {
        $currentRoute = $request->route();
        $routeName = $currentRoute ? $currentRoute->getName() : null;

        // Skip tracking certain routes
        $skipRoutes = ['buyer.menu', 'logout', 'login', 'register'];
        if (in_array($routeName, $skipRoutes)) {
            return;
        }

        $pageData = [
            'name' => $this->getPageName($routeName),
            'route' => $routeName,
            'url' => $request->fullUrl(),
            'timestamp' => now()->timestamp,
            'icon' => $this->getPageIcon($routeName)
        ];

        // Get existing page history from session
        $pageHistory = session('page_history', []);

        // Remove if this page already exists in history (to avoid duplicates)
        $pageHistory = array_filter($pageHistory, function($page) use ($routeName) {
            return $page['route'] !== $routeName;
        });

        // Add current page to the beginning
        array_unshift($pageHistory, $pageData);

        // Keep only the last 5 pages
        $pageHistory = array_slice($pageHistory, 0, 5);

        // Store in session
        session(['page_history' => $pageHistory]);
    }

    /**
     * Get user-friendly page name from route
     */
    private function getPageName($routeName)
    {
        $pageNames = [
            'buyer.home.index' => 'Beranda',
            'buyer.venue.index' => 'Daftar Lapangan',
            'buyer.venue.show' => 'Detail Lapangan',
            'buyer.booking.index' => 'Booking Saya',
            'buyer.booking.create' => 'Buat Booking',
            'buyer.communities.index' => 'Komunitas',
            'buyer.communities.show' => 'Detail Komunitas',
            'buyer.main_bareng.index' => 'Main Bareng',
            'buyer.deposit.index' => 'Deposit',
            'buyer.withdraw.index' => 'Penarikan',
            'buyer.profile.edit' => 'Edit Profil',
            'buyer.notifications.index' => 'Notifikasi',
            'buyer.chat.index' => 'Pesan',
        ];

        return $pageNames[$routeName] ?? 'Halaman';
    }

    /**
     * Get icon for the page
     */
    private function getPageIcon($routeName)
    {
        $pageIcons = [
            'buyer.home.index' => 'fas fa-home',
            'buyer.venue.index' => 'fas fa-futbol',
            'buyer.venue.show' => 'fas fa-map-marker-alt',
            'buyer.booking.index' => 'fas fa-calendar-check',
            'buyer.booking.create' => 'fas fa-plus-circle',
            'buyer.communities.index' => 'fas fa-users',
            'buyer.communities.show' => 'fas fa-user-friends',
            'buyer.main_bareng.index' => 'fas fa-handshake',
            'buyer.deposit.index' => 'fas fa-wallet',
            'buyer.withdraw.index' => 'fas fa-money-bill-wave',
            'buyer.profile.edit' => 'fas fa-user-edit',
            'buyer.notifications.index' => 'fas fa-bell',
            'buyer.chat.index' => 'fas fa-comments',
        ];

        return $pageIcons[$routeName] ?? 'fas fa-circle';
    }
}
