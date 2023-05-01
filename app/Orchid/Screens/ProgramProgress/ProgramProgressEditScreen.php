<?php

namespace App\Orchid\Screens\ProgramProgress;

use App\Models\ProgramProgress;
use App\Models\ProgramProgressBudgetRequest;
use App\Orchid\Layouts\ProgramProgress\ProgramProgressEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProgramProgressEditScreen extends Screen
{
    /**
     * @var ProgramProgress
     */
    public $programprogress;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ProgramProgress $programprogress): iterable
    {
        return [
            'programprogress' => $programprogress,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->programprogress->exists ? 'Manage Program Report' : 'Create Program Report';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        if ($this->programprogress->exists) {
            if ($this->programprogress->status == 'For Approval') {
                return 'This program report is currently awaiting approval and cannot be edited.';
            } else if ($this->programprogress->status == 'Funded') {
                return 'This program report has been funded and cannot be edited.';
            }
        } elseif (Auth::user()->hasAccess('platform.community.approve')) {
            return 'A program report has not been submitted yet. Please ask the assigned coordinator to submit one.';
        }

        return '';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        $commandBar = [];

        if (Auth::user()->hasAccess('platform.community.approve')
            && $this->programprogress->exists
            && $this->programprogress->status == 'For Approval') {
            $commandBar[] = Button::make('Approve')
                ->icon('check')
                ->method('approve')
                ->confirm('Are you sure you want to approve this program report? This action cannot be undone.');
        }

        if (Auth::user()->hasAccess('platform.community')) {
            $commandBar[] = Button::make('Create')
                ->icon('plus')
                ->method('createOrUpdate')
                ->canSee(!$this->programprogress->exists);

            $commandBar[] = Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->programprogress->exists && $this->programprogress->status == 'Drafted');

            $commandBar[] = Button::make('Submit')
                ->icon('check')
                ->method('submit')
                ->confirm('Once the program report is submitted, it cannot be edited.')
                ->canSee($this->programprogress->exists && $this->programprogress->status == 'Drafted');
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

        if ((!$this->programprogress->exists || $this->programprogress->status == 'Drafted')
            && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProgramProgressEditLayout::class;
        } else {
            $layout[] = Layout::legend('programprogress', [
                Sight::make('', 'Program Title')
                    ->render(function () {
                        return $this->programprogress->program->title;
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
            Layout::table('programprogress.budgetRequests', [
                TD::make('', 'Account')
                    ->render(function (ProgramProgressBudgetRequest $budgetRequest) {
                        return $budgetRequest->account;
                    }),
                TD::make('', 'Amount')
                    ->render(function (ProgramProgressBudgetRequest $budgetRequest) {
                        return $budgetRequest->amount;
                    }),
                TD::make('', '')
                    ->render(function (ProgramProgressBudgetRequest $budgetRequest) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('deleteBudgetRequest', ['budgetRequest' => $budgetRequest->id])
                            ->canSee($this->programprogress->status == 'Drafted'
                                && Auth::user()->hasAccess('platform.community'));
                    })
            ]))
            ->title('Budget Requests')
            ->commands(
                ModalToggle::make('New Budget Request')
                    ->modal('newBudgetRequest')
                    ->icon('plus')
                    ->method('createBudgetRequest')
                    ->canSee($this->programprogress->status == 'Drafted'),
            )
            ->canSee($this->programprogress->exists);

        return $layout;
    }

    /**
     * Handle creating or updating program report
     *
     * @param ProgramProgress $programprogress
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(ProgramProgress $programprogress, Request $request)
    {
        $programprogress->fill($request->get('programprogress'));
        $programprogress->status = 'Drafted';
        $programprogress->save();

        Toast::info('Program report saved.');

        return redirect()->route('platform.community.manage', $programprogress->program->community);
    }

    /**
     * Handle submitting program report
     *
     * @param ProgramProgress $programprogress
     * @param Request $request
     * @return RedirectResponse
     */
    public function submit(ProgramProgress $programprogress, Request $request)
    {
        $programprogress->fill($request->get('programprogress'));
        $programprogress->status = 'For Approval';
        $programprogress->save();

        Toast::info('Program report submitted.');

        return redirect()->route('platform.community.manage', $programprogress->program->community);
    }

    /**
     * Handle approving program report
     *
     * @param ProgramProgress $programprogress
     * @return RedirectResponse
     */
    public function approve(ProgramProgress $programprogress)
    {
        $programprogress->status = 'Funded';
        $programprogress->save();

        Toast::info('Program report funded.');

        return redirect()->route('platform.community.manage', $programprogress->program->community);
    }

    /**
     * Handle creating budget request
     *
     * @param ProgramProgress $programprogress
     * @param Request $request
     */
    public function createBudgetRequest(ProgramProgress $programprogress, Request $request)
    {
        $budgetRequest = new ProgramProgressBudgetRequest;
        $budgetRequest->fill($request->get('budgetRequest'));
        $budgetRequest->program_progress_id = $programprogress->id;
        $budgetRequest->save();

        Toast::info('Budget request added.');
    }

    /**
     * Handle deleting budget request
     *
     * @param Request $request
     */
    public function deleteBudgetRequest(Request $request)
    {
        $budgetRequest = ProgramProgressBudgetRequest::find($request->get('budgetRequest'));
        $budgetRequest->delete();

        Toast::info('Budget request deleted.');
    }
}
