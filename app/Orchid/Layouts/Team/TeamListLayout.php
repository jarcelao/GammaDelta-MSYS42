<?php

namespace App\Orchid\Layouts\Team;

use App\Models\Team;
use Orchid\Screen\Actions\Link;
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
            TD::make('team_leader_id', 'Team Leader')
                ->filter()
                ->sort()
                ->render(function (Team $team) {
                    return e($team->teamLeader->name);
                }),
            TD::make('community.name', 'Community')
                ->filter()
                ->sort()
                ->render(function (Team $team) {
                    return Link::make($team->community->name)
                        ->route('platform.community.manage', $team->community->id);
                }),
            TD::make('')
                ->render(function (Team $team) {
                    return Link::make('')
                        ->route('platform.team.manage', $team->id)
                        ->icon('pencil');
                }),
        ];
    }
}
