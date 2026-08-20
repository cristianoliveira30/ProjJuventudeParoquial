<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\RetreatRegistrationController;
use App\Http\Controllers\RetreatServantController;
use App\Http\Controllers\RetreatServantLinkController;
use App\Services\LiturgiaService;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'home'])->name('portal.home');

Route::get('/a-paroquia', [PortalController::class, 'parish'])->name('portal.paroquia');
Route::get('/a-paroquia/pastorais/{group:slug}', [PortalController::class, 'pastoralShow'])->name('portal.pastorais.show');
Route::view('/celebracoes/horarios-de-missa', 'portal.missas')->name('portal.missas');
Route::get('/liturgia-diaria', function (LiturgiaService $liturgiaService) {
    return view('portal.liturgia', [
        'liturgia' => $liturgiaService->getLiturgiaDoDia(),
    ]);
})->name('portal.liturgia');
Route::get('/juventude', [PortalController::class, 'youth'])->name('portal.juventude');
Route::get('/juventude/retiros/{retreat:slug}', [PortalController::class, 'retreatShow'])->name('portal.retiros.show');
Route::post('/juventude/retiros/{retreat:slug}/inscricao', [RetreatRegistrationController::class, 'storePublic'])
    ->middleware('throttle:10,1')
    ->name('portal.retiros.register');
Route::get('/servir/{token}', [RetreatServantLinkController::class, 'publicShow'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->middleware('throttle:30,1')
    ->name('portal.servants.link');
Route::post('/servir/{token}', [RetreatServantController::class, 'storePublic'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->middleware('throttle:10,1')
    ->name('portal.servants.register');
Route::view('/dizimo', 'portal.dizimo')->name('portal.dizimo');
Route::get('/noticias', [PortalController::class, 'newsIndex'])->name('portal.noticias');
Route::get('/noticias/{post:slug}', [PortalController::class, 'newsShow'])->name('portal.noticias.show');
Route::get('/agenda', [PortalController::class, 'agenda'])->name('portal.agenda');
Route::get('/agenda/{event:slug}', [PortalController::class, 'agendaShow'])->name('portal.agenda.show');
Route::get('/galeria', [PortalController::class, 'gallery'])->name('portal.galeria');
Route::get('/galeria/{album:slug}', [PortalController::class, 'galleryShow'])->name('portal.galeria.show');
Route::view('/apoie-o-trabalho-voluntario', 'portal.apoio')->name('portal.apoio');
