<?php

namespace App\Services\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Inertia\Response;
use Laravel\Scout\Searchable;

/**
 * @todo 待验证 全文搜索的检索可能还存在预加载的问题， with 需要通过 回调函数 query(Builder) 来实现，可以通过 $this->model->eagerLoadRelations()
 * 来获取预加载的类，然后用 query回调函数加进去
 */

/**
 * DataTable class provides a way to build and render data tables with various features like pagination, searching, and sorting.
 * It allows adding columns, row actions, and select options, and can handle both Eloquent models and collections.
 * @package App\Services\DataTable
 */
class DataTable
{
    protected Collection $columns;

    protected ?string $createRoute = null;

    protected ?string $batchDeleteRoute = null;

    protected array $rowActions = [];

    protected Request $request;

    protected int $perPage;

    protected array $selectOptions = [];

    public function __construct(
        protected Model|Builder|Collection $model,
        protected ?string $title = null,
        protected ?string $searchField = null,
        protected ?string $resourceCollection = null,
    ) {
        if ($this->resourceCollection !== null) {
            try {
                $reflection = new \ReflectionClass($this->resourceCollection);
            } catch (\ReflectionException $e) {
                $reflection = null;
            }
            if (! $reflection || ! $reflection->isSubclassOf(ResourceCollection::class)) {
                throw new \InvalidArgumentException("The provided resourceCollection must be a subclass of Illuminate\Http\Resources\Json\ResourceCollection.");
            }
        }

        $this->columns = collect();
        $this->request = request();
        $this->perPage = $this->request->integer('perPage', 12);
    }

    public function addColumn(Column $column): self
    {
        $this->columns->push($column->make());

        return $this;
    }

    public function createAction(string $createRoute): self
    {
        $this->createRoute = $createRoute;

        return $this;
    }

    public function addSelectByRelation(string $relation, string $labelColum = 'name'): self
    {

        try {
            $relationModel = $this->model->getRelation($relation)->getModel();
        } catch (RelationNotFoundException) {
            throw new \InvalidArgumentException("The relation '$relation' does not exist.");
        }

        $primaryKey = $relationModel->getKeyName();

        $options = $relationModel->select([$primaryKey.' as value', $labelColum.' as label'])->get()->toArray();

        $selected = $this->request->integer($relation) ?: null;

        if ($selected) {
            $this->model = $this->model->whereHas($relation, fn (Builder $query) => $query->where($relationModel->getKeyName(), $selected));
        }

        $this->selectOptions[] = [
            'name' => $relation,
            'options' => $options,
            'modelValue' => $selected,
        ];

        return $this;
    }

    public function batchDeleteAction(string $batchDeleteRoute): self
    {
        $this->batchDeleteRoute = $batchDeleteRoute;

        return $this;
    }

    public function addRowAction(Button $button): self
    {
        $this->rowActions[] = $button->make();

        return $this;
    }

    public function make(?string $page = null): Response|array
    {
        $builder = $this->model;

        // 直接传入集合时，没有任何交互（不做分页，搜索，排序）
        if ($builder instanceof Collection) {
            $options = [
                'title' => $this->title,
                'columns' => $this->columns,
                'data' => [
                    'data' => $builder,
                ],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => $builder->count(),
                    'total' => $builder->count(),
                ],
                'filter' => null,
                'sortBy' => null,
                'descending' => null,
                'allowSearch' => false,
            ];
        } else {
            if (($search = $this->request->string('search')->value()) && $this->searchField) {
                // diff scout or normal model
                if (in_array(Searchable::class, (new \ReflectionClass($this->model))->getTraitNames())) {
                    $builder = $builder->search($search);
                } else {
                    $builder = $builder->whereLike($this->searchField, "%$search%");
                }
            }

            if ($sortBy = $this->request->string('sortBy')->value()) {
                $builder = $builder->orderBy($sortBy, $this->request->boolean('descending') ? 'desc' : 'asc');
            }

            $data = $builder->paginate($this->perPage);

            $pagination = [
                'currentPage' => $data->currentPage(),
                'perPage' => $data->perPage(),
                'total' => $data->total(),
            ];

            if ($this->resourceCollection) {
                $data = new $this->resourceCollection($data);
            }

            $options = [
                'title' => $this->title,
                'columns' => $this->columns,
                'data' => $data,
                'pagination' => $pagination,
                'filter' => $search,
                'sortBy' => $sortBy,
                'descending' => $this->request->boolean('descending'),
                'allowSearch' => (bool) $this->searchField,
                'selectOptions' => $this->selectOptions,
            ];
        }

        if ($this->createRoute) {
            $options['createRoute'] = $this->createRoute;
        }

        if ($this->batchDeleteRoute) {
            $options['batchDeleteRoute'] = $this->batchDeleteRoute;
        }

        if ($this->rowActions) {
            $options['rowButtons'] = $this->rowActions;
            $options['columns']->push([
                'type' => 'rowActions',
                'label' => 'Actions',
                'align' => 'left',
                'field' => '',
                'name' => 'actions',
                'sortable' => false,
            ]);
        }

        return $page ? $options : inertia('DataTable', compact('options'));
    }
}
