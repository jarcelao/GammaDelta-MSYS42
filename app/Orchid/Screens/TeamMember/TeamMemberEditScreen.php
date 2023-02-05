<?php

namespace App\Orchid\Screens\TeamMember;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class TeamMemberEditScreen extends Screen
{
    /**
     * @var TeamMember
     */
    public $teamMember;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(TeamMember $teamMember): iterable
    {
        return [
            'teamMember' => $teamMember,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->teamMember->exists ? 'Edit Team Member' : 'Create Team Member';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->method('createOrUpdate'),
            
            Button::make('Update')
                ->icon('check')
                ->method('createOrUpdate')
                ->canSee($this->teamMember->exists),

            ModalToggle::make('Delete')
                ->modal('confirmDelete')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->teamMember->exists),
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
            Layout::rows([
                Input::make('teamMember.name')
                    ->title('Name')
                    ->placeholder('Juan dela Cruz')
                    ->required(),
            ]),

            Layout::modal('confirmDelete', [
                Layout::rows([]),
            ])
            ->title('Are you sure?')
            ->applyButton('Delete'),
        ];
    }

    /**
     * Save the team member.
     * 
     * @param TeamMember $teamMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(TeamMember $teamMember, Request $request)
    {
        if ($teamMember->exists) {
            $teamMember->fill($request->get('teamMember'))->save();
            Toast::success('Team member updated.');
            return redirect()->route('platform.team');
        }

        $teamMember->fill($request->get('teamMember'))->save();
        Toast::success('Team member created.');
        return redirect()->route('platform.team');
    }

    /**
     * Remove the team member.
     * 
     * @param TeamMember $teamMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(TeamMember $teamMember)
    {
        $teamMember->delete();
        Toast::success('Team member deleted.');
        return redirect()->route('platform.team');
    }
}
