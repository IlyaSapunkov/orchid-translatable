<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Layouts\Locale;

use IlyaSapunkov\Translatable\Models\OrchidLocale;
use IlyaSapunkov\Translatable\Orchid\Layouts\AbstractListTableLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\TD;
use ReflectionException;

class LocaleListTableLayout extends AbstractListTableLayout
{
    /**
     * @var string
     */
    public $target = 'locales';

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    public function columns(): array
    {
        return array_merge(
            parent::columns(),
            [
                TD::make('name', __('locale.Name'))
                    ->filter(Input::make())
                    ->render(function (OrchidLocale $model) {
                        return Link::make($model->name)
                            ->route('translatable.locales.edit', $model);
                    }),
                TD::make('name_short', __('locale.Short name'))
                    ->filter(Input::make()),

                TD::make('iso', __('locale.ISO'))
                    ->filter(Input::make()),
            ]
        );
    }

    /**
     * @param mixed $model
     *
     * @return Group
     */
    protected function getTableActions(mixed $model): Group
    {
        $activeLocale = $model->iso === app()->getLocale();

        return Group::make([
            Button::make('')
                ->icon(($activeLocale ? 'bs.pin-fill' : 'bs.pin'))
                ->method('select', [
                    'id' => $model->id,
                ])
                ->disabled($activeLocale),

            Link::make('')
                ->icon('bs.pencil')
                ->route('translatable.locales.edit', ['model' => $model->id]),
        ]);
    }

    /**
     * @return string
     */
    protected function getRoute(): string
    {
        return 'translatable.locales';
    }
}
