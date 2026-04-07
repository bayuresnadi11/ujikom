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
     * Menampilkan daftar user cashier
     */
    public function index()
    {
        // Ambil user yang sedang login (landowner)
        $user = auth()->user();

        // Ambil data cashier yang dibuat oleh landowner tersebut saja
        $cashiers = User::where('role', 'cashier')
                        ->where('created_by', $user->id)
                        ->orderBy('created_at', 'desc') // urutkan dari terbaru
                        ->paginate(5); // pagination 5 data per halaman

        // Hitung jumlah cashier (di halaman saat ini)
        $totalCashiers = $cashiers->count();
        
        // Hitung jumlah venue yang dimiliki oleh landowner
        $venues = Venue::where('created_by', $user->id)->count();

        // Tambahkan atribut tambahan ke setiap cashier (jumlah venue yang bisa dikelola)
        foreach ($cashiers as $cashier) {
            $cashier->manageable_venues_count = Venue::where('created_by', $cashier->created_by)->count();
        }

        // Kirim data ke view
        return view('landowner.cashier.index', compact('cashiers', 'totalCashiers', 'venues'));
    }

    /**
     * Menampilkan halaman form tambah cashier
     */
    public function create()
    {
        return view('landowner.cashier.create');
    }

    /**
     * Menyimpan data cashier baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone', // harus unik
            'password' => 'required|string|min:6',
        ]);

        try {
            // Ambil user yang sedang login
            $user = auth()->user();
            
            // Simpan data cashier ke database
            User::create([
                'name'       => $request->name,
                'phone'      => $request->phone,
                'password'   => Hash::make($request->password), // enkripsi password
                'role'       => 'cashier', // set role sebagai cashier
                'created_by' => $user->id, // relasi ke landowner
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil ditambahkan');

        } catch (\Exception $e) {
            // Tangani error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form edit cashier
     */
    public function edit($id)
    {
        // Ambil user login
        $user = auth()->user();

        // Ambil data cashier sesuai id dan milik user tersebut
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        return view('landowner.cashier.edit', compact('cashier'));
    }

    /**
     * Update data cashier
     */
    public function update(Request $request, $id)
    {
        // Ambil user login
        $user = auth()->user();

        // Ambil cashier yang akan diupdate
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone,' . $cashier->id, // unik kecuali dirinya sendiri
            'password' => 'nullable|string|min:6', // boleh kosong
            'password_confirmation' => 'nullable|same:password', // harus sama dengan password
        ]);

        try {
            // Data yang akan diupdate
            $data = [
                'name'  => $request->name,
                'phone' => $request->phone,
            ];

            // Jika password diisi, maka update password
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update data cashier
            $cashier->update($data);

            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus data cashier
     */
    public function destroy($id)
    {
        // Ambil user login
        $user = auth()->user();

        // Ambil cashier sesuai id dan milik user tersebut
        $cashier = User::where('role', 'cashier')
                       ->where('created_by', $user->id)
                       ->findOrFail($id);

        try {
            // Hapus data cashier
            $cashier->delete();

            return redirect()->route('landowner.cashier.index')
                ->with('success', 'Cashier berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}