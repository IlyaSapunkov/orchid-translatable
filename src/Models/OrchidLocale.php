<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Models;

use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int $id
 * @property string $name
 * @property string $name_short
 * @property string $iso
 * @property bool $active
 */
class OrchidLocale extends Locale
{
    use asSource;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_short',
        'iso',
        'active',
    ];
}
