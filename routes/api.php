<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => "payment"], function () {
    Route::post('callback', [StudentController::class, 'paymentCallback']);
});
Route::group(['prefix' => "callback"], function () {
    Route::post('whatsapp', [AdminController::class, 'whatsappCallback'])->name('callback.whatsapp');
    Route::post('midtrans', [StudentController::class, 'paymentCallback'])->name('callback.midtrans');
    // https://ea6a-36-81-170-14.ngrok-free.app
});