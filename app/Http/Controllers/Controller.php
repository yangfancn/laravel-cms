<?php

namespace App\Http\Controllers;

use App\Facades\InertiaMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;

    public function batchDelete(Model $model): RedirectResponse
    {
        $request = request();
        $failedIds = $model->whereIn('id', $ids = $request->post('ids'))->get()->map(function ($_model) {
            if (\Gate::inspect('delete', $_model)->denied()) {
                return $_model->id;
            }
            $_model->delete();

            return null;
        })->filter();

        if (count($ids) !== $failedIds->count()) {
            InertiaMessage::warning('Delete Resource Success!');
        }

        if ($failedIds->count()) {
            InertiaMessage::error("exclude Ids: {$failedIds->implode(',')}(unauthorized)");
        }

        return redirect()->back();
    }

    /**
     * for form select
     */
    public function selectOptionsLoad(Model $model, string $labelField = 'name', string $valueField = 'id'): JsonResponse
    {
        $request = request();
        $page = $request->integer('page');
        $pageSize = $request->integer('pageSize');

        if ($pageSize > 50) {
            $pageSize = 50;
        }
        $fields = ['id', "$labelField as label", "$valueField as value"];

        $data = $model
            ->when($search = $request->string('search'), fn ($builder) => $builder->where($labelField, 'like', "%$search%"))
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get($fields);

        $require = \is_array($require = $request->get('require')) ? $require : [$require];
        $require = array_diff($require, $data->pluck('id')->all());

        if ($require) {
            $data = $model
                ->newQuery()
                ->whereIn($valueField, $require)
                ->get($fields)
                ->concat($data);
        }

        return new JsonResponse([
            'options' => $data->setVisible(['label', 'value'])->all(),
        ]);
    }
}
