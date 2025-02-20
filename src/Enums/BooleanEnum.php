<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Enums;

enum BooleanEnum: int
{
    case NO = 0;
    case YES = 1;

    /**
     * @return string
     */
    public function title(): string
    {
        return match ($this) {
            self::NO => __('app.No'),
            self::YES => __('app.Yes'),
        };
    }

    /**
     * @return string[]
     */
    public static function asSelect(): array
    {
        static $values = [];

        if (empty($values)) {
            foreach (self::cases() as $case) {
                $values[$case->value] = $case->title();
            }
        }

        return $values;
    }
}
