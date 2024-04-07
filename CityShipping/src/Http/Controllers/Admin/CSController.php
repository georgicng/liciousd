<?php

namespace Gaiproject\CityShipping\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Gaiproject\CityShipping\Repositories\ShippingCityRepository;
use Gaiproject\CityShipping\DataGrids\Settings\CSDataGrid;

class CSController extends Controller
{

   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ShippingCityRepository $shippingCityRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        logger()->channel('custom')->info(json_encode(['request' => request()]));
        if (request()->ajax()) {
            return app(CSDataGrid::class)->toJson();
        }

        return view('admin::settings.cs.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code' => 'required|min:3|max:3|unique:currencies,code',
            'name' => 'required',
        ]);

        $data = request()->only([
            'code',
            'name',
            'symbol',
            'decimal'
        ]);

        $this->shippingCityRepository->create($data);

        return new JsonResponse([
            'message' => trans('admin::app.settings.cs.index.create-success'),
        ]);
    }

    /**
     * City Details
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id): JsonResponse
    {
        $city = $this->shippingCityRepository->findOrFail($id);

        return new JsonResponse($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): JsonResponse
    {
        $id = request()->id;

        $this->validate(request(), [
            'code' => ['required', 'unique:currencies,code,' . $id, new \Webkul\Core\Rules\Code],
            'name' => 'required',
        ]);

        $data = request()->only([
            'code',
            'name',
            'symbol',
            'decimal'
        ]);

        $this->shippingCityRepository->update($data, $id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.cs.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->shippingCityRepository->findOrFail($id);

        if ($this->shippingCityRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.cs.index.last-delete-error')
            ], 400);
        }

        try {
            $this->shippingCityRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.settings.cs.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.settings.cs.index.delete-failed')
        ], 500);
    }
}
