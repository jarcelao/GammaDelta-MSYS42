<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Orchid\Layouts\Project\ProjectEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProjectEditScreen extends Screen
{
    /**
     * @var Project
     */
    public $project;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Project $project): iterable
    {
        return [
            'project' => $project,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->project->exists ? 'Manage Project' : 'Create Project';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        if ($this->project->exists) {
            if ($this->project->status == 'For Approval') {
                return 'This project is currently awaiting approval and cannot be edited.';
            }

            if ($this->project->status == 'Approved') {
                return 'This project has been approved and cannot be edited.';
            }
        } elseif (Auth::user()->hasAccess('platform.community.approve')) {
            return 'A project has not been submitted yet. Please ask the assigned coordinator to submit one.';
        }

        return '';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $commandBar = [];

        if (Auth::user()->hasAccess('platform.community.approve') && $this->project->exists && $this->project->status == 'For Approval') {
            $commandBar[] = Button::make('Approve')
                ->icon('check')
                ->method('approve')
                ->confirm('Are you sure you want to approve this project? This action cannot be undone.');
        }

        if (Auth::user()->hasAccess('platform.community')) {
            if (!$this->project->exists) {
                $commandBar[] = Button::make('Create')
                    ->icon('pencil')
                    ->method('createOrUpdate');
            } elseif ($this->project->status == 'Drafted') {
                $commandBar[] = Button::make('Save')
                    ->icon('pencil')
                    ->method('createOrUpdate');

                $commandBar[] = Button::make('Submit')
                    ->icon('check')
                    ->method('submit')
                    ->confirm('Once this project has been submitted, it cannot be edited.');
            }
        }

        return $commandBar;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $layout = [];

        if (!$this->project->exists && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProjectEditLayout::class;
        } elseif ($this->project->status == 'Drafted' && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProjectEditLayout::class;
        } else {
            $layout[] = Layout::legend('project', [
                Sight::make('title'),
                Sight::make('purpose'),
                Sight::make('indicators'),
                Sight::make('contact_person', 'Contact Person'),
                Sight::make('contact_information', 'Contact Information'),
                Sight::make('geographical_setting', 'Geographical Setting'),
                Sight::make('economic_setting', 'Economic Setting'),
                Sight::make('social_setting', 'Social Setting'),
                Sight::make('religious_setting', 'Religious Setting'),
            ]);
        }

        return $layout;
    }

    /**
     * Handle creating or updating project
     *
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Request $request) {
        $project->fill($request->get('project'));
        $project->status = 'Drafted';
        $project->save();

        Toast::info('Project saved.');

        return redirect()->route('platform.community.manage', $project->community);
    }

    /**
     * Handle submitting project
     *
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Project $project, Request $request)
    {
        $project->fill($request->get('project'));
        $project->status = 'For Approval';
        $project->save();

        Toast::info('Project submitted.');

        return redirect()->route('platform.community.manage', $project->community);
    }

    /**
     * Handle approving project
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Project $project)
    {
        $project->status = 'Approved';
        $project->save();

        Toast::info('Project approved.');

        return redirect()->route('platform.community.manage', $project->community);
    }
}
