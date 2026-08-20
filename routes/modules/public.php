<?php

use Illuminate\Support\Facades\Route;
use App\Services\LiturgiaService;

Route::view('/', 'portal.home')->name('portal.home');

Route::view('/a-paroquia', 'portal.paroquia')->name('portal.paroquia');
Route::view('/celebracoes/horarios-de-missa', 'portal.missas')->name('portal.missas');
Route::get('/liturgia-diaria', function (LiturgiaService $liturgiaService) {
    return view('portal.liturgia', [
        'liturgia' => $liturgiaService->getLiturgiaDoDia(),
    ]);
})->name('portal.liturgia');
Route::view('/juventude', 'portal.juventude')->name('portal.juventude');
Route::view('/dizimo', 'portal.dizimo')->name('portal.dizimo');
Route::view('/noticias', 'portal.noticias')->name('portal.noticias');
