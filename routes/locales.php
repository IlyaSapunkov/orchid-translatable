<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleCreateScreen;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleEditScreen;
use IlyaSapunkov\Translatable\Orchid\Screens\Locale\LocaleListScreen;
use Tabuna\Breadcrumbs\Trail;

// Platform > Content > Locales
Route::screen('locales', LocaleListScreen::class)
    ->name('translatable.locales')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('app.Locales'), route('translatable.locales')));

// Platform > Content > Locales > Locale
Route::screen('locales/{model}/edit', LocaleEditScreen::class)
    ->name('translatable.locales.edit')
    ->breadcrumbs(fn (Trail $trail, $model) => $trail
        ->parent('translatable.locales')
        ->push($model->name, route('translatable.locales.edit', $model)));

// Platform > Content > Locales > Create
Route::screen('locales/create', LocaleCreateScreen::class)
    ->name('translatable.locales.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('translatable.locales')
        ->push(__('Create'), route('translatable.locales.create')));
