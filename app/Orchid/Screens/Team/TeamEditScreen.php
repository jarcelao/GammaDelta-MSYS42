<?php

namespace App\Orchid\Screens\Team;

use App\Models\Team;
use App\Models\TeamMember;
use App\Orchid\Layouts\Team\TeamEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TeamEditScreen extends Screen
{
    /**
     * @var Team
     */
    public $team;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Team $team): iterable
    {
        return [
            'team' => $team,
            'team_members' => $team->teamMembers()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->team->exists ? 'Edit Team' : 'Create Team';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('New Member')
                ->modal('newMember')
                ->icon('plus')
                ->method('createMember'),
            Button::make('Save')
                ->icon('check')
                ->method('createOrUpdate'),
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
            TeamEditLayout::class,
            Layout::modal('newMember', [
                Layout::rows([
                    Input::make('team_member.name')
                        ->title('Name')
                        ->placeholder('LAST, Given M.I.')
                        ->required(),
                ])
            ])
                ->title('New Team Member')
        ];
    }

    /**
     * Handle the creation of a new team member.
     * 
     * @param TeamMember $teamMember
     * @param Request $request
     */
    public function createMember(Request $request)
    {
        TeamMember::create($request->get('team_member'));
        Toast::info('Team member created.');
    }

    /**
     * Handle the creation of a new team.
     * 
     * @param Team $team
     * @param Request $request
     */
    public function createOrUpdate(Team $team, Request $request)
    {
        $team->fill($request->get('team'));

        $team->save();

        foreach ($request->get('team_members') as $teamMember) {
            $team->teamMembers()->save(TeamMember::where('id', $teamMember)->first());
        }

        Toast::info('Team saved.');
        
        return redirect()->route('platform.community.manage', $team->community);
    }
}
