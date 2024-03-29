<?php

namespace App\Orchid\Screens\Program;

use App\Models\Program;
use App\Models\StorySet;
use App\Models\ProgramStorySet;
use App\Models\User;
use App\Notifications\ApproveGranted;
use App\Notifications\ApproveRequested;
use App\Orchid\Layouts\Program\ProgramEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProgramEditScreen extends Screen
{
    /**
     * @var Program
     */
    public $program;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Program $program, ProgramStorySet $programStorySet): iterable
    {
        return [
            'program' => $program,
            'storysets' => $programStorySet->where('program_id', $program->id)->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->program->exists ? 'Manage Program' : 'Create Program';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        if ($this->program->exists) {
            if ($this->program->status == 'For Approval') {
                return 'This program is currently awaiting approval and cannot be edited.';
            }

            else if ($this->program->status == 'Approved') {
                return 'This program has been approved and cannot be edited.';
            }
        } elseif (Auth::user()->hasAccess('platform.community.approve')) {
            return 'A program has not been submitted yet. Please ask the assigned coordinator to submit one.';
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

        if (Auth::user()->hasAccess('platform.community.approve') && $this->program->exists && $this->program->status == 'For Approval') {
            $commandBar[] = Button::make('Approve')
                ->icon('check')
                ->method('approve')
                ->confirm('Are you sure you want to approve this program? This action cannot be undone.');
        }

        if (Auth::user()->hasAccess('platform.community')) {
            if (!$this->program->exists) {
                $commandBar[] = Button::make('Create')
                    ->icon('plus')
                    ->method('createOrUpdate');
            } elseif ($this->program->status == 'Drafted') {
                $commandBar[] = Button::make('Save')
                    ->icon('pencil')
                    ->method('createOrUpdate');

                $commandBar[] = Button::make('Submit')
                    ->icon('check')
                    ->method('submit')
                    ->confirm('Once the program is submitted, it cannot be edited.');
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

        if (!$this->program->exists && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProgramEditLayout::class;
        } elseif ($this->program->status == 'Drafted' && Auth::user()->hasAccess('platform.community')) {
            $layout[] = ProgramEditLayout::class;
        } else {
            $layout[] = Layout::legend('program', [
                Sight::make('title'),
                Sight::make('purpose'),
                Sight::make('indicators'),
                Sight::make('start_date', 'Start Date'),
                Sight::make('end_date', 'End Date'),
                Sight::make('assumptions_and_risks', 'Assumptions and Risks'),
                Sight::make('inputs'),
                Sight::make('activities'),
                Sight::make('outputs'),
                Sight::make('why'),
            ]);
        }

        $layout[] = Layout::modal('addStorySet', [
            Layout::rows([
                Input::make('storyset.title')
                    ->title('Title')
                    ->maxlength(100)
                    ->required(),
                Input::make('storyset.references')
                    ->title('References')
                    ->maxlength(100)
                    ->required(),
                Input::make('programstoryset.theme')
                    ->title('Theme')
                    ->maxlength(100)
                    ->required(),
            ]),
        ])
            ->title('Add Story Set');

        $layout[] = Layout::block(
            Layout::table('storysets', [
                TD::make('', 'Title')
                    ->render(function (ProgramStorySet $programStorySet) {
                        return $programStorySet->storySet->title;
                    }),
                TD::make('', 'References')
                    ->render(function (ProgramStorySet $programStorySet) {
                        return $programStorySet->storySet->references;
                    }),
                TD::make('theme', 'Theme'),
                TD::make('', '')
                    ->render(function (ProgramStorySet $programStorySet) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('deleteStorySet', ['storySet' => $programStorySet->id])
                            ->canSee($this->program->status == 'Drafted'
                                && Auth::user()->hasAccess('platform.community'));
                    })
            ])
        )
            ->title('Story Sets')
            ->commands(
                ModalToggle::make('Add Story Set')
                    ->icon('plus')
                    ->modal('addStorySet')
                    ->method('addStorySet')
                    ->canSee($this->program->status == 'Drafted'
                        && Auth::user()->hasAccess('platform.community')),
            );

        return $layout;
    }

    /**
     * Handle creating or updating program
     *
     * @param Program $program
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Program $program, Request $request)
    {
        $program->fill($request->get('program'));
        $program->status = 'Drafted';
        $program->save();

        Toast::info('Program saved.');

        return redirect()->route('platform.community.manage', $program->community);
    }

    /**
     * Handle submitting program
     *
     * @param Program $program
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Program $program, Request $request)
    {
        $program->fill($request->get('program'));
        $program->status = 'For Approval';
        $program->save();

        Toast::info('Program submitted.');

        // NOTE: Ensure that the deployed application is set
        // to contain the role slug 'upper-management'

        $upperManagement = User::whereHas('roles', function ($query) {
            $query->where('slug', 'upper-management');
        })->get();

        Notification::send($upperManagement, new ApproveRequested($program));

        return redirect()->route('platform.community.manage', $program->community);
    }

    /**
     * Handle approving program
     *
     * @param Program $program
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Program $program)
    {
        $program->status = 'Approved';
        $program->save();

        Toast::info('Program approved.');

        $coordinator = $program->community->user()->get();

        Notification::send($coordinator, new ApproveGranted($program));

        return redirect()->route('platform.community.manage', $program->community);
    }

    /**
     * Handle adding story set
     *
     * @param Program $program
     * @param Request $request
     */
    public function addStorySet(Program $program, Request $request)
    {
        $storySet = new StorySet();
        $storySet->fill($request->get('storyset'));
        $storySet->save();

        $programStorySet = new ProgramStorySet();
        $programStorySet->program_id = $program->id;
        $programStorySet->story_set_id = $storySet->id;
        $programStorySet->theme = $request->input('programstoryset.theme');
        $programStorySet->save();

        Toast::info('Story set added.');
    }

    /**
     * Handle deleting story set
     *
     * @param Request $request
     */
    public function deleteStorySet(Request $request)
    {
        $programStorySet = ProgramStorySet::find($request->input('storySet'));
        $programStorySet->delete();

        Toast::info('Story set deleted.');
    }
}
