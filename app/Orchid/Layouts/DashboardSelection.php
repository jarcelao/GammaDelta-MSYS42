<?php

namespace App\Orchid\Layouts;

use App\Orchid\Filters\ProgramStatusFilter;
use App\Orchid\Filters\ProjectStatusFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class DashboardSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            ProgramStatusFilter::class,
            ProjectStatusFilter::class
        ];
    }
}
