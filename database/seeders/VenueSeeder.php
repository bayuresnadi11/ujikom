<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Landowner User if not exists
        $landowner = User::firstOrCreate(
            ['phone' => '08123456789'],
            [
                'name' => 'Budi Landowner',
                'password' => Hash::make('password'),
                'role' => 'landowner',
                'business_name' => 'Budi Sports Center',
                'address' => 'Jl. Olahraga No. 1',
                'gender' => 'male',
            ]
        );

        // 2. Create Categories
        $categoriesData = [
            ['category_name' => 'Futsal', 'logo' => 'categories/futsal.png', 'description' => 'Permainan bola yang dimainkan oleh dua tim, yang masing-masing beranggotakan lima orang.'],
            ['category_name' => 'Badminton', 'logo' => 'categories/badminton.png', 'description' => 'Suatu olahraga raket yang dimainkan oleh dua orang (untuk tunggal) atau dua pasangan (untuk ganda) yang saling berlawanan.'],
            ['category_name' => 'Basket', 'logo' => 'categories/basketball.png', 'description' => 'Olahraga bola berkelompok yang terdiri atas dua tim beranggotakan masing-masing lima orang yang saling bertanding mencetak poin.'],
            ['category_name' => 'Mini Soccer', 'logo' => 'categories/minisoccer.png', 'description' => 'Versi mini dari sepak bola, biasanya dimainkan di lapangan yang lebih kecil dengan jumlah pemain yang lebih sedikit.'],
            ['category_name' => 'Tenis', 'logo' => 'categories/tennis.png', 'description' => 'Olahraga yang biasanya dimainkan antara dua pemain atau antara dua pasangan masing-masing dua pemain.'],
            ['category_name' => 'Voli', 'logo' => 'categories/volleyball.png', 'description' => 'Permainan olahraga yang dimainkan oleh dua grup berlawanan. Masing-masing grup memiliki enam orang pemain.'],
        ];

        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $category = Category::firstOrCreate(
                ['category_name' => $categoryData['category_name']],
                [
                    'logo' => $categoryData['logo'],
                    'description' => $categoryData['description']
                ]
            );
            $categories[$category->category_name] = $category;
        }

        // 3. Create Venues
        $venuesData = [
            [
                'venue_name' => 'Mega Futsal Arena',
                'category' => 'Futsal',
                'location' => 'Jakarta Selatan',
                'description' => 'Lapangan futsal standar internasional dengan rumput sintetis berkualitas tinggi. Fasilitas lengkap termasuk shower, loker, dan kantin.',
                'rating' => 4.8,
                'photo' => 'venues/futsal-arena.jpg',
            ],
            [
                'venue_name' => 'Gor Bulutangkis Juara',
                'category' => 'Badminton',
                'location' => 'Jakarta Timur',
                'description' => 'Gedung olahraga khusus bulutangkis dengan 6 lapangan karpet vinil. Pencahayaan standar turnamen dan sirkulasi udara yang baik.',
                'rating' => 4.5,
                'photo' => 'venues/badminton-hall.jpg',
            ],
            [
                'venue_name' => 'Hoops Basketball Court',
                'category' => 'Basket',
                'location' => 'Jakarta Pusat',
                'description' => 'Lapangan basket indoor dengan lantai kayu parket. Tersedia penyewaan bola dan ring yang bisa diatur tingginya.',
                'rating' => 4.7,
                'photo' => 'venues/basketball-court.jpg',
            ],
            [
                'venue_name' => 'Galaxy Mini Soccer',
                'category' => 'Mini Soccer',
                'location' => 'Tangerang Selatan',
                'description' => 'Lapangan mini soccer 7v7 dengan rumput sintetis FIFA standard. Parkir luas dan cocok untuk sparring malam hari.',
                'rating' => 4.9,
                'photo' => 'venues/minisoccer-field.jpg',
            ],
            [
                'venue_name' => 'Tennis Club Senayan',
                'category' => 'Tenis',
                'location' => 'Jakarta Pusat',
                'description' => 'Lapangan tenis outdoor hard court. Suasana asri dan nyaman untuk latihan pagi atau sore.',
                'rating' => 4.6,
                'photo' => 'venues/tennis-court.jpg',
            ],
            [
                'venue_name' => 'Volley Center',
                'category' => 'Voli',
                'location' => 'Jakarta Barat',
                'description' => 'Lapangan voli indoor dengan lantai interlock. Cocok untuk latihan tim maupun pertandingan persahabatan.',
                'rating' => 4.4,
                'photo' => 'venues/volley-court.jpg',
            ],
            [
                'venue_name' => 'Futsal Ceria',
                'category' => 'Futsal',
                'location' => 'Depok',
                'description' => 'Lapangan futsal ekonomis dengan fasilitas standar. Cocok untuk pelajar dan mahasiswa.',
                'rating' => 4.2,
                'photo' => 'venues/futsal-ceria.jpg',
            ],
        ];

        foreach ($venuesData as $venueData) {
            $categoryName = $venueData['category'];
            
            Venue::updateOrCreate(
                ['venue_name' => $venueData['venue_name']], // Attributes to find a record
                [ // Data to update or create
                    'location' => $venueData['location'],
                    'description' => $venueData['description'],
                    'rating' => $venueData['rating'],
                    'photo' => $venueData['photo'],
                    'created_by' => $landowner->id,
                    'category_id' => $categories[$categoryName]->id,
                ]
            );
        }
    }
}