<?php

use App\Http\Controllers\GalleryAlbumController;
use App\Http\Controllers\GalleryPhotoController;
use Illuminate\Support\Facades\Route;

Route::prefix('painel/galeria')->name('gallery.')->group(function () {
    Route::get('/', [GalleryAlbumController::class, 'index'])->middleware('can:gallery.view')->name('index');
    Route::get('/criar', [GalleryAlbumController::class, 'create'])->middleware('can:gallery.create')->name('create');
    Route::post('/', [GalleryAlbumController::class, 'store'])->middleware('can:gallery.create')->name('store');
    Route::get('/{album}/editar', [GalleryAlbumController::class, 'edit'])->middleware('can:gallery.update')->name('edit');
    Route::put('/{album}', [GalleryAlbumController::class, 'update'])->middleware('can:gallery.update')->name('update');
    Route::patch('/{album}/publicacao', [GalleryAlbumController::class, 'togglePublication'])->middleware('can:gallery.publish')->name('publication');
    Route::delete('/{album}', [GalleryAlbumController::class, 'destroy'])->middleware('can:gallery.delete')->name('destroy');
    Route::get('/{album}/fotos', [GalleryPhotoController::class, 'index'])->middleware('can:gallery.photos')->name('photos.index');
    Route::post('/{album}/fotos', [GalleryPhotoController::class, 'store'])->middleware('can:gallery.photos')->name('photos.store');
    Route::put('/{album}/fotos/{photo}', [GalleryPhotoController::class, 'update'])->middleware('can:gallery.photos')->scopeBindings()->name('photos.update');
    Route::delete('/{album}/fotos/{photo}', [GalleryPhotoController::class, 'destroy'])->middleware('can:gallery.photos')->scopeBindings()->name('photos.destroy');
});
