<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\DataTables;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Yajra\DataTables\Services\DataTable;

class AllianceStructuresDatatable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('corporation_name', function($row) {
                return view('alliance-structure-mngmt::partials.corporation', ['corporationStructure' => $row]);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName])->render();
            })
            ->editColumn('state', function ($row) {
                return ucfirst(str_replace('_', ' ', $row->state));
            })
            ->editColumn('fuel_expires', function ($row) {
                if ($row->fuel_expires)
                    return view('web::partials.date', ['datetime' => $row->fuel_expires])->render();

                return trans('web::seat.low_power');
            })
            ->editColumn('reinforce_hour', function ($row) {
                return view('web::corporation.structures.partials.reinforcement', compact('row'))->render();
            })
            ->editColumn('services', function ($row) {
                return view('web::corporation.structures.partials.services', compact('row'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::corporation.structures.buttons.action', compact('row'))->render();
            })
            ->filterColumn('services', function ($query, $keyword) {
                $query->whereHas('services', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('corporation_name', function($query, $keyword) {
                $query->where('corporation_infos.name', 'LIKE', "%$keyword%");
            })
            ->rawColumns(['action', 'type.typeName', 'fuel_expires', 'offline_estimate', 'reinforce_hour', 'services', 'corporation_name'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax([
                'data' => 'function(d) {
    d.filters = {};
    $("[data-filter-field].dt-filters.active").each(function(i, e) {
        var a = $(e);
        var field = a.data("filter-field");
        var value = a.data("filter-value");
        d.filters[field] = value
    }); }',
            ])
            ->columns($this->getColumns())
            ->addAction()
            ->addTableClass('table-striped table-hover')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationStructure::with('info', 'type', 'solar_system', 'services')
            ->join('corporation_infos', 'corporation_infos.corporation_id', '=', 'corporation_structures.corporation_id')
            ->addSelect([DB::raw('corporation_infos.name AS corporation_name'), 'corporation_structures.*']);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'corporation_name', 'title' => trans('alliance-structure-mngmt::alliance-structure-table.corporation')],
            ['data' => 'type.typeName', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'solar_system.name', 'title' => trans('web::seat.location')],
            ['data' => 'info.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'state', 'title' => trans('web::seat.state')],
            ['data' => 'fuel_expires', 'title' => trans('web::seat.offline')],
            ['data' => 'reinforce_hour', 'title' => trans('web::seat.reinforce_week_hour')],
            ['data' => 'services', 'title' => trans_choice('web::seat.services', 0), 'orderable' => false],
        ];
    }
}