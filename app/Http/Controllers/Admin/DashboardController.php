<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Community;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for admin
     */
    public function index()
{
    $totalUsers = User::count();
    $totalCategories = Category::count();
    $totalCommunities = Community::count();

    // Samakan dengan blade
    $totalPenyewa = User::where('role', 'buyer')->count();
    $totalPemilik = User::where('role', 'landowner')->count();
    $totalAdmin   = User::where('role', 'admin')->count();

    $communityPublic  = Community::where('type', 'public')->count();
    $communityPrivate = Community::where('type', 'private')->count();

    return view('admin.dashboard.index', compact(
        'totalUsers',
        'totalCategories',
        'totalCommunities',
        'totalPenyewa',
        'totalPemilik',
        'totalAdmin',
        'communityPublic',
        'communityPrivate'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}