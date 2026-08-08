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
            // edit market details
            Route::get('/edit-market/{id}', [ManagePropertyController::class, 'edit_market_details'])->name('admin.manage-property.edit-market');

            Route::post('/update-market/{id}', [ManagePropertyController::class, 'update_market_details'])->name('admin.manage-property.update-market');
            //edit floor plan
            Route::get('/edit-floorplan/{id}', [ManagePropertyController::class, 'edit_floorplan'])->name('admin.manage-property.edit-floorplan');

            Route::post('/update-floorplan/{id}', [ManagePropertyController::class, 'update_floorplan'])->name('admin.manage-property.update-floorplan');

            // edit property extra details
            Route::get('/edit-property-extra-details/{id}', [ManagePropertyController::class, 'edit_property_extra_details'])->name('admin.manage-property.edit-property-extra-details');

            Route::post('/update-property-extra-details/{id}', [ManagePropertyController::class, 'update_property_extra_details'])->name('admin.manage-property.update-property-extra-details');
            // annunal cash flow
            Route::get('/edit-property-aacf/{id}', [ManagePropertyController::class, 'edit_property_aacf'])->name('admin.manage-property.edit-property-aacf');

            Route::post('/update-property-aacf/{id}', [ManagePropertyController::class, 'update_property_aacf'])->name('admin.manage-property.update-property-aacf');

            // edit urls
            Route::get('/edit-property-urls/{id}', [ManagePropertyController::class, 'edit_property_urls'])->name('admin.manage-property.edit-property-urls');

            Route::post('/update-property-urls/{id}', [ManagePropertyController::class, 'update_property_urls'])->name('admin.manage-property.update-property-urls');

            // property offerings 
            Route::get('/edit-property-offerings/{id}', [ManagePropertyController::class, 'edit_property_offerings'])->name('admin.manage-property.edit-property-offerings');

            Route::post('/update-property-offerings/{id}', [ManagePropertyController::class, 'update_property_offerings'])->name('admin.manage-property.update-property-offerings');

            //property  shares
            Route::get('/edit-property-shares/{id}', [ManagePropertyController::class, 'edit_property_shares'])->name('admin.manage-property.edit-property-shares');

            Route::post('/update-property-shares/{id}', [ManagePropertyController::class, 'update_property_shares'])->name('admin.manage-property.update-property-shares');

            // financial details 
            Route::get('/edit-property-financial-details/{id}', [ManagePropertyController::class, 'edit_property_financial_details'])->name('admin.manage-property.edit-property-financial-details');

            Route::post('/update-property-financial-details/{id}', [ManagePropertyController::class, 'update_property_financial_details'])->name('admin.manage-property.update-financial-details');

            //calc presets
            Route::get('/edit-property-calc-presets/{id}', [ManagePropertyController::class, 'edit_property_calc_presets'])->name('admin.manage-property.edit-property-calc-presets');

            Route::post('/update-property-calc-presets/{id}', [ManagePropertyController::class, 'update_property_calc_presets'])->name('admin.manage-property.update-calc-presets');
            // edit property documents
            Route::get('/edit-property-documents/{id}', [ManagePropertyController::class, 'edit_property_documents'])->name('admin.manage-property.edit-property-documents');

            Route::post('/update-property-documents/{id}', [ManagePropertyController::class, 'update_property_documents'])->name('admin.manage-property.update-property-documents');
            // edit property taxes
            Route::get('/edit-property-taxes/{id}', [ManagePropertyController::class, 'edit_property_taxes'])->name('admin.manage-property.edit-property-taxes');

            Route::post('/update-property-taxes/{id}', [ManagePropertyController::class, 'update_property_taxes'])->name('admin.manage-property.update-property-taxes');



        });










    });
});






Route::get('/register', [CustomerController::class, 'index']);
Route::get('/invest', [CustomerController::class, 'investor'])->name('properties');

Route::get('/property_singlepage', [CustomerController::class, 'property_singlepage']);

