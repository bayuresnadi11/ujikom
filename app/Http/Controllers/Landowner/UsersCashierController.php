<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersCashierController extends Controller
{
    /**
     * Display a listing of cashier users.
     */
    public function index()
    {
        // Logika Sekuritas Data: Hanya tampilkan akun cashier yang secara eksklusif dibuat oleh landowner yang sedang login
        $user = auth()->user();
        $cashiers = User::where('role', 'cashier')
                        ->where('created_by', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $totalCashiers = $cashiers->count();
        
        // Logika Analitik: Mendapatkan total venue yang dimiliki oleh landowner ini untuk keperluan statistik UI
        $venues = Venue::where('created_by', $user->id)->count();

        // Menyematkan perhitungan statis jumlah venue yang berhak dikelola (manageable) oleh setiap kasir
        foreach ($cashiers as $cashier) {
            $cashier->manageable_venues_count = Venue::where('created_by', $cashier->created_by)->count();
        }

        return view('landowner.cashier.index', compact('cashiers', 'totalCashiers', 'venues'));
    }

    public function create()
    {
        return view('landowner.cashier.create');
    }

    /**
     * Store a newly created cashier.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = auth()->user();
            
            User::create([
                'name'       => $request->name,
                'phone'      => $request->phone,
                'password'   => Hash::make($request->password),
                'role'       => 'cashier',
                'created_by' => $user->id,
            ]);

            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified cashier.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        return view('landowner.cashier.edit', compact('cashier'));
    }

    /**
     * Update the specified cashier.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone,' . $cashier->id,
            'password' => 'nullable|string|min:6', // Password optional on update
            'password_confirmation' => 'nullable|same:password',
        ]);

        try {
            $data = [
                'name'  => $request->name,
                'phone' => $request->phone,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $cashier->update($data);

            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified cashier.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        try {
            $cashier->delete();

            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}