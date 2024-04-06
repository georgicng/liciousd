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
        $country = request('country');
        $state = request('state');
        $queryBuilder = DB::table('pickup_centres')->addSelect('id', 'name', 'address', 'city', 'rate', 'status')
            ->where('country_code', $country)
            ->where('state_code', $state);

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
            'label'      => trans('admin::app.settings.pickup.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.settings.pickup.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'city',
            'label'      => trans('admin::app.settings.pickup.index.datagrid.city'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'rate',
            'label'      => trans('admin::app.settings.pickup.index.datagrid.rate'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.settings.pickup.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.settings.pickup.index.datagrid.active');
                } elseif ($value->status == 0) {
                    return trans('admin::app.settings.pickup.index.datagrid.inactive');
                }

                return trans('admin::app.settings.pickup.index.datagrid.draft');
            },
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
                'title'  => trans('admin::app.settings.pickup.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.pickup.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.pickup.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.pickup.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.pickup.delete', $row->id);
                },
            ]);
        }
    }
}
