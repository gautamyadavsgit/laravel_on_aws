<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManagePropertyController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CustomerController;
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
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('/invest', [CustomerController::class, 'investor'])->name('properties');
Route::get('/property/{slug}', [CustomerController::class, 'property_singlepage'])->name('property.singlepage');
Route::get('/property_singlepage', [CustomerController::class, 'property_singlepage'])->name('property_singlepage');

// Customer / Investor Authentication (Laravel Breeze Flow)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.post');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Authenticated Routes
Route::match(['get', 'post'], '/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Email Verification
Route::get('/verify-email/{token}', VerifyEmailController::class)->name('verification.verify.token');
Route::post('/resend-verification', [EmailVerificationNotificationController::class, 'store'])->name('verification.resend');

// Admin Authentication & Management
Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');

    Route::middleware('admin.access')->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('admin.dashboard');

        // Manage Property Routes
        Route::resource('manage-property', ManagePropertyController::class);

        Route::prefix('/manage-property')->group(function () {
            Route::get('/edit-address/{id}', [ManagePropertyController::class, 'editAddress'])->name('admin.manage-property.edit-address');
            Route::post('/update-address/{id}', [ManagePropertyController::class, 'updateAddress'])->name('admin.manage-property.update-address');

            Route::get('/edit-details/{id}', [ManagePropertyController::class, 'editDetails'])->name('admin.manage-property.edit-details');
            Route::post('/update-details/{id}', [ManagePropertyController::class, 'updateDetails'])->name('admin.manage-property.update-details');

            Route::get('/edit-amenities/{id}', [ManagePropertyController::class, 'editAmenities'])->name('admin.manage-property.edit-amenities');
            Route::post('/update-aminities/{id}', [ManagePropertyController::class, 'updateAmenities'])->name('admin.manage-property.update-aminities');

            Route::get('/edit-floorplan/{id}', [ManagePropertyController::class, 'editFloorplan'])->name('admin.manage-property.edit-floorplan');
            Route::post('/update-floorplan/{id}', [ManagePropertyController::class, 'updateFloorplan'])->name('admin.manage-property.update-floorplan');

            Route::get('/edit-property-offerings/{id}', [ManagePropertyController::class, 'editOfferings'])->name('admin.manage-property.edit-property-offerings');
            Route::post('/update-property-offerings/{id}', [ManagePropertyController::class, 'updateOfferings'])->name('admin.manage-property.update-property-offerings');

            Route::get('/edit-property-documents/{id}', [ManagePropertyController::class, 'editDocuments'])->name('admin.manage-property.edit-property-documents');
            Route::post('/update-property-documents/{id}', [ManagePropertyController::class, 'updateDocuments'])->name('admin.manage-property.update-property-documents');

            Route::get('/edit-property-metrics/{id}', [ManagePropertyController::class, 'editMetrics'])->name('admin.manage-property.edit-property-metrics');
            Route::post('/update-property-metrics/{id}', [ManagePropertyController::class, 'updateMetrics'])->name('admin.manage-property.update-property-metrics');
        });
    });
});
