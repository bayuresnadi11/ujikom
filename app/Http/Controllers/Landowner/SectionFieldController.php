<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\VenueSection;
use App\Models\Venue;
use Illuminate\Http\Request;

class SectionFieldController extends Controller
{
    // Index, Create, Edit methods removed as they are integrated into Venue Index


    public function store(Request $request)
    {
        $request->validate([
            'venue_id'     => 'required|exists:venues,id',
            'section_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
        ]);

        // Logika Sekuritas & Otorisasi: Mencegah serangan kerentanan objek langsung (IDOR)
        // dengan mem-validasi kembali secara eksplisit bahwa `venue_id` yang di-submit dari klien
        // adalah benar-benar dimiliki oleh session user yang aktif.
        $venue = Venue::where('id', $request->venue_id)
            ->where('created_by', auth()->id())
            ->firstOrFail();

        VenueSection::create([
            'venue_id'     => $venue->id,
            'section_name' => $request->section_name,
            'description'  => $request->description,
        ]);

        return redirect()->back()
            ->with('success', 'Section berhasil ditambahkan.');
    }

    // Edit method removed


    public function update(Request $request, $id)
    {
        $section = VenueSection::with('venue')->findOrFail($id);

        // Validasi Otorisasi Relasional Bersarang: Seseorang tidak boleh mengedit data Section
        // apabila Induk Venue dari Section tersebut bukanlah miliknya.
        if ($section->venue->created_by !== auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'venue_id'     => 'required|exists:venues,id',
            'section_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
        ]);

        // Validasi venue baru milik user
        Venue::where('id', $request->venue_id)
            ->where('created_by', auth()->id())
            ->firstOrFail();

        $section->update([
            'venue_id'     => $request->venue_id,
            'section_name' => $request->section_name,
            'description'  => $request->description,
        ]);

        return redirect()->back()
            ->with('success', 'Section berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $section = VenueSection::with('venue')->findOrFail($id);

        if ($section->venue->created_by !== auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $venueId = $section->venue_id;
        $section->delete();

        return redirect()->back()
            ->with('success', 'Section berhasil dihapus.');
    }
}
