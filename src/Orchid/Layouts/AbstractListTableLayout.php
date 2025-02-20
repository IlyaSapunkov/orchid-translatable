<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Layouts;

use IlyaSapunkov\Translatable\Enums\BooleanEnum;
use IlyaSapunkov\Translatable\Models\OrchidLocale;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use ReflectionException;

abstract class AbstractListTableLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'models';

    /**
     * @return array
     *
     * @throws ReflectionException
     */
    public function columns(): array
    {
        return [
            TD::make('', __('app.Actions'))
                ->alignLeft()
                ->cantHide()
                ->render(function (mixed $model) {
                    return $this->getTableActions($model)
                        ->set('align', 'justify-left-end align-items-left')
                        ->autoWidth()
                        ->render();
                }),
            TD::make('id', __('app.ID'))
                ->filter(TD::FILTER_NUMERIC)
                ->sort(),

            TD::make('active', __('app.Active'))
                ->filter(TD::FILTER_SELECT)
                ->sort()
                ->filterOptions(BooleanEnum::asSelect())
                ->usingComponent(Boolean::class, true: BooleanEnum::YES->title(), false: BooleanEnum::NO->title()),

            TD::make('created_at', __('app.Created'))
                ->usingComponent(DateTimeSplit::class, upperFormat: __('formats.date'), lowerFormat: 'H:i', tz: 'Europe/Moscow')
                ->sort()
                ->filter(TD::FILTER_DATE_RANGE),

            TD::make('updated_at', __('app.Last edit'))
                ->usingComponent(DateTimeSplit::class, upperFormat: __('formats.date'), lowerFormat: 'H:i', tz: 'Europe/Moscow')
                ->sort()
                ->filter(TD::FILTER_DATE_RANGE),
        ];
    }

    /**
     * @return string
     */
    abstract protected function getRoute(): string;

    /**
     * @param string $action
     *
     * @return string
     */
    protected function getRouteAction(string $action): string
    {
        return $this->getRoute() . '.' . $action;
    }

    /**
     * @param OrchidLocale $model
     *
     * @return Group
     */
    protected function getTableActions(mixed $model): Group
    {
        return Group::make([
            Link::make('')
                ->icon('bs.pencil')
                ->route($this->getRouteAction('edit'), ['model' => $model->id]),

            Button::make('')
                ->icon('bs.trash3')
                ->method('destroy', [
                    'id' => $model->id,
                ])
                ->confirm(__('Are you sure you want to delete this item?')),
        ]);
    }
}
