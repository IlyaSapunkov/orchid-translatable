<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Orchid\Screens;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractTranslatableListScreen extends AbstractListScreen
{
    /**
     * @var mixed
     */
    protected $model;

    protected ?Builder $query = null;

    /**
     * @param Request $request
     *
     * @return iterable
     */
    public function query(Request $request): iterable
    {
        $this->query = $this->model::filters()
            ->with($this->getWith($this->model));

        $this->applyFilters($request);

        $this->query->orderBy('id', 'desc');

        return [
            'models' => $this->query->paginate(),
        ];
    }

    /**
     * @param Request $request
     */
    protected function applyFilters(Request $request): void
    {
        //        if ($request->has('filter.fieldName')) {
        //            $this->query->whereIn('fieldName', $request->input('filter.fieldName'));
        //        }

        $extraFields = $this->model->getTranslatableFields();

        foreach ($extraFields as $field) {
            $filterField = 'filter.' . $field;
            if ($request->has($filterField)) {
                $this->query->whereHas('translations', function ($q) use ($request, $field, $filterField): void {
                    $q->where('locale', app()->getLocale())
                        ->where($field, 'ilike', '%' . $request->input($filterField) . '%');
                });
            }
        }
    }

    /**
     * @return string[]
     *
     * @param mixed $model
     */
    protected function getWith($model): array
    {
        $result = [];
        if (method_exists($model, 'translations')) {
            $result[] = 'translations';
        }
        //        if (method_exists($model, 'relation')) {
        //            $result[] = 'relation.translations';
        //        }

        return $result;
    }
}
