<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Types;

class TabTitleType extends AbstractType
{
    public const MAIN = 'main';
    public const CONTENT = 'content';

    public static function getTypes(): array
    {
        return [
            self::MAIN => __('types.tabTitle.Settings'),
            self::CONTENT => __('types.tabTitle.Content'),
        ];
    }
}
