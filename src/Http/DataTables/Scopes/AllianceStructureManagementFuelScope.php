<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes;


use Yajra\DataTables\Contracts\DataTableScope;


class AllianceStructureManagementFuelScope implements DataTableScope
{
    final public const LOW_POWER = 'low_power';
    final public const LESS_THAN_WEEK = 'less_than_week';
    final public const LESS_THAN_MONTH = 'less_than_month';

    public function __construct(private readonly ?string $filter)
    {
    }

    public function apply($query)
    {
        $filter = $this->parseFilter();

        if (!$filter) {
            return $query;
        }

        if ($filter === self::LOW_POWER) {
            return $query->whereNull('fuel_expires');
        }

        return $query->where('fuel_expires', '<', $filter);
    }

    private function parseFilter(): ?string
    {
        return match ($this->filter) {
            self::LOW_POWER => self::LOW_POWER,
            self::LESS_THAN_WEEK => now()->addWeek(),
            self::LESS_THAN_MONTH => now()->addMonth(),
            default => null,
        };
    }
}