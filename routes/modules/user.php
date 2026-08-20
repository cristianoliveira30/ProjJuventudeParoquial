<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/search', [UserController::class, 'search'])
        ->middleware('can:users.view')
        ->name('search');
    Route::get('/create', [UserController::class, 'create'])
        ->middleware('can:users.create')
        ->name('create');
    Route::post('/', [UserController::class, 'store'])
        ->middleware('can:users.create')
        ->name('store');
    Route::put('/{user}', [UserController::class, 'updateAdmin'])
        ->middleware('can:users.update')
        ->name('update');
    Route::patch('/{user}/status', [UserController::class, 'toggleStatus'])
        ->middleware('can:users.status')
        ->name('status');
    Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('can:users.reset-password')
        ->name('reset-password');
});
