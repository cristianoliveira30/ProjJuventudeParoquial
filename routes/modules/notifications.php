<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WebPushController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')
    ->group(function () {

        Route::post('/create', [NotificationController::class, 'create'])
            ->middleware('can:notifications.send')
            ->name('notifications.create');
        Route::put('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::get('/dashboard', [NotificationController::class, 'dashboard'])
            ->middleware('can:notifications.send')
            ->name('notifications.dashboard');

        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/count-unread', [NotificationController::class, 'countUnread'])->name('notifications.count-unread');

        // ✅ web push
        Route::post('/webpush/subscribe', [WebPushController::class, 'subscribe'])->name('webpush.subscribe');
        Route::post('/webpush/unsubscribe', [WebPushController::class, 'unsubscribe'])->name('webpush.unsubscribe');
    });
