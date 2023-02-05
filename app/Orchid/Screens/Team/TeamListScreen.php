<?php

namespace App\Orchid\Screens\Team;

use App\Models\TeamMember;
use App\Orchid\Layouts\TeamMember\TeamMemberListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TeamListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'teamMembers' => TeamMember::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Team';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Member')
                ->icon('plus')
                ->route('platform.team-member.edit'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            TeamMemberListLayout::class,
        ];
    }
}
