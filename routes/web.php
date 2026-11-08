<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\NoStoreHtml;
use App\Http\Middleware\TrackPageAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/modules/public.php';

Route::middleware([NoStoreHtml::class, 'guest'])->group(function () {
    Route::view('/login', 'welcome')->name('login');
});

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::middleware('guest')->group(function () {
    Route::get('/esqueci-a-senha', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/esqueci-a-senha', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/redefinir-senha/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/redefinir-senha', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/verificar-email', [AuthController::class, 'showEmailVerification'])->name('email.verify');
    Route::post('/verificar-email', [AuthController::class, 'verifyEmail'])->middleware('throttle:5,1')->name('email.verify.submit');
    Route::post('/verificar-email/reenviar', [AuthController::class, 'resendEmailVerification'])->middleware('throttle:3,10')->name('email.verify.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/primeiro-acesso', [UserController::class, 'showFirstAccess'])->name('first.access');

    Route::put('/user/profile/update/password', [UserController::class, 'updateFirstAccessPassword'])
        ->name('user.profile.password.update');
});
Route::middleware(['auth', 'active.user', 'first.access.lock', TrackPageAccess::class])->group(function () {

    require __DIR__.'/modules/user.php';
    require __DIR__.'/modules/notifications.php';
    require __DIR__.'/modules/posts.php';
    require __DIR__.'/modules/events.php';
    require __DIR__.'/modules/groups.php';
    require __DIR__.'/modules/retreats.php';
    require __DIR__.'/modules/gallery.php';
    require __DIR__.'/modules/rooms.php';

    Route::get('/home', HomeController::class)->name('home');
    Route::get('/conta/seguranca', [UserController::class, 'showSecurity'])->name('account.security');
    Route::put('/conta/senha', [UserController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/perfis-de-acesso', [RoleController::class, 'index'])->middleware('can:roles.manage')->name('roles.index');
    Route::put('/perfis-de-acesso/{role}', [RoleController::class, 'update'])->middleware('can:roles.manage')->name('roles.update');
    Route::view('/relatorios', 'reports.default')->name('report.default');

    Route::prefix('user')->name('user.')->group(function () {
        Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    });
});

Route::view('/errors', 'errors.nodata')->name('errors.nodata');
Route::match(['get', 'post'], '/em-construcao', function () {
    return view('errors.nodata', [
        'title' => 'Em Breve',
        'route_name' => 'Funcionalidade em desenvolvimento',
    ]);
})->name('#');

Route::fallback(function (Request $request) {
    $caminho = $request->path();
    $tituloAmigavel = ucwords(str_replace(['/', '-', '_'], [' > ', ' ', ' '], $caminho));

    return response()->view('errors.nodata', [
        'title' => 'Em Desenvolvimento',
        'route_name' => $tituloAmigavel,
        'error_msg' => 'A funcionalidade solicitada ainda não está mapeada no sistema.',
    ], 404);
});
