<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\Controllers;


use Humunuk\Seat\AllianceStructureManagement\Http\DataTables\AllianceStructuresDatatable;
use Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes\AllianceStructureManagementFuelScope;
use Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes\AllianceStructureManagementScope;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Web\Http\Controllers\Controller;

class AllianceStructureController extends Controller
{
    public function index(Alliance $alliance, AllianceStructuresDatatable $dataTable)
    {
        return $dataTable
            ->addScope(new AllianceStructureManagementScope('global.superuser', [$alliance->alliance_id]))
            ->addScope(new AllianceStructureManagementFuelScope(request()->input('filters.fuel')))
            ->render("alliance-structure-mngmt::structures", ['alliance' => $alliance]);
    }
}