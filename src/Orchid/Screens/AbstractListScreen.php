<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens;

use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

abstract class AbstractListScreen extends Screen
{
    /**
     * @var mixed
     */
    protected $model = null;

    /**
     * @param Request $request
     *
     * @return iterable
     */
    public function query(Request $request): iterable
    {
        $query = $this->model::filters();
        $query->orderBy('id', 'desc');

        return [
            'models' => $query->paginate(),
        ];
    }

    /**
     * @return array|Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route($this->getRout()),
        ];
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request): void
    {
        $this->model::findOrFail($request->get('id'))->delete();

        Toast::success(__('app.You have successfully :action the record', ['action' => __('app.deleted')]));
    }

    /**
     * @return string
     */
    abstract public function getRout(): string;
}
