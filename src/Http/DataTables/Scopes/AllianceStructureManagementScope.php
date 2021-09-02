<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes;


use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Contracts\DataTableScope;

class AllianceStructureManagementScope implements DataTableScope
{
    /**
     * @var string|null
     */
    private $ability;
    /**
     * @var array|null
     */
    private $requested_alliances;

    /**
     * @param string|null $ability
     * @param int[]|null $allianceIds
     */
    public function __construct(?string $ability = null, ?array $allianceIds = null)
    {
        $this->ability = $ability;
        $this->requested_alliances = $allianceIds;
    }

    public function apply($query)
    {
        if (Gate::allows($this->ability)) {
            return $query->whereIn("corporation_infos.alliance_id", $this->requested_alliances);
        }

        return $query->whereIn("corporation_infos.alliance_id.corporation_id", []);
    }
}