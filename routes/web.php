<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Foundation\Application;
use App\Http\Controllers\ActivitiesController;

// use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\DashboardController;

// Admin
use App\Http\Controllers\ExplorePlacesController;
use App\Http\Controllers\MonthlyVisitsController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPlaceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\AdminEventController;
// Trash Bin
use App\Http\Controllers\publichomeController;
use App\Http\Controllers\TouristPlaceController;
use App\Http\Controllers\TouristSpotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserHistoryController;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\Configuration\Php;

// use App\Http\Controllers\VerificationController;
// use Inertia\Inertia;

// PUBLIC HOME PAGE
Route::get('/', [publichomeController::class, 'index']);

// Simple visit tracking endpoint: call this from landing page to record a visit
Route::get('/track-visit', [DashboardController::class, 'trackVisit'])->name('track.visit');

// PUBLIC ABOUT US
Route::get('/aboutuspage', function () {
    return view('public.longinfo.aboutuspage');
})->name('aboutuspage');
// HISTORY
Route::get('/historypage', [HistoryController::class, 'index'])->name('historypage');

// PUBLIC ACTIVITES
Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities.index');
// PUBLIC AVTIVITY DETAILS
Route::get('/activities/{id}', [ActivitiesController::class, 'show'])->name('activities.show');


// PUBLIC EVENTS
Route::get('/events', [EventsController::class, 'index'])->name('events.list');
// PUBLIC EVENT DETAILS
Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');

// PUBLIC EXPLORE PLACES
Route::get('/exploreplaces', [ExplorePlacesController::class, 'index'])->name('exploreplaces');
// PUBLIC EXPLORE DETAILS
Route::get('/exploreplaces/{id}', [ExplorePlacesController::class, 'show'])->name('exploreplaces.show');
// ADD REVIEWS
Route::post('/exploreplaces/{place}/review', [ExplorePlacesController::class, 'storeReview'])->name('review.store');



// ADMIN DASHBOARD
// LOG IN

// Route::get('admin/login', function () {
//     return view('admin.login');
// })->name('login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD
Route::middleware(['admin.auth', 'no-cache'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/admindashboard', [DashboardController::class, 'index'])->name('admindashboard');
        // API endpoint for fetching current month (realtime) visit numbers
        Route::get('/realtime-visits', [DashboardController::class, 'realtimeVisitors'])->name('realtime.visits');

        // Route::resource('activities', AdminActivityController::class);
        // Route::resource('categories', AdminCategoryController::class);
        // Route::resource('places', AdminPlaceController::class);
        // Route::resource('events', AdminEventController::class);
    });
// // Route::get('/admin/admindashboard', function () {
// //     return view('admin.admindashboard');
// // })->name('admindashboard');

// Route::get('/display', function () {
//     return view('admin.list.display');
// })->name('display');
// Route::get('/about', function () {
//     return view('admin.list.about');
// })->name('about');

// Trash Bin Restoration Routes
Route::get('/bin/users', [UserController::class, 'trash'])->name('bin.users');
Route::patch('/bin/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore'); // Route::get('/trash/categories', [CategoryController::class, 'trash'])->name('trash.categories');
// Route::get('/trash/touristspots', [TouristSpotController::class, 'trash'])->name('trash.touristspots');


// Activities,place.category
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('activities', AdminActivityController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('places', AdminPlaceController::class);
    Route::resource('events', AdminEventController::class);

    // place edit - remove image
    Route::post(
        'places/{id}/remove-image',
        [AdminPlaceController::class, 'removeImage']
    )
        ->name('places.removeImage');

    // event edit - remove image
    Route::post(
        'events/{id}/remove-image',
        [AdminEventController::class, 'removeImage']
    )
        ->name('events.removeImage');

    // SETTINGS
    Route::get('settings', [SettingsController::class, 'edit'])
        ->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])
        ->name('settings.update');
    // remove img
    Route::post(
        'settings/ajax-delete-image',
        [SettingsController::class, 'ajaxDeleteImage']
    )->name('settings.ajaxDeleteImage');
});


// Protected admin actions (monthly visits, users, tourist spots, etc.)
Route::get('/touristplace', [TouristPlaceController::class, 'index'])->name('touristplace.index');
// Add Tourist Spots
Route::post('/touristplace/store', [TouristPlaceController::class, 'store'])->name('touristplace.store');
// Edit Tourist Spots
Route::put('/admin/touristplace/update/{id}', [TouristPlaceController::class, 'update'])->name('touristplace.update');

// Protected admin actions (monthly visits, users, etc.)
Route::middleware(['admin.auth', 'no-cache'])->group(function () {
    // Monthly Visits
    Route::get('/monthly-visits', [MonthlyVisitsController::class, 'index'])
        ->name('monthlyvisits.index');
    Route::get('/monthly-visits-overview', [MonthlyVisitsController::class, 'overview'])
        ->name('monthlyvisits.overview');
    // Add Visits
    Route::post('/monthlyvisits/store', [MonthlyVisitsController::class, 'store'])->name('monthlyvisits.store');
    // Edit Visits
    Route::put('/monthlyvisits/{id}', [MonthlyVisitsController::class, 'update'])
        ->name('monthlyvisits.update');
    //delete visits
    Route::delete('/monthlyvisits/{id}', [MonthlyVisitsController::class, 'destroy'])
        ->name('monthlyvisits.destroy');

    // For Duplicates
    Route::get('/monthlyvisits/check-month', [MonthlyVisitsController::class, 'checkMonth']);

    // User Search
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');
    // Add User
    Route::post('/adduser', action: [UserController::class, 'adduser']);
    // Display User List
    Route::get('/usershome', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    // Update User
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    // Delete User
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // USER LOG HISTORY
    Route::get('/user-log', [UserHistoryController::class, 'index'])->name('userlog.index');

    //history routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    
});
