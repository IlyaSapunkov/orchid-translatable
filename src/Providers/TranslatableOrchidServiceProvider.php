<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Providers;

use Illuminate\Support\ServiceProvider;
use IlyaSapunkov\Translatable\Http\Middleware\LocaleMiddleware;

class TranslatableOrchidServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/locales.php');
        $this->app['router']->pushMiddlewareToGroup('web', LocaleMiddleware::class);
    }
}
