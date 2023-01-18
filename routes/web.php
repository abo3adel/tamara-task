<?php

use App\Http\Controllers\Website\PaymentResponse;
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

Route::prefix('payment-response')->name('payment-response.')->controller(PaymentResponse::class)->group(function() {
    Route::get('success', 'success')->name('success');
    Route::get('failure', 'failure')->name('failure');
    Route::get('cancel', 'cancel')->name('cancel');
});

Route::get('/', function () {
    return view('welcome');
});
