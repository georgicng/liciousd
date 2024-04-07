<?php

namespace Gaiproject\Pickup\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Gaiproject\Pickup\Repositories\PickupCentreRepository;
use Gaiproject\Pickup\DataGrids\Settings\PickupDataGrid;

class PickupController extends Controller
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PickupCentreRepository $pickupCentreRepository)
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
            return app(PickupDataGrid::class)->toJson();
        }

        return view('admin::settings.pickup.index');
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

        $this->pickupCentreRepository->create($data);

        return new JsonResponse([
            'message' => trans('admin::app.settings.pickup.index.create-success'),
        ]);
    }

    /**
     * Centre Details
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id): JsonResponse
    {
        $centre = $this->pickupCentreRepository->findOrFail($id);

        return new JsonResponse($centre);
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

        $this->pickupCentreRepository->update($data, $id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.pickup.index.update-success'),
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
        $this->pickupCentreRepository->findOrFail($id);

        if ($this->pickupCentreRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.pickup.index.last-delete-error')
            ], 400);
        }

        try {
            $this->pickupCentreRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.settings.pickup.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.settings.pickup.index.delete-failed')
        ], 500);
    }
}
