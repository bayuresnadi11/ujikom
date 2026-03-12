<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venue;
use App\Models\Category;

class ExploreController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('category_name')->get();

        $venues = Venue::with('category')
            ->orderByDesc('rating')
            ->paginate(5);

        return view('buyer.explore.index', compact('categories', 'venues'));
    }

    public function map()
    {
        $user = Auth::user();

        return view('buyer.explore.map', [
            'title' => 'Peta Lapangan',
            'user' => $user
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category');

        // Validate minimum search length
        if (!$request->has('category') && strlen($query) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Kata kunci terlalu pendek'
            ], 400);
        }

        try {
            // Search in venue name, location, and category name
            $venuesQuery = Venue::with('category');

            if ($category && $category !== 'Semua') {
                $venuesQuery->whereHas('category', function($q) use ($category) {
                    $q->where('category_name', $category);
                });
            }

            if ($query) {
                $venuesQuery->where(function($q) use ($query) {
                    $q->where('venue_name', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%")
                      ->orWhereHas('category', function($categoryQuery) use ($query) {
                          $categoryQuery->where('category_name', 'LIKE', "%{$query}%");
                      });
                });
            }

            $venues = $venuesQuery->orderBy('rating', 'desc')
                ->get()
                ->map(function($venue) {
                    return [
                        'id' => $venue->id,
                        'venue_name' => $venue->venue_name,
                        'location' => $venue->location,
                        'photo' => $venue->photo,
                        'rating' => $venue->rating ?? 0,
                        'category_name' => $venue->category->category_name ?? 'Tanpa Kategori',
                        'description' => $venue->description,
                    ];
                });

            return response()->json([
                'success' => true,
                'venues' => $venues,
                'count' => $venues->count(),
                'query' => $query
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
