<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $communities = Community::with('creator')->orderBy('created_at', 'desc')->get();
        return view('admin.community.index', compact('communities'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $communities = Community::with('creator')->findOrFail($id);
        return view('admin.community.show', compact('communities'));
    }

    // Hapus semua method lainnya (store, update, destroy)
    // Karena hanya read-only
}