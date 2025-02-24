<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Types;

use Illuminate\Support\Collection;

abstract class AbstractType
{
    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        return collect(static::getTypes());
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public static function get(mixed $key): ?string
    {
        return static::all()->get($key);
    }

    /**
     * @return array
     */
    abstract protected static function getTypes(): array;
}
