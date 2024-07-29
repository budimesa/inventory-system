<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\OutgoingItemController;
use App\Http\Controllers\ItemReportingController;
use App\Http\Controllers\IncomingReportingController;
use App\Http\Controllers\OutgoingReportingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('suppliers', SupplierController::class)->except([
        'destroy'
    ]);
    Route::get('get-supplier-list', [SupplierController::class, 'getSupplierList'])->name('supplier.get-supplier-list');
    Route::post('/update-supplier/{id}', [SupplierController::class, 'updateSupplier'])->name('supplier.update');
    Route::post('/delete-supplier/{id}', [SupplierController::class, 'deleteSupplier'])->name('supplier.delete');
    Route::get('dropdown-supplier', [SupplierController::class, 'getDropdownSupplier'])->name('supplier.dropdown');
    
    Route::resource('items', ItemController::class);
    Route::get('get-item-list', [ItemController::class, 'getItemList'])->name('item.get-item-list');
    Route::post('/update-item/{id}', [ItemController::class, 'updateItem'])->name('item.update');
    Route::post('/delete-item/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');
    Route::get('dropdown-item', [ItemController::class, 'getDropdownItem'])->name('item.dropdown');

    Route::resource('incoming_items', IncomingItemController::class);
    Route::get('get-incoming-list', [IncomingItemController::class, 'getIncomingList'])->name('incoming.get-incoming-list');
    Route::post('/update-incoming-item/{id}', [IncomingItemController::class, 'updateIncoming'])->name('incoming.update');
    Route::post('/delete-incoming-item/{id}', [IncomingItemController::class, 'deleteIncoming'])->name('incoming.delete');

    Route::resource('outgoing_items', OutgoingItemController::class);
    Route::get('get-outgoing-list', [OutgoingItemController::class, 'getOutgoingList'])->name('outgoing.get-outgoing-list');
    Route::post('/update-outgoing-item/{id}', [OutgoingItemController::class, 'updateOutgoing'])->name('outgoing.update');
    Route::post('/delete-outgoing-item/{id}', [OutgoingItemController::class, 'deleteOutgoing'])->name('outgoing.delete');

    Route::resource('item-reporting', ItemReportingController::class);
    Route::get('get-item-report', [ItemReportingController::class, 'getItemReport'])->name('reporting.get-item-report');

    Route::resource('outgoing-reporting', OutgoingReportingController::class);
    Route::get('get-outgoing-report', [OutgoingReportingController::class, 'getOutgoingReport'])->name('reporting.get-outgoing-report');

    Route::resource('incoming-reporting', IncomingReportingController::class);
    Route::get('get-incoming-report', [IncomingReportingController::class, 'getIncomingReport'])->name('reporting.get-incoming-report');

    Route::put('/change-password', [UserController::class, 'changePassword'])->name('change.password');

});


// Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');