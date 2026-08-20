<?php

use App\Http\Controllers\ParishEventController;
use Illuminate\Support\Facades\Route;

Route::prefix('eventos')->name('events.')->group(function () {
    Route::get('/', [ParishEventController::class, 'index'])->middleware('can:events.view')->name('index');
    Route::get('/criar', [ParishEventController::class, 'create'])->middleware('can:events.create')->name('create');
    Route::post('/', [ParishEventController::class, 'store'])->middleware('can:events.create')->name('store');
    Route::get('/{event}/editar', [ParishEventController::class, 'edit'])->middleware('can:events.update')->name('edit');
    Route::put('/{event}', [ParishEventController::class, 'update'])->middleware('can:events.update')->name('update');
    Route::patch('/{event}/publicacao', [ParishEventController::class, 'togglePublication'])->middleware('can:events.publish')->name('publication');
    Route::delete('/{event}', [ParishEventController::class, 'destroy'])->middleware('can:events.delete')->name('destroy');
});
