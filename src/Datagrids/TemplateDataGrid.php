<?php

namespace Webkul\SizeChart\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class TemplateDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('size_charts')->addSelect('id', 'template_name', 'template_code', 'template_type', 'image_path');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('sizechart::app.sizechart.template.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'template_name',
            'label'      => trans('sizechart::app.sizechart.template.template-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'template_code',
            'label'      => trans('sizechart::app.sizechart.template.template-code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'template_type',
            'label'      => trans('sizechart::app.sizechart.template.template-type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->template_type == 'configurable')
                    return trans('sizechart::app.sizechart.template.configurable-type');
                else
                    return trans('sizechart::app.sizechart.template.simple-type');
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title' => trans('sizechart::app.edit'),
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'sizechart.admin.index.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title'        => trans('sizechart::app.delete'),
            'method'       => 'POST',
            'route'        => 'sizechart.admin.index.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete'),
            'icon'         => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('sizechart::app.sizechart.template.delete'),
            'action' => route('sizechart.admin.index.massdelete'),
            'method' => 'POST'
        ]);
    }
}