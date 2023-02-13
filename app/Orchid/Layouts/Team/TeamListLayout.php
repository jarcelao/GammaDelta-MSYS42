<?php

namespace App\Orchid\Layouts\Team;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TeamListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'teams';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('team_leader', 'Team Leader')
                ->filter()
                ->sort(),
            TD::make('community.name', 'Community')
                ->filter()
                ->sort(),
        ];
    }
}
