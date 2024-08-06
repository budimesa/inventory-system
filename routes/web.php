<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\ItemReportingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssetLoanController;
use App\Http\Controllers\ProblematicItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;

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

    Route::resource('users', UserController::class);
    Route::get('get-dropdown-user', [UserController::class, 'getDropdownUser']);
    Route::get('get-user-list', [UserController::class, 'getUserList'])->name('users.get-users-list');
    Route::post('update-user/{id}', [UserController::class, 'updateUser']);
    Route::post('delete-user/{id}', [UserController::class, 'deleteUser']);

    Route::resource('asset_loans', AssetLoanController::class);
    Route::get('get-loan-list', [AssetLoanController::class, 'getLoanList'])->name('loan.get-loan-list');
    Route::post('/update-loan/{id}', [AssetLoanController::class, 'updateLoan'])->name('loan.update');
    Route::post('/return-loan/{id}', [AssetLoanController::class, 'returnLoan'])->name('loan.return');
    Route::post('/delete-loan/{id}', [AssetLoanController::class, 'deleteLoan'])->name('loan.delete');

    Route::resource('problematic-items', ProblematicItemController::class);
    Route::get('get-problematic-item-list', [ProblematicItemController::class, 'getProblematicItemList'])->name('problematic-item.get-problematic-item-list');
    Route::post('/return-problematic-item/{id}', [ProblematicItemController::class, 'returnProblematicItem'])->name('problematic-return.update');

    Route::resource('item-reporting', ItemReportingController::class);
    Route::get('get-item-report', [ItemReportingController::class, 'getItemReport'])->name('reporting.get-item-report');

    Route::put('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.change.update');

});


// Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
