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
        $this->app['router']->prependMiddlewareToGroup('web', LocaleMiddleware::class);
    }
}
