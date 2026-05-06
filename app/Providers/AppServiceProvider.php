<?php

namespace App\Providers;

use App\View\Composers\NotificationBadgeComposer;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \URL::forceScheme('https');
        App::setLocale(config('hotel.lang_default', 'fr'));
        Carbon::setLocale(config('hotel.lang_default', 'fr'));

        View::composer('layouts.app', NotificationBadgeComposer::class);
    }
}
