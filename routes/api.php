<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/tamara-notification-service', PaymentNotificationService::class)->name('notification-service');

Route::prefix('/payment')->name('payment.')->controller(PaymentController::class)->middleware('auth:api')->group(function() {
    Route::get('get-types', 'getTypes')->name('get-types');
    Route::post('checkout', 'checkout')->name('checkout');
    
    Route::post('capture/{order}', 'capture')->name('capture');

    Route::post('refund/{order}', 'refund')->name('refund');

    Route::prefix('order')->name('order.')->group(function() {
        Route::get('order/refrence/{order}', 'getOrderByReferenceId')->name('get-order-by-reference-id');
        Route::get('order/tamara/{order}', 'getOrderByTamaraId')->name('get-order-by-tamara-id');
    });
});
