<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

abstract class AbstractEditScreen extends Screen
{
    /**
     * @return array|Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Cancel')->route($this->getRout()),
            Button::make('Save')
                ->type(Color::BASIC)
                ->icon('bs.check-circle')
                ->method('save'),
            Button::make('Delete')
                ->type(Color::BASIC)
                ->icon('bs.trash')
                ->canSee($this->query['model.id'] ?? false)
                ->method('destroy')
                ->confirm(__('Are you sure you want to delete this item?')),
        ];
    }

    /**
     * @return array|Layout[]
     */
    public function layout(): iterable
    {
        return [
            ...$this->getCommonLayout(),
        ];
    }

    /**
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $this->model->delete();

        Toast::success(__('app.You have successfully :action the record', ['action' => __('app.deleted')]));

        return redirect()->route($this->getRout());
    }

    /**
     * @return string
     */
    abstract public function getRout(): string;

    /**
     * @param $request
     *
     * @return RedirectResponse
     */
    protected function saveScreen($request): RedirectResponse
    {
        $id = $this->saveModel($request);

        $this->saveRelations($request);

        Toast::success(__('app.You have successfully :action the record', ['action' => __(isset($this->query['model.id']) ? 'app.updated' : 'app.created')]));

        return Redirect::route($this->getRout());
    }

    /**
     * @return mixed
     */
    abstract protected function getCommonLayout(): array;

    /**
     * @param array $params
     *
     * @return RedirectResponse
     */
    protected function redirect(array $params = []): RedirectResponse
    {
        return redirect()->route($this->getRout());
    }

    /**
     * @param Request $request
     */
    protected function saveRelations(Request $request): void
    {
        $this->model->attachments()->sync($request->input('model.attachments', []));
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    protected function saveModel(Request $request): int
    {
        $this->model->fill($request->get('model'));
        $this->model->updated_at = now();
        $this->model->save();

        return $this->model->id;
    }
}
