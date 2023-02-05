<?php

namespace App\Orchid\Screens\TeamMember;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class TeamMemberCreateScreen extends Screen
{
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
        return 'Create Team Member';
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
                ->method('create'),
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
        ];
    }

    /**
     * Save the team member.
     * 
     * @param TeamMember $teamMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(TeamMember $teamMember, Request $request)
    {
        $teamMember->fill($request->get('teamMember'))->save();
        return redirect()->route('platform.index');
    }
}
