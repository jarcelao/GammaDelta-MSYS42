<?php

namespace App\Orchid\Layouts\TeamMember;

use App\Models\TeamMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TeamMemberListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'teamMembers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->filter(),
            TD::make('')
                ->render(function (TeamMember $teamMember) {
                    return Link::make('')
                        ->route('platform.team-member.edit', $teamMember->id)
                        ->icon('pencil');
                }),
        ];
    }
}
