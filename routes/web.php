<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyFavoriteController;
use App\Http\Controllers\PropertyInterestController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Frontend
Route::view('/', 'frontend.index')->name('home');

Route::get('/invest', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/property_singlepage', [PropertyController::class, 'showLegacy'])->name('properties.legacy');

// Customer / Investor Authentication (Laravel Breeze Flow)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:10,1')->name('register.post');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:6,1')->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('throttle:6,1')->name('password.update');
});

Route::view('/investment-interest', 'frontend.investment-interest')->name('investment.interest');

Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/investment-interest/{propertyId}', [PropertyInterestController::class, 'store'])->whereNumber('propertyId')->name('investment.submit');

    // Property Favorites
    Route::post('/property/{propertyId}/favorite', [PropertyFavoriteController::class, 'toggle'])->whereNumber('propertyId')->name('properties.favorite');
});

// Authenticated Routes
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Email Verification
Route::get('/verify-email/{token}', VerifyEmailController::class)->name('verification.verify.token');
Route::post('/resend-verification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.resend');

// Admin Authentication & Management
Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'index'])->name('admin.index');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::middleware('admin.access')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
        Route::resource('users', UserController::class)->names('admin.users');

        Route::resource('properties', AdminPropertyController::class)->names('admin.properties');

        Route::delete('properties/{property}/images/{image}', [AdminPropertyController::class, 'destroyImage'])->scopeBindings()->name('admin.properties.images.destroy');
    });
});
