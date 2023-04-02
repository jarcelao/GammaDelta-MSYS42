<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use App\Models\ProgramProgress;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CommunityManageScreen extends Screen
{
    /**
     * @var Community
     */
    public Community $community;

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
                Sight::make('team.teamLeader', 'Team Leader')
                    ->render(function () {
                        return $this->community->team ? $this->community->team->teamLeader->name : '';

                    }),
                Sight::make('', 'Team Members')
                    ->render(function () {
                        return $this->community->team ? $this->community->team->teamMembers->map(function ($member) {
                            return $member->name;
                        })->implode('<br>') : '';
                    }),
            ]))
                ->title('Team Details')
                ->commands(
                    Link::make('Manage Team')
                        ->route('platform.team.manage', ($this->community->team) ? $this->community->team->id : null)
                        ->icon('pencil')
                        ->canSee(Auth::user()->hasAccess('platform.community')),
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

            Layout::block(Layout::table('community.program.progress', [
                TD::make('created_at', 'Date')
                    ->render(function (ProgramProgress $programprogress) {
                        return Link::make($programprogress->created_at->format('d/m/Y'))
                            ->route('platform.program.report', $programprogress->id);
                    })
                    ->sort(),
                TD::make('status', 'Status')
                    ->render(function (ProgramProgress $programprogress) {
                        return $programprogress->status;
                    })
                    ->filter(TD::FILTER_SELECT, ProgramProgress::pluck('status', 'status')->toArray()),
            ]))
                ->title('Program Progress Reports')
                ->commands(
                    Link::make('Add Progress Report')
                        ->route('platform.program.report')
                        ->icon('plus')
                        ->canSee(Auth::user()->hasAccess('platform.community')),
                )
                ->canSee($this->community->program()->exists()),

            Layout::block(Layout::legend('community', [
                Sight::make('project.title', 'Project Title'),
                Sight::make('project.status', 'Project Status'),
            ]))
                ->title('Project Details')
                ->commands(
                    Link::make('Manage Project')
                        ->route('platform.project.manage', ($this->community->project) ? $this->community->project->id : null)
                        ->icon('pencil'),
                ),
        ];
    }
}
