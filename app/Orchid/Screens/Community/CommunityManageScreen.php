<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class CommunityManageScreen extends Screen
{
    /**
     * @var Community
     */
    public $community;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Community $community): iterable
    {
        return [
            'community' => $community,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->community->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(Layout::legend('community', [
                Sight::make('name', 'Name'),
                Sight::make('country', 'Country'),
                Sight::make('region', 'Region'),
                Sight::make('language', 'Language'),
                Sight::make('', 'Creator')
                    ->render(function () {
                        return '
                                ' . $this->community->user->name . '
                                <br>
                                ' . $this->community->user->email . '
                                <br>
                                ' . $this->community->user->contact . '
                            ';
                    }),
            ]))
                ->title('Community Details'),

            Layout::block(Layout::legend('', [
                Sight::make('', 'Team Leader')
                    ->render(function () {
                        if ($this->community->team) {
                            return $this->community->team->teamLeader->name;
                        }

                        return '';
                    }),
                Sight::make('', 'Team Members')
                    ->render(function () {
                        if ($this->community->team) {
                            return $this->community->team->teamMembers->map(function ($member) {
                                return $member->name;
                            })->implode('<br>');
                        }

                        return '';
                    }),
            ]))
                ->title('Team Details')
                ->commands(
                    Link::make('Manage Team')
                        ->route('platform.team.manage', ($this->community->team) ? $this->community->team->id : null)
                        ->icon('pencil')
                        ->canSee(Auth::user()->hasAccess('platform.community'))
                ),

            Layout::block(Layout::legend('community', [
                Sight::make('program.title', 'Program Title'),
                Sight::make('program.status', 'Program Status'),
            ]))
                ->title('Program Details')
                ->commands(
                    Link::make('Manage Program')
                        ->route('platform.program.manage', ($this->community->program) ? $this->community->program->id : null)
                        ->icon('pencil'),
                ),

        ];
    }
}
