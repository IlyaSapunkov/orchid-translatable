<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleCreateScreen;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleEditScreen;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleListScreen;
use Tabuna\Breadcrumbs\Trail;

// Platform > Content > Locales
Route::screen('locales', LocaleListScreen::class)
    ->name('platform.locales')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('app.Locales'), route('platform.locales')));

// Platform > Content > Locales > Locale
Route::screen('locales/{model}/edit', LocaleEditScreen::class)
    ->name('platform.locales.edit')
    ->breadcrumbs(fn (Trail $trail, $model) => $trail
        ->parent('platform.locales')
        ->push($model->name, route('platform.locales.edit', $model)));

// Platform > Content > Locales > Create
Route::screen('locales/create', LocaleCreateScreen::class)
    ->name('platform.locales.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.locales')
        ->push(__('Create'), route('platform.locales.create')));
