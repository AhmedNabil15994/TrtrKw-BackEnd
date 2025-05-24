<?php

namespace Modules\Variation\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Core\Traits\DataTable;
use Modules\Variation\Http\Requests\Dashboard\OptionRequest;
use Modules\Variation\Transformers\Dashboard\OptionResource;
use Modules\Variation\Repositories\Dashboard\OptionRepository as Option;

class OptionController extends Controller
{
    protected $option;

    function __construct(Option $option)
    {
        $this->option = $option;
    }

    public function valuesByOptionId(Request $request)
    {
        if (!$request['values_ids'])
            return response()->json(['status' => false, 'message' => 'select option values'], 422);

        $valuesGrouped = $this->option->findByOptionValuesId($request['values_ids']);
        $data = [];
        foreach ($valuesGrouped as $key => $optionValues) {
            $data[] = $optionValues->pluck('id');
        }

        $variations = combinations($data);
        $res = [];

        if ($request->current_option) {
            foreach ($variations as $key => $variation) {
                if (!in_array($variation, $request->current_option)) {
                    array_push($res, $variation);
                }
            }
        } else {
            $res = $variations;
        }
        return view('catalog::dashboard.products.html.tabs_variation_blank', compact('res'));
    }

    public function index()
    {
        return view('variation::dashboard.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->option->QueryTable($request));

        $datatable['data'] = OptionResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('variation::dashboard.create');
    }

    public function store(OptionRequest $request)
    {
        try {
            $newFlag = Str::slug($request->title['en'], '_');
            if (in_array($newFlag, ['colors', 'sizes']))
                return Response()->json([false, __('variation::dashboard.options.validation.title.unique')]);

            $request->request->add(['flag' => $newFlag]);
            $create = $this->option->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('variation::dashboard.show');
    }

    public function edit($id)
    {
        $option = $this->option->findById($id);
        if (!$option)
            abort(404);

        return view('variation::dashboard.edit', compact('option'));
    }

    public function update(OptionRequest $request, $id)
    {
        try {
            $newFlag = Str::slug($request->title['en'], '_');
            if (in_array($newFlag, ['colors', 'sizes']))
                return Response()->json([false, __('variation::dashboard.options.validation.title.unique')]);

            $request->request->add(['flag' => $newFlag]);
            $update = $this->option->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $option = $this->option->checkIfHaveProductOptions($id);
            if (is_null($option))
                return Response()->json([false, __('variation::dashboard.options.validation.option_have_product_options')]);

            $deleted = null;
            if (!in_array($option->flag, ['colors', 'sizes']))
                $deleted = $this->option->delete($id);
            else
                return Response()->json([true, __('apps::dashboard.general.message_cannot_deleted')]);

            if ($deleted)
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->option->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
