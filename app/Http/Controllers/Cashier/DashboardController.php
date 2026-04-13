<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venue;
use App\Models\VenueSection;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display dashboard for cashier
     */
    public function index(Request $request)
    {
        $perPage = 5;
        
        // Get total count (tidak terpengaruh search)
        $totalCustomers = User::where('created_by', auth()->id())
            ->where('role', 'buyer')
            ->count();
        
        // Get customers created by this cashier
        $query = User::where('created_by', auth()->id())
            ->where('role', 'buyer')
            ->latest();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->paginate($perPage);
        
        // Other data
        $sections = VenueSection::with('venue')->get();
        $venues = Venue::all();
        
        return view('cashier.dashboard.index', compact('customers', 'sections', 'venues', 'totalCustomers'));
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

    dd($request->all());
    
        $validated = $request->validate([
            'name' => 'required|max:100',
            'username' => 'nullable|unique:users,username',
            'phone' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username ?: null,
            'phone' => $request->phone,
            'role' => 'buyer',
            'created_by' => auth()->id(),
            'password' => Hash::make('password123')        
        ]);

        return back()->with('success', 'Penyewa berhasil ditambahkan');
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
