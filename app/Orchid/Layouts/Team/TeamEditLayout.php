<?php

namespace App\Orchid\Layouts\Team;

use App\Models\Community;
use App\Models\TeamMember;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class TeamEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('team.team_leader')
                ->title('Team Leader')
                ->placeholder('LAST, Given M.I.')
                ->required(),

            Relation::make('team.community_id')
                ->title('Community')
                ->fromModel(Community::class, 'name')
                ->required(),

            Relation::make('team_members.')
                ->title('Team Members')
                ->fromModel(TeamMember::class, 'name')
                ->multiple(),
        ];
    }
}
