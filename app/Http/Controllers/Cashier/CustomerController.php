<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VenueSection;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomerController extends Controller
{   
    public function index(Request $request)
    {
        $perPage = 10;
        
        // ========== TOTAL COUNT - TIDAK TERPENGARUH SEARCH ==========
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
            
            // Gunakan where closure untuk OR condition yang benar
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // HARUS paginate(), BUKAN get()
        $customers = $query->paginate($perPage);
        
        // Append search parameter to pagination links
        $customers->appends($request->query());
        
        // Other data (ini boleh get() karena bukan pagination)
        $sections = VenueSection::with('venue')->get();
        $venues = Venue::all();
        
        // Pastikan me-return ke view yang benar dengan totalCustomers
        return view('cashier.dashboard.index', compact('customers', 'sections', 'venues', 'totalCustomers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username',
            'phone' => 'required|string|unique:users,phone',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // password auto 6 digit
        $password = rand(100000, 999999);

        $user = User::create([
            'name'       => $request->name,
            'username'   => $request->username ?: null, // 🔥 INI DIA
            'phone'      => $phone,
            'password'   => Hash::make($password),
            'role'       => 'buyer',
            'created_by' => auth()->id(), // cashier
        ]);

        return back()->with('success', "Penyewa berhasil ditambahkan. Password: $password");
    }

    public function destroy(User $customer)
    {
        try {
            // Pastikan hanya bisa menghapus data yang dibuatnya sendiri
            if ($customer->created_by != auth()->id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk menghapus data ini.'
                    ], 403);
                }
                return back()->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
            }
            
            $customer->delete();
            
            // Response untuk AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penyewa berhasil dihapus!'
                ]);
            }
            
            // Response untuk non-AJAX (fallback)
            return redirect()->route('cashier.dashboard.index')
                ->with('success', 'Penyewa berhasil dihapus!');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data.'
                ], 500);
            }
            
            return redirect()->route('cashier.dashboard.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }
}