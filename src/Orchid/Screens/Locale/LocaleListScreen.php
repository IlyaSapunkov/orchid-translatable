<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens\Locale;

use Illuminate\Http\Request;
use IlyaSapunkov\Translatable\Models\Locale;
use IlyaSapunkov\Translatable\Orchid\Layouts\Locale\LocaleListTableLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class LocaleListScreen extends Screen
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'locales' => Locale::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('app.Locales');
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            LocaleListTableLayout::class,
        ];
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request): void
    {
        Locale::findOrFail($request->get('id'))->delete();

        Toast::success(__('app.You have successfully :action the record', ['action' => __('app.deleted')]));
    }

    /**
     * @param Request $request
     */
    public function select(Request $request): void
    {
        $model = Locale::findOrFail($request->get('id'));
        $response = redirect()->route('platform.locales');
        $response->withCookie(cookie('locale', $model->iso, 525600));

        Toast::success(__('app.You have successfully selected :name language', ['name' => $model->name]));

        $response->send();
    }
}
