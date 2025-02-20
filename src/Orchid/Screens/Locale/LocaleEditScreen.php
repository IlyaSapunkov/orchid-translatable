<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens\Locale;

use IlyaSapunkov\Translatable\Models\Locale;

class LocaleEditScreen extends LocaleCreateScreen
{
    /**
     * @return Locale
     */
    public $model;

    public function name(): ?string
    {
        return __('app.Edit :name', ['name' => trans_choice('locale.Locale', 2)]);
    }
}
