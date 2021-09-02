<?php


namespace Humunuk\Seat\AllianceStructureManagement\Http\DataTables\Scopes;


use Yajra\DataTables\Contracts\DataTableScope;


class AllianceStructureManagementFuelScope implements DataTableScope
{
    const LOW_POWER = 'low_power';
    const LESS_THAN_WEEK = 'less_than_week';
    const LESS_THAN_MONTH = 'less_than_month';

    /**
     * @var string
     */
    private $filter;

    public function __construct(?string $filter)
    {
        $this->filter = $filter;
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
        switch ($this->filter) {
            case self::LOW_POWER:
                return self::LOW_POWER;
            case self::LESS_THAN_WEEK:
                return now()->addWeek();
            case self::LESS_THAN_MONTH:
                return now()->addMonth();
            default:
                return null;
        }
    }
}