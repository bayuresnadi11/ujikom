<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommunityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ListAdminController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RoleRequestController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\BookingController as BuyerBookingController;
use App\Http\Controllers\Buyer\BuyerVenueController;
use App\Http\Controllers\Buyer\ChatController as BuyerChatController;
use App\Http\Controllers\Buyer\CommunitiesController;
use App\Http\Controllers\Buyer\CommunityMemberController;
use App\Http\Controllers\Buyer\DepositController;
use App\Http\Controllers\Buyer\ExploreController as BuyerExploreController;
use App\Http\Controllers\Buyer\HomeController as BuyerHomeController;
use App\Http\Controllers\Buyer\MenuController as BuyerMenuController;
use App\Http\Controllers\Buyer\MyPlayTogetherController;
use App\Http\Controllers\Buyer\NotificationController;
use App\Http\Controllers\Buyer\PhoneChangeController as BuyerPhoneChangeController;
use App\Http\Controllers\Buyer\PlayTogetherController;
use App\Http\Controllers\Buyer\ProfileController as BuyerProfileController;
use App\Http\Controllers\Cashier\CustomerController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\ReceiptController;
use App\Http\Controllers\Cashier\ScanController;
use App\Http\Controllers\Cashier\TiketController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Landowner\ChatController as LandownerChatController;
use App\Http\Controllers\Landowner\DashboardController as LandownerDashboardController;
use App\Http\Controllers\Landowner\HomeController as LandownerHomeController;
use App\Http\Controllers\Landowner\MenuController as LandownerMenuController;
use App\Http\Controllers\Landowner\PhoneChangeController as LandownerPhoneChangeController;
use App\Http\Controllers\Landowner\ProfileController as LandownerProfileController;
use App\Http\Controllers\Landowner\ReportController;
use App\Http\Controllers\Landowner\ScheduleController;
use App\Http\Controllers\Landowner\SectionFieldController;
use App\Http\Controllers\Landowner\TipsController;
use App\Http\Controllers\Landowner\VenueController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/callback', [BuyerBookingController::class, 'midtransCallback']);

// Tambahkan route emergency
Route::get('/landowner/withdraw-saldo', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawIndex'])
    ->middleware(['auth', 'role:landowner'])
    ->name('landowner.withdraw.saldo');
          Route::get('/riwayat-penarikan', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawHistory'])
            ->middleware(['auth', 'role:landowner'])
            ->name('landowner.withdraw.riwayat');

            Route::get('/landowner/withdraw-otp', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpForm'])
    ->middleware(['auth', 'role:landowner'])
    ->name('landowner.withdraw.otp');

Route::post('/landowner/withdraw-otp', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpSubmit'])
    ->middleware(['auth', 'role:landowner'])
    ->name('landowner.withdraw.otp.submit');

Route::post('/landowner/withdraw-otp-resend', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpResend'])
    ->middleware(['auth', 'role:landowner'])
    ->name('landowner.withdraw.otp.resend');
/*


|--------------------------------------------------------------------------
| GUEST ROUTES (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
// Tambahkan di luar semua group, biasanya di akhir file routes/web.php

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'submitLogin'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'submitRegister'])->name('register.submit');

    Route::get('/otp', [AuthController::class, 'formOtp'])->name('otp.form');
    Route::post('/otp', [AuthController::class, 'submitOtp'])->name('otp.submit');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

    Route::get('/forgot-password', [ResetPasswordController::class, 'showForgetForm'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.whatsapp');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| GLOBAL NOTIFICATION ROUTES (Untuk semua role yang login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    // Route untuk mendapatkan jumlah notifikasi yang belum dibaca (AJAX)
    Route::get('/unread-count', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
            'success' => true,
        ]);
    })->name('unread-count');

    // Route untuk mendapatkan notifikasi terbaru (AJAX)
    Route::get('/latest', function () {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'success' => true,
        ]);
    })->name('latest');

    // Route untuk menandai notifikasi sebagai dibaca (AJAX)
    Route::post('/{id}/mark-read', function ($id) {
        $user = auth()->user();
        $notification = $user->notifications()->find($id);

        if ($notification && ! $notification->read_at) {
            $notification->update(['read_at' => now()]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    })->name('mark-read');

    // Route untuk menandai semua notifikasi sebagai dibaca (AJAX)
    Route::post('/mark-all-read', function () {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    })->name('mark-all-read');
});

/*
|--------------------------------------------------------------------------
|CASHIER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::get('/', [CashierController::class, 'index'])->name('index');
        // Route::get('/ticket', [CashierController::class, 'ticket'])->name('ticket'); // Removed duplicate
        // Halaman scan
        // Halaman scan
        Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');

        Route::post('/customers/store', [CustomerController::class, 'store'])
            ->name('customers.store');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');

        // API validasi scan (POST request dari JavaScript)
        Route::post('/scan/validate', [ScanController::class, 'validateTicket'])->name('scan.validate');
        Route::get('/queue', [CashierController::class, 'queue'])->name('queue');

        // AJAX endpoints
        Route::get('/dashboard', [CashierDashboardController::class, 'index'])
            ->name('dashboard.index');
        Route::get('/venues/search', [CashierController::class, 'search'])
            ->name('venues.search');
        Route::get('/venues/{venueId}/sections', [CashierController::class, 'getSections'])
            ->name('venues.sections');

        // 🔹 TAMPIL SEMUA VENUE
        Route::get('/display/venues', [App\Http\Controllers\Cashier\DisplayController::class, 'venues'])
            ->name('display.venues');

        // 🔹 TAMPIL SEMUA SECTION
        Route::get('/display/sections', [App\Http\Controllers\Cashier\DisplayController::class, 'sections'])
            ->name('display.sections');

        // 🔹 DISPLAY JADWAL PER SECTION
        Route::get('/display/sections/{section}', [App\Http\Controllers\Cashier\DisplayController::class, 'sectionDetail'])
            ->name('display.sections.show');

        // 🔹 TAMPIL SECTIONS PER VENUE
        Route::get('/display/venues/{venue}/sections', [App\Http\Controllers\Cashier\DisplayController::class, 'venueSections'])
            ->name('display.venues.sections');

        Route::get('/dashboard', [CashierDashboardController::class, 'index'])
            ->name('dashboard.index');
        Route::get('/venues/search', [CashierController::class, 'search'])
            ->name('venues.search');
        Route::get('/venues/{venueId}/sections', [CashierController::class, 'getSections'])
            ->name('venues.sections');
        // Route for getting schedules
        Route::get('/sections/{sectionId}/schedules', [CashierController::class, 'getSchedules'])
            ->name('sections.schedules');
        // Route for searching buyers
        Route::get('/buyers/search', [CashierController::class, 'searchBuyers'])
            ->name('buyers.search');

        Route::get('/ticket', [TiketController::class, 'index'])
            ->name('cashier.ticket.index');

        Route::post('/process-payment', [CashierController::class, 'processPayment'])->name('payment.process');
        Route::post('/payment/update-status', [CashierController::class, 'updatePaymentStatus'])->name('payment.update-status');
        Route::post('/midtrans/callback', [CashierController::class, 'midtransCallback'])->name('payment.callback');
        Route::get('/payment/finish', [CashierController::class, 'paymentFinish'])->name('payment.finish');

        // Receipt routes
        Route::get('/receipt/{transactionCode}', [ReceiptController::class, 'show'])
        ->name('receipt.show');
        // // ✅ NOTIFICATION ROUTES untuk cashier
        // Route::prefix('notifications')->name('notifications.')->group(function () {
        //     Route::get('/', [\App\Http\Controllers\Cashier\NotificationController::class, 'index'])->name('index');
        //     Route::get('/unread-count', [\App\Http\Controllers\Cashier\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        //     Route::post('/mark-all-read', [\App\Http\Controllers\Cashier\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        // });
    });

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $stats = [
        'venues_count' => \App\Models\Venue::count(),
        'users_count' => \App\Models\User::where('role', 'buyer')->count(),
        'bookings_count' => \App\Models\Booking::count(),
        'total_rating' => \App\Models\Venue::avg('rating') ?? 0,
    ];

    $venues = \App\Models\Venue::with('category')->orderByDesc('rating')->limit(5)->get();
    
    $communities = \App\Models\Community::with('category')->withCount(['members', 'playTogetherEvents'])->limit(5)->get();
    
    $categories = \App\Models\Category::all();

    return view('welcome', compact('stats', 'venues', 'communities', 'categories'));
})->name('welcome');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/communities/join', [CommunitiesController::class, 'join'])->name('communities.join');
Route::get('/main-bareng', [PlayTogetherController::class, 'index'])->name('guest.main_bareng.index');
Route::get('/main-bareng/{id}', [PlayTogetherController::class, 'show'])->name('guest.main_bareng.show');
Route::get('/sparring', function () {
    return view('buyer.sparring.index'); // Buat view sederhana
})->name('guest.sparring.index');
Route::get('/venue', [BuyerVenueController::class, 'index'])->name('guest.venue.index');
Route::get('/venue/{id}', [BuyerVenueController::class, 'show'])->name('guest.venue.show');
Route::get('/profile', [BuyerProfileController::class, 'profile'])->name('guest.profile');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Role Approved Notification
    Route::get('/role-approved', function () {
        return view('approved-role-change');
    })->name('role.approved');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // Chat dengan conversation tertentu
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');

    // Mulai chat baru dengan user tertentu
    Route::get('/chat/start/{user}', [ChatController::class, 'create'])->name('chat.create');
    // ==================== ROLE SWITCHING ROUTES (Session-based, No Logout!) ====================
    Route::post('/switch-to-landowner', [RoleController::class, 'switchToLandowner'])->name('switch.to.landowner');
    Route::post('/switch-to-buyer', [RoleController::class, 'switchToBuyer'])->name('switch.to.buyer');
    
    // Landowner request submission (buyer only)
    Route::middleware('role:buyer')->group(function() {
        Route::post('/landowner/request', [RoleController::class, 'submitLandownerRequest'])->name('buyer.landowner.request');
    });
    
    // Admin approval/rejection
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function() {
        Route::post('/role-requests/{id}/approve', [RoleController::class, 'approveRequest'])->name('role.approve');
        Route::post('/role-requests/reject', [RoleController::class, 'rejectRequest'])->name('role.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

            Route::resource('category', CategoryController::class);
            Route::resource('community', CommunityController::class);

            // PERBAIKAN: Routing daftar_admin yang benar
            Route::prefix('daftar_admin')->name('daftar_admin.')->group(function () {
                Route::get('/', [ListAdminController::class, 'index'])->name('index');
                Route::post('/', [ListAdminController::class, 'store'])->name('store');
                Route::get('/{id}', [ListAdminController::class, 'show'])->name('show');
                Route::put('/{id}', [ListAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [ListAdminController::class, 'destroy'])->name('destroy');
            });

              Route::get('/pencairan', [App\Http\Controllers\Admin\PencairanController::class, 'index'])
            ->name('pencairan.index');

        Route::get('/pencairan/{id}/edit', [App\Http\Controllers\Admin\PencairanController::class, 'edit'])
            ->name('pencairan.edit');

        Route::put('/pencairan/{id}', [App\Http\Controllers\Admin\PencairanController::class, 'update'])
            ->name('pencairan.update');
            Route::get('/check-current-user', [ListAdminController::class, 'checkCurrentUser'])->name('check_current_user');

            Route::resource('user', UserController::class);

            Route::prefix('setting')->name('setting.')->group(function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::post('/', [SettingController::class, 'store'])->name('store');
                Route::put('/', [SettingController::class, 'update'])->name('update');
            });

            Route::get('/role-request', [RoleRequestController::class, 'index'])->name('rolerequest.index');
            Route::post('/role-request/approve', [RoleRequestController::class, 'approve'])->name('rolerequest.approve');
            Route::post('/role-request/reject', [RoleRequestController::class, 'reject'])->name('rolerequest.reject');

            // PERBAIKAN: Routing profile yang benar - HANYA 'admin.profile.*'
            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', [AdminProfileController::class, 'index'])->name('index');
                Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
                Route::post('/update', [AdminProfileController::class, 'update'])->name('update');

                Route::get('/phone', [AdminProfileController::class, 'phone'])->name('phone');
                Route::post('/phone/send-otp', [AdminProfileController::class, 'sendPhoneOtp'])->name('phone.send-otp');
                Route::get('/phone/verify-otp', [AdminProfileController::class, 'showVerifyPhoneOtp'])->name('verify-phone-otp');
                Route::post('/phone/verify-otp', [AdminProfileController::class, 'verifyPhoneOtp'])->name('phone.verify-otp');
                Route::post('/phone/resend-otp', [AdminProfileController::class, 'resendPhoneOtp'])->name('phone.resend-otp');

                Route::post('/update-password', [AdminProfileController::class, 'updatePassword'])->name('update-password');
            });

            // ✅ ADMIN NOTIFICATION ROUTES
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
                Route::get('/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::get('/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('show');
                Route::post('/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-read');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'delete'])->name('delete');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | LANDOWNER ROUTES (FULL & FIXED)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:landowner')
        ->prefix('landowner')
        ->name('landowner.')
        ->group(function () {
            // Home, Menu, Dashboard
            Route::get('/home', [LandownerHomeController::class, 'index'])->name('home');
            Route::get('/menu', [LandownerMenuController::class, 'index'])->name('menu');
            Route::get('/chat', [LandownerChatController::class, 'index'])->name('chat');
            Route::get('/dashboard', [LandownerDashboardController::class, 'index'])->name('dashboard');

            // Venue & Lapangan
            Route::resource('venue', VenueController::class);
            Route::resource('lapangan', VenueController::class);

            // Section Lapangan
            Route::resource('section-lapangan', SectionFieldController::class);

            Route::resource('schedule-lapangan', ScheduleController::class);

            Route::get('report-lapangan/{booking}/pdf', [ReportController::class, 'exportPdf'])->name('report-lapangan.pdf');
            Route::resource('report-lapangan', ReportController::class);
            Route::get('report-lapangan/bulanan/{month}',[ReportController::class, 'exportMonthlyPdf'])->name('report-lapangan.monthly-pdf');

            // Alias route to fix 'landowner.booking.index' not defined error
            Route::get('/bookings', [ReportController::class, 'index'])->name('booking.index');

            // API ambil section berdasarkan lapangan (UNTUK FETCH JS)
            Route::get(
                '/lapangan/{lapangan}/sections',
                [ScheduleController::class, 'getSections']
            )->name('lapangan.sections');

            // Tips management routes
            Route::prefix('tips')->name('tips.')->group(function () {
                Route::get('/', [TipsController::class, 'index'])->name('index');
                Route::get('/category/{category}', [TipsController::class, 'getByCategory'])->name('byCategory');
                Route::post('/bookmark', [TipsController::class, 'bookmark'])->name('bookmark');
                Route::delete('/bookmark/{tip}', [TipsController::class, 'removeBookmark'])->name('removeBookmark');
            });

            // ================================
            // JADWAL & BOOKING ROUTES - LENGKAP
            // ================================

            // Tambahkan routes berikut ke dalam Route::middleware('role:landowner')->prefix('landowner')->name('landowner.')->group()

            // Tambahkan di dalam Route::middleware('role:landowner')->prefix('landowner')->name('landowner.')->group()
            Route::resource('booking', \App\Http\Controllers\Landowner\BookingController::class)->only(['index', 'show']);
            Route::resource('cashier', \App\Http\Controllers\Landowner\UsersCashierController::class);
            // Schedule Management Routes
            Route::prefix('schedule')->name('schedule.')->group(function () {
                // Main index page
                Route::get('/', [ScheduleController::class, 'index'])->name('index');

                // Get sections by venue ID
                Route::get('/sections/{venueId}', [ScheduleController::class, 'getSections'])->name('sections');

                // Get schedules by section ID
                Route::get('/jadwal/{sectionId}', [ScheduleController::class, 'getJadwalBySection'])->name('jadwal');

                // Generate bulk schedules
                Route::post('/generate', [ScheduleController::class, 'generateJadwalBulk'])->name('generate');

                // Store single schedule
                Route::post('/store', [ScheduleController::class, 'store'])->name('store');

                // Update schedule
                Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('update');

                // Delete single schedule
                Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');

                // Delete multiple schedules
                Route::post('/delete-multiple', [ScheduleController::class, 'destroyMultiple'])->name('delete-multiple');
            });

            // Tambahkan di dalam Route::middleware('role:landowner')->prefix('landowner')->name('landowner.')->group()
            Route::resource('cashier', \App\Http\Controllers\Landowner\UsersCashierController::class);
            // Schedule Management Routes
            Route::prefix('schedule')->name('schedule.')->group(function () {
                // Main index page
                Route::get('/', [ScheduleController::class, 'index'])->name('index');

                // PAGES ROUTES
                Route::get('/create', [ScheduleController::class, 'create'])->name('create');
                Route::get('/generate', [ScheduleController::class, 'generate'])->name('generate');
                Route::get('/{id}/edit', [ScheduleController::class, 'edit'])->name('edit');

                // AJAX/API ROUTES
                Route::get('/sections/{venueId}', [ScheduleController::class, 'getSections'])->name('sections');
                Route::get('/jadwal/{sectionId}', [ScheduleController::class, 'getJadwalBySection'])->name('jadwal');

                // ACTIONS
                Route::post('/generate', [ScheduleController::class, 'generateJadwalBulk'])->name('generateItem'); // Renamed to avoid name collision if any, but actually POST vs GET shares name is fine in Laravel but clarity is better. Wait, generate.blade.php form likely points to 'generate' POST.
                // Actually, if I name GET 'generate' and POST 'generate', Laravel handles it by method.
                // unique names are safer.
                Route::post('/generate-bulk', [ScheduleController::class, 'generateJadwalBulk'])->name('generate.bulk');

                Route::post('/store', [ScheduleController::class, 'store'])->name('store');

                // Update schedule
                Route::put('/{id}', [ScheduleController::class, 'update'])->name('update'); // Standard URL /landowner/schedule/{id}

                // Delete single schedule
                Route::delete('/{id}', [ScheduleController::class, 'destroy'])->name('destroy'); // Standard URL /landowner/schedule/{id}

                // Delete multiple schedules
                Route::post('/delete-multiple', [ScheduleController::class, 'destroyMultiple'])->name('delete-multiple');

                // Update batch status
                Route::post('/update-batch-status', [ScheduleController::class, 'updateBatchStatus'])->name('update-batch-status');

                // Get schedule detail (JSON)
                Route::get('/{id}/detail', [ScheduleController::class, 'show'])->name('show');

                // Get schedule stats
                Route::get('/stats/{sectionId}', [ScheduleController::class, 'getStats'])->name('stats');
            });

            Route::prefix('jadwal')->name('jadwal.')->group(function () {
                // Halaman utama jadwal
                Route::get('/', [ScheduleController::class, 'index'])->name('index');

                // API untuk mengambil section berdasarkan field/venue
                Route::get('/venue/{fieldId}/sections', [SectionFieldController::class, 'getSectionsByField'])
                    ->name('venue.sections');

                // SCHEDULE ROUTES
                Route::prefix('schedule')->name('schedule.')->group(function () {
                    Route::get('/', [ScheduleController::class, 'index'])->name('index');
                    Route::get('/sections/{venueId}', [ScheduleController::class, 'getSections'])->name('sections');
                    Route::get('/jadwal/{sectionId}', [ScheduleController::class, 'getJadwalBySection'])->name('jadwal');
                    Route::post('/store', [ScheduleController::class, 'store'])->name('store');
                    Route::get('/detail/{id}', [ScheduleController::class, 'getScheduleDetail'])->name('detail');
                    Route::get('/show/{id}', [ScheduleController::class, 'show'])->name('show');
                    Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('destroy');
                    Route::post('/delete-multiple', [ScheduleController::class, 'destroyMultiple'])->name('delete.multiple');
                });

                // Profile routes
                Route::get('/profile', [LandownerProfileController::class, 'index'])->name('profile');
                Route::post('/profile/update', [LandownerProfileController::class, 'update'])->name('profile.update');
                Route::post('/profile/change-password', [LandownerProfileController::class, 'changePassword'])->name('profile.change-password');
                Route::get('/profile/stats', [LandownerProfileController::class, 'getStats'])->name('profile.stats');

                // Placeholder routes
                Route::view('/laporan', 'landowner.laporan.index')->name('laporan');

                // Switch back to buyer
                Route::get('/switch-back', function () {
                    session()->forget('temp_role');

                    return redirect()->route('buyer.home.index');
                })->name('switch.back');
            });

            Route::middleware('auth')
                ->post('/landowner/schedule/generate', [ScheduleController::class, 'generateJadwalBulk'])
                ->name('landowner.schedule.generate');
            Route::get('/jadwal-booking', [ScheduleController::class, 'index'])->name('jadwal-booking');

            // Deposit routes
       // TAMBAHKAN ROUTE INI DI DALAM Route::middleware('role:landowner')->prefix('landowner')->name('landowner.')->group()
// TEPAT DI DALAM BAGIAN DEPOSIT ROUTES

// Landowner Deposit routes


            // Place
            // Profile
            Route::get('/profile', [LandownerProfileController::class, 'index'])->name('profile');
            Route::get('/profile/edit', [LandownerProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile/update', [LandownerProfileController::class, 'update'])->name('profile.update');
            Route::post('/profile/change-password', [LandownerProfileController::class, 'changePassword'])->name('profile.change-password');
            Route::get('/profile/stats', [LandownerProfileController::class, 'getStats'])->name('profile.stats');
            
            // Background update route
            Route::post('/profile/update-background', [LandownerProfileController::class, 'updateBackground'])
                ->name('profile.background.update');


        // Landowner Deposit routes
// Landowner Deposit routes
// TAMBAHKAN ROUTE INI DI DALAM Route::middleware('role:landowner')->prefix('landowner')->name('landowner.')->group()
// TEPAT DI DALAM BAGIAN DEPOSIT ROUTES

// Landowner Deposit routes
Route::prefix('deposit')->name('deposit.')->group(function () {
    Route::get('/', [App\Http\Controllers\Landowner\DepositController::class, 'index'])->name('index');
    Route::get('/history', [App\Http\Controllers\Landowner\DepositController::class, 'getHistory'])->name('history');
    Route::get('/create', [App\Http\Controllers\Landowner\DepositController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Landowner\DepositController::class, 'store'])->name('store');
    Route::get('/{deposit}', [App\Http\Controllers\Landowner\DepositController::class, 'show'])->name('show');
    Route::post('/{deposit}/confirm', [App\Http\Controllers\Landowner\DepositController::class, 'confirm'])->name('confirm');
    Route::post('/{deposit}/cancel', [App\Http\Controllers\Landowner\DepositController::class, 'cancel'])->name('cancel');
    
    // Withdraw routes
    Route::get('/withdraw', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawIndex'])->name('withdraw.index');
    Route::post('/withdraw', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawStore'])->name('withdraw.store');
    Route::get('/withdraw/{withdrawal}', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawShow'])->name('withdraw.show');
    Route::get('/withdraw-history', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawHistory'])->name('withdraw.history');
    
    // ROUTE OTP UNTUK WITHDRAW - TAMBAHKAN INI
    Route::get('/withdraw-otp', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpForm'])->name('withdraw.otp');
    Route::post('/withdraw-otp', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpSubmit'])->name('withdraw.otp.submit');
    Route::post('/withdraw-otp-resend', [App\Http\Controllers\Landowner\DepositController::class, 'withdrawOtpResend'])->name('withdraw.otp.resend');
});

            // Placeholder
            Route::view('/laporan', 'landowner.laporan')->name('laporan');

            // change phone number
            Route::post('/profile/change-phone', [LandownerPhoneChangeController::class, 'send'])
                ->name('phone.change');
            Route::post('/profile/resend-phone', [LandownerPhoneChangeController::class, 'resend'])
                ->name('phone.resend');
            Route::get('/profile/verify-phone', [LandownerPhoneChangeController::class, 'verify'])
                ->name('phone.verify');
            Route::get('/profile/phone-invalid', function () {
                return view('landowner.profile.phone-invalid');
            })->name('profile.phone-invalid');
            Route::get('/profile/phone-verified', function () {
                return view('landowner.profile.phone-verified');
            })->name('profile.phone-verified');
            Route::post('/profile/cancel-phone', [LandownerPhoneChangeController::class, 'cancel'])
                ->name('phone.cancel');

            // ✅ LANDOWNER NOTIFICATION ROUTES
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Landowner\NotificationController::class, 'index'])->name('index');
                Route::get('/unread-count', [\App\Http\Controllers\Landowner\NotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::post('/mark-all-read', [\App\Http\Controllers\Landowner\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::get('/{id}', [\App\Http\Controllers\Landowner\NotificationController::class, 'show'])->name('show');
                Route::post('/{id}/mark-read', [\App\Http\Controllers\Landowner\NotificationController::class, 'markAsRead'])->name('mark-read');
                Route::delete('/{id}', [\App\Http\Controllers\Landowner\NotificationController::class, 'delete'])->name('delete');
            });

            // Switch back
            Route::get('/switch-back', function () {
                session()->forget('temp_role');

                return redirect()->route('buyer.home');
            })->name('switch.back');
        });

    

    /*
    |--------------------------------------------------------------------------
    | GUEST ROUTES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | GUEST ROUTES (Moved to Public Section)
    |--------------------------------------------------------------------------
    */
    // These routes have been moved to the public section above to avoid auth middleware.

    /*
    |--------------------------------------------------------------------------
    | BUYER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:buyer')
        ->prefix('buyer')
        ->name('buyer.')
        ->group(function () {
            // Profile routes
             Route::get('/profile/pengajuan', [BuyerProfileController::class, 'pengajuan'])->name('profile.pengajuan');
            // ========================================
            // NOTIFICATION ROUTES - LENGKAP
            // ========================================
            Route::prefix('notifications')->name('notifications.')->group(function () {
                // Main notifications page
                Route::get('/', [NotificationController::class, 'index'])->name('index');

                // AJAX/API endpoints
                Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::get('/list', [NotificationController::class, 'getNotifications'])->name('list');

                // Mark notifications
                Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
                Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::post('/{id}/mark-unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');

                // Read and redirect
                Route::get('/{id}/read', [NotificationController::class, 'readAndRedirect'])->name('read');

                // View notification detail
                Route::get('/{id}', [NotificationController::class, 'show'])->name('show');

                // Delete notifications
                Route::delete('/{id}', [NotificationController::class, 'delete'])->name('delete');
                Route::delete('/', [NotificationController::class, 'clearAll'])->name('clear-all');
            });

            Route::prefix('venue')->name('venue.')->group(function () {
                Route::get('/', [BuyerVenueController::class, 'index'])->name('index');
                Route::get('/popular', [BuyerVenueController::class, 'popular'])->name('popular');
                Route::get('/{id}', [BuyerVenueController::class, 'show'])->name('show');
            });

            // Route untuk Sparring (temporary)
            Route::get('/sparring', function () {
                return view('buyer.sparring.index'); // Buat view sederhana
            })->name('sparring.index');

            // ========================================
            // COMMUNITIES ROUTES - REORGANIZED
            // ========================================
            Route::prefix('communities')->name('communities.')->group(function () {
                // Main communities routes
                Route::get('/', [CommunitiesController::class, 'index'])->name('index');
                Route::get('/search-joined', [CommunitiesController::class, 'searchJoined'])->name('search-joined');
                Route::get('/search', [CommunitiesController::class, 'search'])->name('search');
                Route::get('/create', [CommunitiesController::class, 'create'])->name('create');
                Route::post('/store', [CommunitiesController::class, 'store'])->name('store');

                // Specific community routes
                Route::get('/{community}', [CommunitiesController::class, 'show'])->name('show');
                Route::get('/{community}/aktivitas', [CommunitiesController::class, 'aktivitas'])->name('aktivitas');
                Route::get('/{community}/galeri', [CommunitiesController::class, 'galeri'])->name('galeri');
                Route::post('/{community}/galeri', [CommunitiesController::class, 'storeGallery'])->name('galeri.store');
                Route::delete('/{community}/galeri/{gallery}', [CommunitiesController::class, 'destroyGallery'])->name('galeri.destroy');
                Route::get('/{community}/edit', [CommunitiesController::class, 'edit'])->name('edit');
                Route::put('/{community}', [CommunitiesController::class, 'update'])->name('update');
                Route::delete('/{community}', [CommunitiesController::class, 'destroy'])->name('destroy');
                Route::get('/{community}/invite', [CommunitiesController::class, 'invite'])->name('invite');
                Route::get('/{community}/search-users', [CommunitiesController::class, 'searchUsers'])->name('searchUsers');
                Route::post('/{community}/invite-user', [CommunitiesController::class, 'inviteUser'])->name('inviteUser');
                Route::post('/{community}/join', [CommunitiesController::class, 'storeJoin'])->name('join.store');
                Route::post('/{community}/join-invite', [CommunitiesController::class, 'joinInvite'])->name('joinInvite');
                Route::post('/{community}/reject-invite', [CommunitiesController::class, 'rejectInvite'])->name('rejectInvite');
                Route::post('/{community}/leave', [CommunitiesController::class, 'leave'])->name('leave');

                // ========================================
                // TAMBAHAN ROUTE UNTUK INVITE-ANGGOTA - TAMBAHKAN INI
                // ========================================
                Route::post('/{community}/request-rejoin', [CommunitiesController::class, 'requestRejoin'])->name('requestRejoin');

                Route::get('/{community}/invite-anggota', [CommunitiesController::class, 'inviteAnggota'])
                    ->name('invite-anggota');

                // Handle request dari halaman invite-anggota (AJAX)
                Route::post('/{community}/handle-invite-request', [CommunitiesController::class, 'handleInviteRequest'])
                    ->name('handle-invite-request');

                // Undang kembali member yang dikeluarkan (AJAX)
                Route::post('/{community}/reinvite-member/{memberId}', [CommunitiesController::class, 'reinviteMember'])
                    ->name('reinvite-member');

                // Batalkan undangan (AJAX)
                Route::post('/{community}/cancel-invite/{memberId}', [CommunitiesController::class, 'cancelInvite'])
                    ->name('cancel-invite');

                // Kirim email invitation dari halaman invite-anggota (AJAX)
                Route::post('/{community}/send-email-invitation', [CommunitiesController::class, 'sendEmailInvitation'])
                    ->name('send-email-invitation');

                // Generate invite link baru (AJAX)
                Route::post('/{community}/generate-invite-link', [CommunitiesController::class, 'generateInviteLink'])
                    ->name('generate-invite-link');

                // Bulk Invitation Routes
                Route::post('/{community}/invite/draft', [CommunitiesController::class, 'addToDraft'])->name('invite.draft.add');
                Route::get('/{community}/invite/draft', [CommunitiesController::class, 'showDraft'])->name('invite.draft');
                Route::delete('/{community}/invite/draft/{userId}', [CommunitiesController::class, 'removeFromDraft'])->name('invite.draft.remove');
                Route::post('/{community}/invite/send', [CommunitiesController::class, 'sendBulkInvite'])->name('invite.send');

                // Route for community-specific activity detail
                Route::get('/{community}/main-bareng/{id}', [CommunitiesController::class, 'showMainBareng'])->name('main_bareng.show_community');
                // ========================================
                // END TAMBAHAN ROUTE UNTUK INVITE-ANGGOTA
                // ========================================

                // Community management routes
                Route::get('/{community}/requests', [CommunitiesController::class, 'requests'])->name('requests');
                Route::post('/{community}/send-message', [CommunitiesController::class, 'sendCommunityMessage'])->name('send-message');

                // Request approval/rejection
                Route::post('/requests/{request}/approve', [CommunitiesController::class, 'approveRequest'])->name('requests.approve');
                Route::post('/requests/{request}/reject', [CommunitiesController::class, 'rejectRequest'])->name('requests.reject');

                // Membership actions
                Route::post('/membership/approve', [CommunitiesController::class, 'approveMembershipAjax'])->name('membership.approve');
                Route::post('/membership/reject', [CommunitiesController::class, 'rejectMembershipAjax'])->name('membership.reject');

                // ========================================
                // COMMUNITY MEMBERS ROUTES - FIXED
                // ========================================
                Route::prefix('{community}/members')->name('members.')->group(function () {
                    // FIX: Ini adalah route yang diperlukan untuk buyer.communities.members.index
                    Route::get('/', [CommunitiesController::class, 'members'])->name('index');

                    // Member management actions
                    Route::post('/{member}/make-admin', [CommunitiesController::class, 'makeAdmin'])->name('make-admin');
                    Route::post('/{member}/remove-admin', [CommunitiesController::class, 'removeAdmin'])->name('remove-admin');
                    Route::post('/{member}/remove', [CommunitiesController::class, 'removeMember'])->name('remove');
                });

                // Update Background Image
                Route::post('/{community}/update-background', [CommunitiesController::class, 'updateBackground'])->name('update-background');
            });

            // ========================================
            // COMMUNITY MEMBER CONTROLLER ROUTES (Separate controller - Optional/Backup)
            // ========================================
            /*
        Route::prefix('communities/{community}/member')->name('communities.member.')->group(function () {
             Route::get('/', [CommunityMemberController::class, 'index'])->name('index');
             Route::get('/{member}', [CommunityMemberController::class, 'show'])->name('show');
             Route::post('/{member}/make-admin', [CommunityMemberController::class, 'makeAdmin'])->name('make-admin');
             Route::post('/{member}/remove-admin', [CommunityMemberController::class, 'removeAdmin'])->name('remove-admin');
             Route::post('/{member}/remove', [CommunityMemberController::class, 'removeMember'])->name('remove');
             Route::get('/search', [CommunityMemberController::class, 'search'])->name('search');
        });
        */

            // TAMBAHKAN ROUTE INI DI DALAM Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group()
            // Tepat di bawah Route::prefix('booking')->name('booking.')->group()
            Route::get('/booking/{id}/get-payment-data', [BuyerBookingController::class, 'getPaymentData'])
                ->name('booking.get-payment-data');


    Route::prefix('main-bareng')->name('main_bareng.')->group(function () {
    Route::get('/', [PlayTogetherController::class, 'index'])->name('index');
    Route::post('/', [PlayTogetherController::class, 'store'])->name('store');
    
    // EDIT & UPDATE
    Route::get('/{id}/edit', [PlayTogetherController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PlayTogetherController::class, 'update'])->name('update');
    
    // SHOW (detail)
    Route::get('/{id}', [PlayTogetherController::class, 'show'])->name('show');
    
    // JOIN
    Route::post('/{id}/join', [PlayTogetherController::class, 'join'])->name('join');
    // Di dalam Route::prefix('main-bareng')->name('main_bareng.')->group(function () {
Route::post('/{id}/host-join', [PlayTogetherController::class, 'hostJoin'])->name('hostJoin');
    // PROCESS PAYMENT
    Route::post('/{id}/process-payment', [PlayTogetherController::class, 'processPayment'])->name('process_payment');
    
    // CREATE PARTICIPANT AFTER PAYMENT
    Route::post('/create-participant', [PlayTogetherController::class, 'createParticipant'])->name('create_participant');
    
    // CHECK PAYMENT STATUS
    Route::get('/check-payment-status/{orderId}', [PlayTogetherController::class, 'checkPaymentStatus'])->name('check_payment_status');
    
    // DELETE
    Route::delete('/{id}', [PlayTogetherController::class, 'destroy'])->name('destroy');
});

// Deposit routes

// Withdraw routes - UNIQUE ROUTES (tidak dalam prefix deposit)
// HAPUS SEMUA ROUTE DEPOSIT YANG LAMA dan ganti dengan ini:

// Deposit routes
// Deposit routes
Route::get('/deposit', [DepositController::class, 'index'])->name('deposit.index');
Route::get('/deposit/history', [DepositController::class, 'getHistory'])->name('deposit.history');

// Withdraw routes dengan OTP
Route::get('/deposit/withdraw-saldo', [DepositController::class, 'withdrawIndex'])
    ->name('deposit.withdraw.saldo');
    
Route::post('/deposit/withdraw-saldo', [DepositController::class, 'withdrawStore'])
    ->name('deposit.withdraw.store');

// Route OTP Withdraw    
Route::get('/deposit/withdraw-otp', [DepositController::class, 'withdrawOtp'])
    ->name('deposit.withdraw.otp');
    
Route::post('/deposit/withdraw-verify-otp', [DepositController::class, 'withdrawVerifyOtp'])
    ->name('deposit.withdraw.verify-otp');
    
Route::post('/deposit/withdraw-resend-otp', [DepositController::class, 'withdrawResendOtp'])
    ->name('deposit.withdraw.resend-otp');
    
Route::get('/riwayat-penarikan', [DepositController::class, 'withdrawHistory'])
    ->name('deposit.withdraw.riwayat');
// Main Bareng Saya Routes
 Route::prefix('main-bareng-saya')->name('main_bareng_saya.')->group(function () {

        Route::get('/', [MyPlayTogetherController::class, 'index'])->name('index');
        Route::get('/{id}', [MyPlayTogetherController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MyPlayTogetherController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MyPlayTogetherController::class, 'update'])->name('update');
        Route::delete('/{id}', [MyPlayTogetherController::class, 'destroy'])->name('destroy');
        
        // NEW ROUTE FOR RECALCULATE PRICE - TAMBAHKAN INI
        Route::post('/{id}/recalculate-price', [MyPlayTogetherController::class, 'recalculatePrice'])->name('recalculatePrice');

        // Di dalam Route::prefix('main-bareng-saya')->name('main_bareng_saya.')->group(function () {
        Route::post('/{id}/kick/{participantId}', [MyPlayTogetherController::class, 'kickParticipant'])->name('kickParticipant');

        Route::get('/{id}/qr', [MyPlayTogetherController::class, 'showQr'])->name('qr');
    
        // NEW PAYMENT ROUTES FOR PARTICIPANTS
        Route::get('/{id}/get-snap-token', [MyPlayTogetherController::class, 'getSnapToken'])->name('getSnapToken');
        Route::post('/{id}/update-payment-status', [MyPlayTogetherController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');

        // Search users for invitation
        Route::get('/{id}/search-users', [MyPlayTogetherController::class, 'searchUsers'])->name('searchUsers');
        
        // Invite user
        Route::post('/{id}/invite-user', [MyPlayTogetherController::class, 'inviteUser'])->name('inviteUser');
        
        // Approve/reject participants
        Route::post('/{id}/approve/{participantId}', [MyPlayTogetherController::class, 'approveParticipant'])->name('approveParticipant');
        Route::post('/{id}/reject/{participantId}', [MyPlayTogetherController::class, 'rejectParticipant'])->name('rejectParticipant');

        
        // Invitation response routes
        Route::post('/invitation/{invitation}/accept', [MyPlayTogetherController::class, 'acceptInvitation'])->name('acceptInvitation');
        Route::post('/invitation/{invitation}/reject', [MyPlayTogetherController::class, 'rejectInvitation'])->name('rejectInvitation');
    });

            Route::get('/booking/{id}/check-status', [BuyerBookingController::class, 'checkStatus'])
                ->name('booking.check-status');

            Route::get('/booking/{id}/qr-code', [BuyerBookingController::class, 'getQrCode'])
                ->name('booking.qr-code');

            Route::get('/booking/{id}/test-payment', [BuyerBookingController::class, 'testPayment'])
                ->name('booking.test-payment');

            // TAMBAHKAN ROUTE INI DI DALAM Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group()
            // TAMBAHKAN/UPDATE ROUTE BOOKING INI DI DALAM Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group()

            // TAMBAHKAN/UPDATE ROUTE BOOKING INI DI DALAM Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group()

            Route::prefix('booking')->name('booking.')->group(function () {
                // Main CRUD Routes
                Route::get('/', [BuyerBookingController::class, 'index'])->name('index');
                Route::get('/create', [BuyerBookingController::class, 'create'])->name('create');
                Route::post('/', [BuyerBookingController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [BuyerBookingController::class, 'edit'])->name('edit');
                Route::put('/{id}', [BuyerBookingController::class, 'update'])->name('update');
                Route::delete('/{id}', [BuyerBookingController::class, 'destroy'])->name('destroy');

                // Payment Routes
                Route::post('/{id}/process-payment', [BuyerBookingController::class, 'processPayment'])->name('process-payment');
                Route::post('/{id}/update-payment', [BuyerBookingController::class, 'updatePaymentStatus'])->name('update-payment');

                Route::get('/{id}/generate-snap-token', [BuyerBookingController::class, 'generateSnapToken'])->name('booking.generate-snap-token');


                // AJAX Routes
                Route::get('/sections/{venueId}', [BuyerBookingController::class, 'getSections'])->name('sections');
                Route::get('/schedules-by-section/{sectionId}', [BuyerBookingController::class, 'getSchedulesBySection'])->name('schedules-by-section');

                // Additional info routes
                Route::get('/{id}/get-payment-data', [BuyerBookingController::class, 'getPaymentData'])->name('get-payment-data');
                Route::get('/{id}/check-status', [BuyerBookingController::class, 'checkStatus'])->name('check-status');
                Route::get('/{id}/qr-code', [BuyerBookingController::class, 'getQrCode'])->name('qr-code');
                Route::get('/{id}/test-payment', [BuyerBookingController::class, 'testPayment'])->name('test-payment');
            });

            Route::prefix('main-bareng')->name('main_bareng.')->group(function () {
                Route::get('/', [PlayTogetherController::class, 'index'])->name('index');
                Route::post('/', [PlayTogetherController::class, 'store'])->name('store');

                // EDIT & UPDATE
                Route::get('/{id}/edit', [PlayTogetherController::class, 'edit'])->name('edit');
                Route::put('/{id}', [PlayTogetherController::class, 'update'])->name('update');

                // SHOW (detail)
                Route::get('/{id}', [PlayTogetherController::class, 'show'])->name('show');

                // JOIN
                Route::post('/{id}/join', [PlayTogetherController::class, 'join'])->name('join');

                // PROCESS PAYMENT
                Route::post('/{id}/process-payment', [PlayTogetherController::class, 'processPayment'])->name('process_payment');

                // CREATE PARTICIPANT AFTER PAYMENT
                Route::post('/create-participant', [PlayTogetherController::class, 'createParticipant'])->name('create_participant');

                // CHECK PAYMENT STATUS
                Route::get('/check-payment-status/{orderId}', [PlayTogetherController::class, 'checkPaymentStatus'])->name('check_payment_status');

                // DELETE
                Route::delete('/{id}', [PlayTogetherController::class, 'destroy'])->name('destroy');
            });

            // Deposit routes
            Route::prefix('deposit')->name('deposit.')->group(function () {
                Route::get('/', [DepositController::class, 'index'])->name('index');
                Route::get('/create', [DepositController::class, 'create'])->name('create');
                Route::post('/', [DepositController::class, 'store'])->name('store');
                Route::get('/{deposit}', [DepositController::class, 'show'])->name('show');
                Route::post('/{deposit}/confirm', [DepositController::class, 'confirm'])->name('confirm');
                Route::post('/{deposit}/cancel', [DepositController::class, 'cancel'])->name('cancel');
                Route::get('/api/history', [DepositController::class, 'getHistory'])->name('history');
            });

            // Main Bareng Saya Routes
            Route::prefix('main-bareng-saya')->name('main_bareng_saya.')->group(function () {

                Route::get('/', [MyPlayTogetherController::class, 'index'])->name('index');
                Route::get('/{id}', [MyPlayTogetherController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [MyPlayTogetherController::class, 'edit'])->name('edit');
                Route::put('/{id}', [MyPlayTogetherController::class, 'update'])->name('update');
                Route::delete('/{id}', [MyPlayTogetherController::class, 'destroy'])->name('destroy');

                // NEW ROUTE FOR RECALCULATE PRICE - TAMBAHKAN INI
                Route::post('/{id}/recalculate-price', [MyPlayTogetherController::class, 'recalculatePrice'])->name('recalculatePrice');

                Route::get('/{id}/qr', [MyPlayTogetherController::class, 'showQr'])->name('qr');

                // NEW PAYMENT ROUTES FOR PARTICIPANTS
                Route::get('/{id}/get-snap-token', [MyPlayTogetherController::class, 'getSnapToken'])->name('getSnapToken');
                Route::post('/{id}/update-payment-status', [MyPlayTogetherController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');

                // Search users for invitation
                Route::get('/{id}/search-users', [MyPlayTogetherController::class, 'searchUsers'])->name('searchUsers');

                // Invite user
                Route::post('/{id}/invite-user', [MyPlayTogetherController::class, 'inviteUser'])->name('inviteUser');

                // Approve/reject participants
                Route::post('/{id}/approve/{participantId}', [MyPlayTogetherController::class, 'approveParticipant'])->name('approveParticipant');
                Route::post('/{id}/reject/{participantId}', [MyPlayTogetherController::class, 'rejectParticipant'])->name('rejectParticipant');

                // Invitation response routes
                Route::post('/invitation/{invitation}/accept', [MyPlayTogetherController::class, 'acceptInvitation'])->name('acceptInvitation');
                Route::post('/invitation/{invitation}/reject', [MyPlayTogetherController::class, 'rejectInvitation'])->name('rejectInvitation');
            });

            Route::get('/home', [BuyerHomeController::class, 'index'])->name('home.index');

            // Navigation routes
            Route::get('/home', [BuyerHomeController::class, 'index'])->name('home');

            // Profile routes
            Route::get('/profile', [BuyerProfileController::class, 'profile'])
                ->name('profile');
            Route::get('/profile/edit', [BuyerProfileController::class, 'edit'])
                ->name('profile.edit');

            Route::put('/profile/update', [BuyerProfileController::class, 'update'])
                ->name('profile.update');

            Route::post('/profile/change-password', [BuyerProfileController::class, 'changePassword'])
                ->name('profile.change-password');

            Route::get('/profile/stats', [BuyerProfileController::class, 'getStats'])
                ->name('profile.stats');
            
            // Background update route
            Route::post('/profile/update-background', [BuyerProfileController::class, 'updateBackground'])
                ->name('profile.background.update');
            
            // Route untuk halaman pengajuan
            Route::get('/buyer/pengajuan', [BuyerProfileController::class, 'pengajuan'])->name('profile.pengajuan');

            Route::get('/explore', [BuyerExploreController::class, 'index'])->name('explore');
            Route::get('/explore/search', [BuyerExploreController::class, 'search'])->name('explore.search');

            Route::get('/map', [BuyerExploreController::class, 'map'])
                ->name('map');

            Route::get('/chat', [BuyerChatController::class, 'chat'])
                ->name('chat');

            Route::get('/menu', [BuyerMenuController::class, 'menu'])
                ->name('menu');

            Route::get('/menu/booking', [BuyerBookingController::class, 'index'])->name('menu.booking.index');
            Route::get('/venue/popular', [BuyerVenueController::class, 'popular'])->name('venue.popular');
            Route::get('/venue/{id}', [BuyerVenueController::class, 'show'])->name('venue.show');
            Route::get('/menu/booking', [BuyerBookingController::class, 'index'])->name('menu.booking.index');

            // ========================================
            // PHONE CHANGE ROUTES
            // ========================================
            // Route untuk halaman edit komunitas
            Route::get('/communities/{community}/edit', [CommunitiesController::class, 'edit'])
                ->name('communities.edit');

            // Route untuk halaman invite member
            Route::get('/communities/{community}/invite', [CommunitiesController::class, 'invite'])
                ->name('communities.invite');

            // Fetch semua user untuk invite (search optional)
            Route::get('/communities/{community}/search-users', [CommunitiesController::class, 'searchUsers'])
                ->name('communities.searchUsers');

            // Invite user
            Route::post('/communities/{community}/invite-user', [CommunitiesController::class, 'inviteUser'])
                ->name('communities.inviteUser');

            // accept invite
            Route::post(
                '/communities/{community}/accept',
                [CommunitiesController::class, 'joinInvite']
            )->name('communities.joinInvite');

            // reject invite
            Route::put(
                '/communities/{invitation}/reject',
                [CommunitiesController::class, 'rejectInvite']
            )->name('communities.rejectInvite');
            /*
        |--------------------------------------------------------------------------
        | COMMUNITY JOIN REQUEST (APPROVE / REJECT)
        |--------------------------------------------------------------------------
        */
            Route::get(
                '/communities/{community}/requests',
                [CommunitiesController::class, 'requests']
            )->name('communities.requests');

            Route::post(
                '/communities/requests/{request}/approve',
                [CommunitiesController::class, 'approveRequest']
            )->name('communities.requests.approve');

            Route::post(
                '/communities/requests/{request}/reject',
                [CommunitiesController::class, 'rejectRequest']
            )->name('communities.requests.reject');

            // Route to open community chat
            Route::get('/communities/{community}/chat', [CommunitiesController::class, 'chat'])
                ->name('communities.chat');

            // Membership actions via notification (accept/reject by community_id + user_id)
            Route::post('/communities/membership/approve', [CommunitiesController::class, 'approveMembershipAjax'])->name('communities.membership.approve');
            Route::post('/communities/membership/reject', [CommunitiesController::class, 'rejectMembershipAjax'])->name('communities.membership.reject');
            // Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');

            // Notification routes
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
                Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
            });

            // change phone number
            Route::post('/profile/change-phone', [BuyerPhoneChangeController::class, 'send'])
                ->name('phone.change');
            Route::post('/profile/resend-phone', [BuyerPhoneChangeController::class, 'resend'])
                ->name('phone.resend');
            Route::get('/profile/verify-phone', [BuyerPhoneChangeController::class, 'verify'])
                ->name('phone.verify');
            Route::get('/profile/phone-invalid', function () {
                return view('buyer.profile.phone-invalid');
            })->name('profile.phone-invalid');
            Route::get('/profile/phone-verified', function () {
                return view('buyer.profile.phone-verified');
            })->name('profile.phone-verified');
            Route::post('/profile/cancel-phone', [BuyerPhoneChangeController::class, 'cancel'])
                ->name('phone.cancel');
        });

    /*
    |--------------------------------------------------------------------------
    | UTILITY / GENERAL ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/layouts/landowner/home/profile', function () {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role === 'landowner' || session('temp_role') === 'landowner') {
            return redirect()->route('landowner.profile');
        }

        if ($user->role === 'buyer') {
            return redirect()->route('buyer.profile');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect('/');
    })->name('layouts.landowner.home.profile');
});

// routes/web.php
Route::get('/debug/main-bareng', [PlayTogetherController::class, 'debug'])
    ->middleware('auth')
    ->name('buyer.main_bareng.debug');
