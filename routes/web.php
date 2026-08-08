<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManagePropertyController;
use App\Http\Controllers\CustomerController;

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

Route::get('/', function () {
    return view('frontend.index');
});



Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
    Route::middleware('admin.access')->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('admin.dashboard');

        // new property routes

        Route::resource('manage-property', ManagePropertyController::class);
        //extra routes for working with extra data

        Route::prefix('/manage-property')->group(function () {
            Route::get('/edit-address/{id}', [ManagePropertyController::class, 'edit_address'])->name('admin.manage-property.edit-address');
            Route::post('/update-address/{id}', [ManagePropertyController::class, 'update_address'])->name('admin.manage-property.update-address');


            // edit property details
            Route::get('/edit-details/{id}', [ManagePropertyController::class, 'edit_property_details'])->name('admin.manage-property.edit-details');
            Route::post('/update-details/{id}', [ManagePropertyController::class, 'update_property_details'])->name('admin.manage-property.update-details');

            // edit Amenities (list)

            Route::get('/edit-amenities/{id}', [ManagePropertyController::class, 'edit_amenities'])->name('admin.manage-property.edit-amenities');

            Route::post('/update-aminities/{id}', [ManagePropertyController::class, 'update_aminities'])->name('admin.manage-property.update-aminities');
            //edit floor plan
            Route::get('/edit-floorplan/{id}', [ManagePropertyController::class, 'edit_floorplan'])->name('admin.manage-property.edit-floorplan');

            Route::post('/update-floorplan/{id}', [ManagePropertyController::class, 'update_floorplan'])->name('admin.manage-property.update-floorplan');

            // property offerings 
            Route::get('/edit-property-offerings/{id}', [ManagePropertyController::class, 'edit_property_offerings'])->name('admin.manage-property.edit-property-offerings');

            Route::post('/update-property-offerings/{id}', [ManagePropertyController::class, 'update_property_offerings'])->name('admin.manage-property.update-property-offerings');

            // edit property documents
            Route::get('/edit-property-documents/{id}', [ManagePropertyController::class, 'edit_property_documents'])->name('admin.manage-property.edit-property-documents');

            Route::post('/update-property-documents/{id}', [ManagePropertyController::class, 'update_property_documents'])->name('admin.manage-property.update-property-documents');
        });










    });
});






Route::get('/register', [CustomerController::class, 'index']);
Route::get('/invest', [CustomerController::class, 'investor'])->name('properties');

Route::get('/property_singlepage', [CustomerController::class, 'property_singlepage']);

