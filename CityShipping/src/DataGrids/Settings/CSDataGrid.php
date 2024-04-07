<?php

namespace Gaiproject\CityShipping\DataGrids\Settings;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CSDataGrid extends DataGrid
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
        $queryBuilder = DB::table('shipping_cities')->addSelect('id', 'name', 'rate', 'status')
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
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'rate',
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.rate'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('cs::app.admin.settings.cs.index.datagrid.status'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('cs::app.admin.settings.cs.index.datagrid.active');
                } elseif ($value->status == 0) {
                    return trans('cs::app.admin.settings.cs.index.datagrid.inactive');
                }

                return trans('cs::app.admin.settings.cs.index.datagrid.draft');
            }
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.cs.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('cs::app.admin.settings.cs.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.cs.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.cs.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('cs::app.admin.settings.cs.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.cs.delete', $row->id);
                },
            ]);
        }
    }
}
