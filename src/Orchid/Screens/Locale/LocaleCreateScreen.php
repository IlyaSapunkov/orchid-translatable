<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens\Locale;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use IlyaSapunkov\Translatable\Models\OrchidLocale;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LocaleCreateScreen extends Screen
{
    /**
     * @var OrchidLocale
     */
    public $model;

    /**
     * Query data.
     *
     * @param OrchidLocale $model
     *
     * @return array
     */
    public function query(OrchidLocale $model): array
    {
        return [
            'model' => $model,
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers.
     */
    public function name(): ?string
    {
        return __('app.Creating a :name', ['name' => trans_choice('locale.Locale', 2)]);
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Cancel')->route('platform.locales'),
            Button::make('Save')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('model.name')
                    ->title(__('locale.Name'))
                    ->placeholder(__('locale.Enter locale name')),
                Input::make('model.name_short')
                    ->title(__('locale.Short name'))
                    ->popover('ISO 3166')
                    ->placeholder('locale.Enter locale short name')
                    ->help(__('locale.Short name for menu')),
                Input::make('model.iso')
                    ->title(__('locale.ISO'))
                    ->popover('ISO 639')
                    ->placeholder(__('locale.Enter locale iso'))
                    ->help('locale.Locale url prefix like "ru", "en", etc'),
                Switcher::make('model.active')
                    ->title(__('app.Active'))
                    ->sendTrueOrFalse(),
            ]),
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Request $request): RedirectResponse
    {
        $action = ($this->model->exists ? 'app.updated' : 'app.created');

        $request
            ->validate($this->rules($this->model), [], $this->attributes());

        $this->model
            ->fill($request->get('model'))
            ->save();

        Toast::success(__('app.You have successfully :action the record', ['action' => __($action)]));

        return redirect()->route('platform.locales');
    }

    /**
     * @param OrchidLocale $model
     *
     * @return array
     */
    protected function rules(OrchidLocale $model): array
    {
        return [
            'model.name' => ['required', 'max:32'],
            'model.name_short' => ['required', 'max:16'],
            'model.iso' => [
                'required',
                'max:8',
                'alpha:ascii',
                Rule::unique(OrchidLocale::class, 'iso')->ignore($model),
            ],
            'model.active' => ['nullable'],
        ];
    }

    /**
     * Получить пользовательские имена атрибутов для формирования ошибок валидатора.
     *
     * @return array<string, string>
     */
    protected function attributes(): array
    {
        return [
            'model.name' => __('locale.Name'),
            'model.name_short' => __('locale.Short name'),
            'model.iso' => __('locale.ISO'),
            'model.active' => __('locale.Active'),
        ];
    }
}
