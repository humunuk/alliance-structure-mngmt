<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes;


use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Contracts\DataTableScope;

class AllianceStructureManagementScope implements DataTableScope
{
    /**
     * @param string|null $ability
     * @param int[]|null $requested_alliances
     */
    public function __construct(private readonly ?string $ability = null, private readonly ?array $requested_alliances = null)
    {
    }

    public function apply($query)
    {

        if ($this->requested_alliances != null) {
            $alliance_ids = collect($this->requested_alliances)->filter(fn($item) => Gate::allows($this->ability, [$item]));

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
            ->filter(function ($permission): bool {
                if (empty($this->ability))
                    return str_starts_with((string) $permission->title, 'alliance.');

                return $permission->title == $this->ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(fn($permission): bool => ! $permission->hasFilters())->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission): array {
            $filters = json_decode((string) $permission->pivot->filters, null, 512, JSON_THROW_ON_ERROR);

            return [
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        $alliance_ids = $map->pluck('alliances')->flatten()->toArray();

        return $query->whereIn("corporation_infos.alliance_id", $alliance_ids);
    }
}