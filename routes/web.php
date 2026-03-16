<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExplorePlacesController;
use App\Http\Controllers\MonthlyVisitsController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPlaceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\publichomeController;
use App\Http\Controllers\TouristPlaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [publichomeController::class, 'index']);
Route::post('/track-visit', [DashboardController::class, 'trackVisit'])->name('track.visit');

Route::get('/aboutuspage', function () {
    return view('public.longinfo.aboutuspage');
})->name('aboutuspage');

Route::get('/historypage', [HistoryController::class, 'index'])->name('historypage');
Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [ActivitiesController::class, 'show'])->name('activities.show');
Route::get('/events', [EventsController::class, 'index'])->name('events.list');
Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');
Route::get('/exploreplaces', [ExplorePlacesController::class, 'index'])->name('exploreplaces');
Route::get('/exploreplaces/{id}', [ExplorePlacesController::class, 'show'])->name('exploreplaces.show');
Route::post('/exploreplaces/{place}/review', [ExplorePlacesController::class, 'storeReview'])->name('review.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['admin.auth', 'no-cache'])->prefix('admin')->group(function () {
    Route::get('/admindashboard', [DashboardController::class, 'index'])->name('admindashboard');
    Route::get('/realtime-visits', [DashboardController::class, 'realtimeVisitors'])->name('realtime.visits');
});

Route::get('/bin/users', [UserController::class, 'trash'])->name('bin.users');
Route::patch('/bin/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('activities', AdminActivityController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('places', AdminPlaceController::class);
    Route::resource('events', AdminEventController::class);
    Route::post('places/{id}/remove-image', [AdminPlaceController::class, 'removeImage'])->name('places.removeImage');
    Route::post('events/{id}/remove-image', [AdminEventController::class, 'removeImage'])->name('events.removeImage');
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/ajax-delete-image', [SettingsController::class, 'ajaxDeleteImage'])->name('settings.ajaxDeleteImage');
});

Route::get('/touristplace', [TouristPlaceController::class, 'index'])->name('touristplace.index');
Route::post('/touristplace/store', [TouristPlaceController::class, 'store'])->name('touristplace.store');
Route::put('/admin/touristplace/update/{id}', [TouristPlaceController::class, 'update'])->name('touristplace.update');

Route::middleware(['admin.auth', 'no-cache'])->group(function () {
    Route::get('/monthly-visits', [MonthlyVisitsController::class, 'index'])->name('monthlyvisits.index');
    Route::get('/monthly-visits-overview', [MonthlyVisitsController::class, 'overview'])->name('monthlyvisits.overview');
    Route::post('/monthlyvisits/store', [MonthlyVisitsController::class, 'store'])->name('monthlyvisits.store');
    Route::put('/monthlyvisits/{id}', [MonthlyVisitsController::class, 'update'])->name('monthlyvisits.update');
    Route::delete('/monthlyvisits/{id}', [MonthlyVisitsController::class, 'destroy'])->name('monthlyvisits.destroy');
    Route::get('/monthlyvisits/check-month', [MonthlyVisitsController::class, 'checkMonth']);
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('/adduser', [UserController::class, 'adduser']);
    Route::get('/usershome', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/user-log', [UserHistoryController::class, 'index'])->name('userlog.index');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});
