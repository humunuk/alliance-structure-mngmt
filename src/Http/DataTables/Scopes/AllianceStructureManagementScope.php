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

        if ($this->requested_alliances != null) {
            $alliance_ids = collect($this->requested_alliances)->filter(function ($item) {
                return Gate::allows($this->ability, [$item]);
            });

            return $alliance_ids->count() == count($this->requested_alliances) ?
                $query->whereIn("corporation_infos.alliance_id", $this->requested_alliances) :
                $query->whereIn("corporation_infos.alliance_id.corporation_id", []);
        }

        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) {
                if (empty($this->ability))
                    return strpos($permission->title, 'alliance.') === 0;

                return $permission->title == $this->ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        $alliance_ids = $map->pluck('alliances')->flatten()->toArray();

        return $query->whereIn("corporation_infos.alliance_id", $alliance_ids);
    }
}