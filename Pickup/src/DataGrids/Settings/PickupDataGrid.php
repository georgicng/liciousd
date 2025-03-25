<?php

namespace Gaiproject\Pickup\DataGrids\Settings;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class PickupDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('pickup_centres')->addSelect('id', 'name', 'address', 'city', 'rate', 'status', 'country_code', 'state_code');
        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('pickup::app.admin.settings.pickup.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('pickup::app.admin.settings.pickup.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'city',
            'label'      => trans('pickup::app.admin.settings.pickup.index.datagrid.city'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'rate',
            'label'      => trans('pickup::app.admin.settings.pickup.index.datagrid.rate'),
            'type'       => 'price',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('pickup::app.admin.settings.pickup.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('pickup::app.admin.settings.pickup.index.datagrid.active');
                } elseif ($value->status == 0) {
                    return trans('pickup::app.admin.settings.pickup.index.datagrid.inactive');
                }

                return trans('pickup::app.admin.settings.pickup.index.datagrid.draft');
            },
        ]);

        $this->addColumn([
            'index'      => 'country_code',
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.country'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => core()->countries()->map(fn($item) => ['label' => $item->name, 'value' => $item->code])->toArray(),
                ],
            ],
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'visibility' => false
        ]);

        $country = request()->has('filters') && is_array(request()->query('filters')['country_code']) ? request()->query('filters')['country_code'][0] : config('app.default_country');

        $this->addColumn([
            'index'      => 'state_code',
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.state'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => core()->states($country)->map(fn($item) => ['label' => $item->default_name, 'value' => $item->code])->toArray(),
                ],
            ],
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'visibility' => false
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.pickup.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('pickup::app.admin.settings.pickup.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.pickup.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.pickup.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('pickup::app.admin.settings.pickup.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.pickup.delete', $row->id);
                },
            ]);
        }
    }
}
