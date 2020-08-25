<?php

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
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('roles', 'RoleController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::get('auth', function () {
        return auth()->user();
    });

    // Sector routes
    Route::resource('sectors', 'SectorController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::put('sectors/change/status/{id}', 'SectorController@changeStatus')->name('sectors.status');
    Route::get('sectors/all', 'SectorController@fetchAllSectors');
    Route::get('sectors/all/only-active', 'SectorController@fetchAllActiveSectors');

    // Payment Mode routes
    Route::resource('paymentmodes', 'PaymentModeController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::put('paymentmodes/change/status/{id}', 'PaymentModeController@changeStatus')->name('paymentmodes.status');
    Route::get('paymentmodes/all', 'PaymentModeController@fetchAllPaymentModes');
    Route::get('paymentmodes/all/only-active', 'PaymentModeController@fetchAllActivePaymentModes');

    // Sector routes
    Route::resource('services', 'ServiceController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::put('services/change/status/{id}', 'ServiceController@changeStatus')->name('services.status');
    Route::get('services/all', 'ServiceController@fetchAllServices');

    // Sector routes
    Route::resource('transactiontypes', 'TransactionTypeController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::put('transactiontypes/change/status/{id}', 'TransactionTypeController@changeStatus')->name('transactiontypes.status');
    Route::get('transactiontypes/all', 'TransactionTypeController@fetchAllTransactiontypes');

    // Payment Mode routes
    Route::resource('transactions', 'TransactionController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::put('transactions/change/status/{id}', 'TransactionController@changeStatus')->name('transactions.status');
    Route::get('transactions/all', 'TransactionController@fetchAllTransactions');

    Route::get('/home', 'HomeController@index')->name('home');
});

