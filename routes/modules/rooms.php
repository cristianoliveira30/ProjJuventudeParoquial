<?php

use App\Http\Controllers\RoomBookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('salas')->name('rooms.')->group(function () {
    Route::get('/', [RoomBookingController::class, 'index'])->middleware('can:rooms.view')->name('index');
    Route::post('/solicitacoes', [RoomBookingController::class, 'store'])->middleware('can:rooms.request')->name('store');
    Route::patch('/solicitacoes/{booking}/decisao', [RoomBookingController::class, 'decide'])->middleware('can:rooms.manage')->name('decide');
    Route::patch('/solicitacoes/{booking}/cancelar', [RoomBookingController::class, 'cancel'])->middleware('can:rooms.view')->name('cancel');
    Route::post('/', [RoomBookingController::class, 'storeRoom'])->middleware('can:rooms.manage')->name('room.store');
    Route::patch('/{room}/status', [RoomBookingController::class, 'toggleRoom'])->middleware('can:rooms.manage')->name('room.toggle');
});
