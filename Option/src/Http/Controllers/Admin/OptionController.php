<?php

namespace Gaiproject\Option\Http\Controllers\Admin;

use Gaiproject\Option\Http\Controllers\Controller;
use Gaiproject\Option\Repositories\OptionRepository;
use Webkul\Product\Repositories\ProductRepository;
use Gaiproject\Option\DataGrids\OptionDataGrid;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Rules\Code;
use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Requests\MassDestroyRequest;

class OptionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OptionRepository $optionRepository,
        protected ProductRepository $productRepository
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(OptionDataGrid::class)->toJson();
        }
        return view('admin::options.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::options.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code'          => ['required', 'not_in:type,attribute_family_id', 'unique:options,code', new Code()],
            'admin_name'    => 'required',
            'type'          => 'required',
        ]);

        $requestData =  request()->all();

        Event::dispatch('catalog.option.create.before');

        $option = $this->optionRepository->create($requestData);

        Event::dispatch('catalog.option.create.after', $option);

        session()->flash('success', trans('admin::app.catalog.options.create-success'));

        return redirect()->route('admin.options.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $option = $this->optionRepository->findOrFail($id);
        return view('admin::options.edit', compact('option'));
    }

     /**
     * Get values associated with an option.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getOptionValues($id)
    {
        $option = $this->optionRepository->findOrFail($id);

        return $option->values()->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'code'          => ['required', 'unique:options,code,' . $id, new Code],
            'admin_name'    => 'required',
            'type'          => 'required',
        ]);


        $requestData =  request()->all();

        Event::dispatch('catalog.option.update.before', $id);

        $option = $this->optionRepository->update($requestData, $id);

        Event::dispatch('catalog.option.update.after', $option);

        session()->flash('success', trans('option::app.admin.catalog.options.update-success'));

        return redirect()->route('admin.options.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): JsonResponse
    {

        try {
            Event::dispatch('catalog.option.delete.before', $id);

            $this->optionRepository->delete($id);

            Event::dispatch('catalog.option.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.catalog.options.delete-success')
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.options.delete-failed')
        ], 500);

    }

    /**
     * Remove the specified resources from database.
     *
     * @param MassDestroyRequest $massDestroyRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('catalog.option.delete.before', $index);

            $this->optionRepository->delete($index);

            Event::dispatch('catalog.option.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.options.index.datagrid.mass-delete-success')
        ]);
    }
}
