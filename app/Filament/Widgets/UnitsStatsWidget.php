<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnitsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Units'), Unit::count())
                ->description(__('All property units'))
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make(__('Rental Units Count'), Unit::rental()->count())
                ->description(__('Units for rent'))
                ->descriptionIcon('heroicon-m-key')
                ->color('info'),

            Stat::make(__('Sale Units Count'), Unit::sale()->count())
                ->description(__('Units for sale'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make(__('Construction Units Count'), Unit::underConstruction()->count())
                ->description(__('Under construction'))
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('warning'),
        ];
    }
}
