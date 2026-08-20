<?php

use App\Http\Controllers\RetreatController;
use App\Http\Controllers\RetreatMinistryController;
use App\Http\Controllers\RetreatRegistrationController;
use App\Http\Controllers\RetreatServantController;
use App\Http\Controllers\RetreatServantLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('retiros')->name('retreats.')->group(function () {
    Route::get('/', [RetreatController::class, 'index'])->middleware('can:retreats.view')->name('index');
    Route::get('/criar', [RetreatController::class, 'create'])->middleware('can:retreats.create')->name('create');
    Route::post('/', [RetreatController::class, 'store'])->middleware('can:retreats.create')->name('store');
    Route::get('/{retreat}/editar', [RetreatController::class, 'edit'])->middleware('can:retreats.update')->name('edit');
    Route::put('/{retreat}', [RetreatController::class, 'update'])->middleware('can:retreats.update')->name('update');
    Route::patch('/{retreat}/publicacao', [RetreatController::class, 'togglePublication'])->middleware('can:retreats.publish')->name('publication');
    Route::delete('/{retreat}', [RetreatController::class, 'destroy'])->middleware('can:retreats.delete')->name('destroy');
    Route::get('/{retreat}/inscricoes', [RetreatRegistrationController::class, 'index'])->middleware('can:retreats.registrations')->name('registrations.index');
    Route::patch('/{retreat}/inscricoes/{registration}', [RetreatRegistrationController::class, 'update'])->middleware('can:retreats.registrations')->scopeBindings()->name('registrations.update');
    Route::get('/{retreat}/ministerios', [RetreatMinistryController::class, 'index'])->middleware('can:retreats.update')->name('ministries.index');
    Route::post('/{retreat}/ministerios', [RetreatMinistryController::class, 'store'])->middleware('can:retreats.update')->name('ministries.store');
    Route::put('/{retreat}/ministerios/{ministry}', [RetreatMinistryController::class, 'update'])->middleware('can:retreats.update')->name('ministries.update');
    Route::patch('/{retreat}/ministerios/{ministry}/status', [RetreatMinistryController::class, 'toggle'])->middleware('can:retreats.update')->name('ministries.status');
    Route::delete('/{retreat}/ministerios/{ministry}', [RetreatMinistryController::class, 'destroy'])->middleware('can:retreats.update')->name('ministries.destroy');
    Route::get('/{retreat}/link-de-servos', [RetreatServantLinkController::class, 'show'])->middleware('can:retreats.update')->name('servant-link.show');
    Route::put('/{retreat}/link-de-servos', [RetreatServantLinkController::class, 'update'])->middleware('can:retreats.update')->name('servant-link.update');
    Route::post('/{retreat}/link-de-servos/regenerar', [RetreatServantLinkController::class, 'regenerate'])->middleware('can:retreats.update')->name('servant-link.regenerate');
    Route::get('/{retreat}/servos', [RetreatServantController::class, 'index'])->middleware('can:retreats.registrations')->name('servants.index');
    Route::patch('/{retreat}/servos/{servant}', [RetreatServantController::class, 'update'])->middleware('can:retreats.registrations')->scopeBindings()->name('servants.update');
});
