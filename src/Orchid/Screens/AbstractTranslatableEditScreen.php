<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use IlyaSapunkov\Translatable\Models\Locale;
use IlyaSapunkov\Translatable\Types\TabTitleType;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

abstract class AbstractTranslatableEditScreen extends AbstractEditScreen
{
    /**
     * @return array|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            ...$this->getCommonLayout(),
            ...$this->getLocaleLayout(),
        ];
    }

    /**
     * @param $request
     *
     * @return RedirectResponse
     */
    protected function saveScreen($request): RedirectResponse
    {
        $id = $this->saveModel($request);

        $this->saveTranslations($request);

        $this->saveRelations($request);

        Toast::success(__('app.You have successfully :action the record', ['action' => __(isset($this->query['model.id']) ? 'app.updated' : 'app.created')]));

        return $this->redirect(['id' => $id]);
    }

    /**
     * @param Request $request
     */
    protected function saveTranslations(Request $request): void
    {
        $translations = $request->get('translations', []);

        foreach (Locale::all() as $locale) {
            $translation = $this->model->translations()->firstOrNew(['locale' => $locale->iso]);
            $translation->fill($translations[$locale->iso])->save();
        }
    }

    /**
     * @param mixed $locale
     * @param mixed $translation
     *
     * @return mixed
     */
    protected function getLocaleTab(mixed $locale, mixed $translation): mixed
    {
        return Layout::rows([
            Input::make("translations.$locale->iso.name")
                ->required($locale->iso == 'ru')
                ->title(__('directories.Name'))
                ->value($translation?->name),
        ]);
    }

    /**
     * @return mixed
     */
    protected function getLocaleLayout(): array
    {
        $tabs = [];
        $locales = Locale::query()
            ->orderBy('id')
            ->get();
        foreach ($locales as $locale) {
            $translation = $this->model->translations->firstWhere('locale', $locale->iso);
            $tabs["$locale->name"] = $this->getLocaleTab($locale, $translation);
        }

        return [
            Layout::tabs($tabs),
        ];
    }

    /**
     * @param string $default
     *
     * @return string
     */
    protected function getActiveTab(string $default = TabTitleType::MAIN): string
    {
        $tab = $this->model->exists ? request()->session()->get('tab', $default) : $this->getCreateTab();

        return TabTitleType::get($tab ?? $default);
    }

    /**
     * @return string
     */
    protected function getCreateTab(): string
    {
        return TabTitleType::CONTENT;
    }

    /**
     * @param array $tabs
     *
     * @return array
     */
    protected function orderTabs(array $tabs): array
    {
        if ($this->model->exists) {
            return $tabs;
        }

        $activeTab = $this->getActiveTab();
        $tabs = collect($tabs)->sortBy(function ($item, $key) use ($activeTab) {
            if ($key == $activeTab) {
                return -1;
            }

            return 0;
        });

        return $tabs->all();
    }
}
