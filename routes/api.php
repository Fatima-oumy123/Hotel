<?php
use App\Http\Controllers\Api\ApiReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Routes publiques (Guest)
    Route::get('availability', [ApiReservationController::class, 'checkAvailability']);
    Route::post('reservations', [ApiReservationController::class, 'store']);
    Route::delete('reservations/{reservation}/cancel', [ApiReservationController::class, 'cancel']);

    // Routes protégées par Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('reservations', [ApiReservationController::class, 'index']);
        Route::get('reservations/{reservation}', [ApiReservationController::class, 'show']);
    });
});

