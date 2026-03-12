<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VenueSection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::with(['category', 'photos', 'venueSections'])
            ->withCount(['venueSections', 'bookings'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $categories = Category::all();
        
        return view('landowner.venue.index', compact('venues', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('landowner.venue.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Simpan foto pertama sebagai foto utama (deprecated tapi diisi untuk backward compatibility)
            $mainPhotoPath = '';
            if ($request->hasFile('photos')) {
                $mainPhotoPath = $request->file('photos')[0]->store('venues', 'public');
            }

            // Simpan venue
            $venue = Venue::create([
                'created_by' => Auth::id(),
                'category_id' => $request->category_id,
                'venue_name' => $request->venue_name,
                'description' => $request->description,
                'location' => $request->location,
                'rating' => $request->rating,
                'photo' => $mainPhotoPath, // Legacy support
            ]);

            // Simpan semua foto ke table venue_photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    // Jika index 0, kita sudah punya pathnya, jika tidak upload baru
                    $path = ($index === 0) ? $mainPhotoPath : $photo->store('venues', 'public');
                    
                    \App\Models\VenuePhoto::create([
                        'venue_id' => $venue->id,
                        'photo_path' => $path
                    ]);
                }
            }

            return redirect()->route('landowner.venue.index')->with('success', 'Venue berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan venue: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $venue = Venue::with(['category', 'photos', 'venueSections'])
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        return view('landowner.venue.show', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $venue = Venue::with('photos') // Eager load photos
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();
            
        $categories = Category::all();

        return view('landowner.venue.edit', compact('venue', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $venue = Venue::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $request->validate([
            'venue_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'new_photos' => 'nullable|array',
            'new_photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = [
                'category_id' => $request->category_id,
                'venue_name' => $request->venue_name,
                'description' => $request->description,
                'location' => $request->location,
                'rating' => $request->rating,
            ];

            // Tambah foto baru jika ada
            if ($request->hasFile('new_photos')) {
                foreach ($request->file('new_photos') as $photo) {
                    $photoPath = $photo->store('venues', 'public');
                    
                    \App\Models\VenuePhoto::create([
                        'venue_id' => $venue->id,
                        'photo_path' => $photoPath
                    ]);
                }
            }
            
            // Hapus foto yang dipilih untuk dihapus
            if ($request->filled('deleted_photos')) {
                $deletedIds = explode(',', $request->deleted_photos);
                foreach ($deletedIds as $photoId) {
                    $photo = \App\Models\VenuePhoto::where('id', $photoId)->where('venue_id', $venue->id)->first();
                    if ($photo) {
                        if (Storage::disk('public')->exists($photo->photo_path)) {
                            Storage::disk('public')->delete($photo->photo_path);
                        }
                        $photo->delete();
                    }
                }
            }

            // Update main photo jika perlu (ambil foto pertama yang tersisa)
            $firstPhoto = $venue->photos()->first();
            if ($firstPhoto) {
                $data['photo'] = $firstPhoto->photo_path;
            } elseif ($venue->photos()->count() == 0) {
                 // Warning: Venue without photo
                 $data['photo'] = null;
            }

            $venue->update($data);

            return redirect()->route('landowner.venue.index')->with('success', 'Venue berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $venue = Venue::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();

            // Hapus semua foto fisik
            foreach ($venue->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
            }
            
            // Legacy photo check
            if ($venue->photo && Storage::disk('public')->exists($venue->photo)) {
                // Check if not deleted by loop above (duplicate path logic?)
                // Storage delete is safe to call even if deleted
                Storage::disk('public')->delete($venue->photo);
            }

            $venue->delete();

            return redirect()->route('landowner.venue.index')->with('success', 'Venue berhasil dihapus');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Venue tidak ditemukan atau tidak memiliki akses']);
        }
    }
}