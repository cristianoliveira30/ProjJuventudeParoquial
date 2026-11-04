<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layouts.app.header', function ($view) {
            $user = Auth::user();
            $notifications = $user
                ? $user->unreadNotifications()->latest('created_at')->limit(10)->get()->toArray()
                : [];

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $user ? $user->unreadNotifications()->count() : 0,
            ]);
        });
    }
}
