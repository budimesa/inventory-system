<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\OutgoingItemController;
use App\Http\Controllers\ItemReportingController;
use App\Http\Controllers\IncomingReportingController;
use App\Http\Controllers\OutgoingReportingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssetLoanController;
use App\Http\Controllers\ProblematicItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ChangePasswordController;

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

    Route::middleware('auth')->group(function() {
        Route::get('password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
    });
    
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

    Route::resource('master-items', MasterItemController::class);
    Route::get('get-master-item-list', [MasterItemController::class, 'getMasterItemList'])->name('master-item.get-master-item-list');
    Route::post('/update-master-item/{id}', [MasterItemController::class, 'updateMasterItem'])->name('master-item.update');
    Route::post('/delete-master-item/{id}', [MasterItemController::class, 'deleteMasterItem'])->name('master-item.delete');
    Route::get('dropdown-master-item', [MasterItemController::class, 'getDropdownMasterItem'])->name('master-item.dropdown');

    Route::resource('employees', EmployeeController::class);
    Route::get('/employees-by-division/{division}', [EmployeeController::class, 'getEmployeesByDivision']);
    Route::get('get-dropdown-employee', [EmployeeController::class, 'getDropdownEmployee']);
    Route::get('get-employee-list', [EmployeeController::class, 'getEmployeeList'])->name('employees.get-employees-list');
    Route::post('update-employee/{id}', [EmployeeController::class, 'updateEmployee']);
    Route::post('delete-employee/{id}', [EmployeeController::class, 'deleteEmployee']);

    Route::resource('incoming_items', IncomingItemController::class);
    Route::get('get-incoming-list', [IncomingItemController::class, 'getIncomingList'])->name('incoming.get-incoming-list');
    Route::post('/update-incoming-item/{id}', [IncomingItemController::class, 'updateIncoming'])->name('incoming.update');
    Route::post('/delete-incoming-item/{id}', [IncomingItemController::class, 'deleteIncoming'])->name('incoming.delete');

    Route::resource('asset_loans', AssetLoanController::class);
    Route::get('get-loan-list', [AssetLoanController::class, 'getLoanList'])->name('loan.get-loan-list');
    Route::post('/update-loan/{id}', [AssetLoanController::class, 'updateLoan'])->name('loan.update');
    Route::post('/return-loan/{id}', [AssetLoanController::class, 'returnLoan'])->name('loan.return');
    Route::post('/delete-loan/{id}', [AssetLoanController::class, 'deleteLoan'])->name('loan.delete');

    Route::resource('problematic-items', ProblematicItemController::class);
    Route::get('get-problematic-item-list', [ProblematicItemController::class, 'getProblematicItemList'])->name('problematic-item.get-problematic-item-list');
    Route::post('/return-problematic-item/{id}', [ProblematicItemController::class, 'returnProblematicItem'])->name('problematic-return.update');

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

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
