<?php

use App\Http\Controllers\PastoralGroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('pastorais')->name('groups.')->group(function () {
    Route::get('/', [PastoralGroupController::class, 'index'])->middleware('can:groups.view')->name('index');
    Route::get('/criar', [PastoralGroupController::class, 'create'])->middleware('can:groups.create')->name('create');
    Route::post('/', [PastoralGroupController::class, 'store'])->middleware('can:groups.create')->name('store');
    Route::get('/{group}/editar', [PastoralGroupController::class, 'edit'])->middleware('can:groups.update')->name('edit');
    Route::put('/{group}', [PastoralGroupController::class, 'update'])->middleware('can:groups.update')->name('update');
    Route::patch('/{group}/publicacao', [PastoralGroupController::class, 'togglePublication'])->middleware('can:groups.publish')->name('publication');
    Route::delete('/{group}', [PastoralGroupController::class, 'destroy'])->middleware('can:groups.delete')->name('destroy');
});
