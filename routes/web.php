<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('index');
// });


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


/**
 * Invoice Controller
 */

Route::middleware(['web','auth'])->group(function () {

    Route::post('/invoice/create', 'InvoiceController@create' )->name('create.invoice');
    Route::view('/invoice/create', 'invoices.create')->name('create.invoice');
    Route::get('/invoices/', 'InvoiceController@index' )->name('invoices');

    Route::get('/invoices/{invoice}', 'InvoiceController@show' )->name('invoices.show');


});

