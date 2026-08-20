<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('publicacoes')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->middleware('can:posts.view')->name('index');
    Route::get('/criar', [PostController::class, 'create'])->middleware('can:posts.create')->name('create');
    Route::post('/', [PostController::class, 'store'])->middleware('can:posts.create')->name('store');
    Route::get('/{post}/editar', [PostController::class, 'edit'])->middleware('can:posts.update')->name('edit');
    Route::put('/{post}', [PostController::class, 'update'])->middleware('can:posts.update')->name('update');
    Route::patch('/{post}/publicacao', [PostController::class, 'togglePublication'])->middleware('can:posts.publish')->name('publication');
    Route::delete('/{post}', [PostController::class, 'destroy'])->middleware('can:posts.delete')->name('destroy');
});
