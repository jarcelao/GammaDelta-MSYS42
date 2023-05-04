<?php

namespace App\Orchid\Screens\ProjectProgress;

use App\Models\Partner;
use App\Models\ProjectProgress;
use App\Models\ProjectProgressBudgetRequest;
use App\Models\Workshop;
use App\Orchid\Layouts\ProjectProgress\ProjectProgressEditLayout;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProjectProgressEditScreen extends Screen
{
    /**
     * @var ProjectProgress
     */
    public $projectprogress;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ProjectProgress $projectprogress): iterable
    {
        return [
            'projectprogress' => $projectprogress,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->projectprogress->exists ? 'Manage Project Report' : 'Create Project Report';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        if ($this->projectprogress->exists) {
            if ($this->projectprogress->status == 'For Approval') {
                return 'This project report is currently awaiting approval and cannot be edited.';
            } elseif ($this->projectprogress->status == 'Funded') {
                return 'This project report has been funded and cannot be edited.';
            }
        } elseif (Auth::user()->hasAccess('platform.community.approve')) {
            return 'A project report has not been submitted yet. Please ask the assigned coordinator to submit one.';
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

        if (Auth::user()->hasAccess('platform.community.approve')
            && $this->projectprogress->exists
            && $this->projectprogress->status == 'For Approval') {
            $commandBar[] = Button::make('Approve')
                ->icon('check')
                ->method('approve')
                ->confirm('Are you sure you want to approve this project report? This action cannot be undone.');
        }

        if (Auth::user()->hasAccess('platform.community')) {
            $commandBar[] = Button::make('Create')
                ->icon('plus')
                ->method('createOrUpdate')
                ->canSee(!$this->projectprogress->exists);

            $commandBar[] = Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->projectprogress->exists && $this->projectprogress->status == 'Drafted');

            $commandBar[] = Button::make('Submit')
                ->icon('check')
                ->method('submit')
                ->confirm('Once the program report is submitted, it cannot be edited.')
                ->canSee($this->projectprogress->exists && $this->projectprogress->status == 'Drafted');
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

        if ((!$this->projectprogress->exists || $this->projectprogress->status == 'Drafted')
            && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProjectProgressEditLayout::class;
        } else {
            $layout[] = Layout::legend('projectprogress', [
               Sight::make('', 'Project Title')
                    ->render(function () {
                        return $this->projectprogress->project->title;
                    }),
               Sight::make('writeup', 'Writeup'),
            ]);
        }

        $layout[] = Layout::modal('newBudgetRequest', [
            Layout::rows([
                Input::make('budgetRequest.account')
                    ->title('Account')
                    ->required(),
                Input::make('budgetRequest.amount')
                    ->title('Amount')
                    ->mask(['alias' => 'currency'])
                    ->required(),
            ]),
        ])
        ->title('New Budget Request');

        $layout[] = Layout::block(
            Layout::table('projectprogress.budgetRequests', [
                TD::make('', 'Account')
                    ->render(function (ProjectProgressBudgetRequest $budgetRequest) {
                        return $budgetRequest->account;
                    }),
                TD::make('', 'Amount')
                    ->render(function (ProjectProgressBudgetRequest $budgetRequest) {
                        return $budgetRequest->amount;
                    }),
                TD::make('', '')
                    ->render(function (ProjectProgressBudgetRequest $budgetRequest) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('deleteBudgetRequest', ['budgetRequest' => $budgetRequest->id])
                            ->canSee($this->projectprogress->status == 'Drafted'
                                && Auth::user()->hasAccess('platform.community'));
                    }),
            ]))
            ->title('Budget Requests')
            ->commands(
                ModalToggle::make('New Budget Request')
                    ->modal('newBudgetRequest')
                    ->icon('plus')
                    ->method('createBudgetRequest')
                    ->canSee($this->projectprogress->status == 'Drafted'),
            )
            ->canSee($this->projectprogress->exists);

        $layout[] = Layout::modal('newWorkshop', [
           Layout::rows([
               Input::make('workshop.activity')
                   ->title('Activity')
                   ->required(),
               Input::make('workshop.schedule')
                   ->title('Schedule')
                   ->type('date'),
               Input::make('workshop.guest')
                   ->title('Guest'),
               Input::make('workshop.outcome')
                   ->title('Outcome'),
               Input::make('workshop.location')
                   ->title('Location'),
           ]),
        ])
        ->title('New Workshop');

        $layout[] = Layout::block(
            Layout::table('projectprogress.workshops', [
                TD::make('', 'Activity')
                    ->render(function (Workshop $workshop) {
                        return $workshop->activity;
                    }),
                TD::make('', 'Schedule')
                    ->render(function (Workshop $workshop) {
                        return $workshop->schedule;
                    }),
                TD::make('', 'Guest')
                    ->render(function (Workshop $workshop) {
                        return $workshop->guest;
                    }),
                TD::make('', 'Outcome')
                    ->render(function (Workshop $workshop) {
                        return $workshop->outcome;
                    }),
                TD::make('', 'Location')
                    ->render(function (Workshop $workshop) {
                        return $workshop->location;
                    }),
                TD::make('', '')
                    ->render(function (Workshop $workshop) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('deleteWorkshop', ['workshop' => $workshop->id])
                            ->canSee($this->projectprogress->status == 'Drafted'
                                && Auth::user()->hasAccess('platform.community'));
                    }),
            ]))
            ->title('Workshops')
            ->commands(
                ModalToggle::make('New Workshop')
                    ->modal('newWorkshop')
                    ->icon('plus')
                    ->method('createWorkshop')
                    ->canSee($this->projectprogress->status == 'Drafted'),
            )
            ->canSee($this->projectprogress->exists);

        $layout[] = Layout::modal('newPartner', [
            Layout::rows([
                Input::make('partner.point_person')
                    ->title('Point Person')
                    ->required(),
                Input::make('partner.cluster')
                    ->title('Cluster'),
            ]),
        ])
        ->title('New Partner');

        $layout[] = Layout::block(
            Layout::table('projectprogress.partners', [
                TD::make('', 'Point Person')
                    ->render(function (Partner $partner) {
                        return $partner->point_person;
                    }),
                TD::make('', 'Cluster')
                    ->render(function (Partner $partner) {
                        return $partner->cluster;
                    }),
                TD::make('', '')
                    ->render(function (Partner $partner) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('deletePartner', ['partner' => $partner->id])
                            ->canSee($this->projectprogress->status == 'Drafted'
                                && Auth::user()->hasAccess('platform.community'));
                    }),
            ]))
            ->title('Partners')
            ->commands(
                ModalToggle::make('New Partner')
                    ->modal('newPartner')
                    ->icon('plus')
                    ->method('createPartner')
                    ->canSee($this->projectprogress->status == 'Drafted'),
            )
            ->canSee($this->projectprogress->exists);

        return $layout;
    }

    /**
     * Handle creating or updating project report
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(ProjectProgress $projectprogress, Request $request): RedirectResponse
    {
        $projectprogress->fill($request->get('projectprogress'));
        $projectprogress->status = 'Drafted';
        $projectprogress->save();

        Toast::info('Project report saved.');

        return redirect()->route('platform.community.manage', $projectprogress->project->community);
    }

    /**
     * Handle submitting project report
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     * @return RedirectResponse
     */
    public function submit(ProjectProgress $projectprogress, Request $request): RedirectResponse
    {
        $projectprogress->fill($request->get('projectprogress'));
        $projectprogress->status = 'For Approval';
        $projectprogress->save();

        Toast::info('Project report submitted.');

        return redirect()->route('platform.community.manage', $projectprogress->project->community);
    }

    /**
     * Handle approving project report
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     * @return RedirectResponse
     */
    public function approve(ProjectProgress $projectprogress, Request $request): RedirectResponse
    {
        $projectprogress->status = 'Funded';
        $projectprogress->save();

        Toast::info('Project report approved.');

        return redirect()->route('platform.community.manage', $projectprogress->project->community);
    }

    /**
     * Handle creating budget request
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function createBudgetRequest(ProjectProgress $projectprogress, Request $request)
    {
        $budgetRequest = new ProjectProgressBudgetRequest();
        $budgetRequest->fill($request->get('budgetRequest'));
        $budgetRequest->project_progress_id = $projectprogress->id;
        $budgetRequest->amount = abs($budgetRequest->amount);
        $budgetRequest->save();

        Toast::info('Budget request created.');
    }

    /**
     * Handle deleting budget request
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function deleteBudgetRequest(ProjectProgress $projectprogress, Request $request)
    {
        $budgetRequest = ProjectProgressBudgetRequest::find($request->get('budgetRequest'));
        $budgetRequest->delete();

        Toast::info('Budget request deleted.');
    }

    /**
     * Handle creating workshop
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function createWorkshop(ProjectProgress $projectprogress, Request $request)
    {
        $workshop = new Workshop();
        $workshop->fill($request->get('workshop'));
        $workshop->project_progress_id = $projectprogress->id;
        $workshop->save();

        Toast::info('Workshop created.');
    }

    /**
     * Handle deleting workshop
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function deleteWorkshop(ProjectProgress $projectprogress, Request $request)
    {
        $workshop = Workshop::find($request->get('workshop'));
        $workshop->delete();

        Toast::info('Workshop deleted.');
    }

    /**
     * Handle creating partner
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function createPartner(ProjectProgress $projectprogress, Request $request)
    {
        $partner = new Partner();
        $partner->fill($request->get('partner'));
        $partner->project_progress_id = $projectprogress->id;
        $partner->save();

        Toast::info('Partner created.');
    }

    /**
     * Handle deleting partner
     *
     * @param ProjectProgress $projectprogress
     * @param Request $request
     */
    public function deletePartner(ProjectProgress $projectprogress, Request $request)
    {
        $partner = Partner::find($request->get('partner'));
        $partner->delete();

        Toast::info('Partner deleted.');
    }
}
