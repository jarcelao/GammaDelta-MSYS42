<?php

namespace App\Orchid\Screens\Program;

use App\Models\Program;
use App\Models\StorySet;
use App\Models\ProgramStorySet;
use App\Orchid\Layouts\Program\ProgramEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function query(Program $program, StorySet $storySet): iterable
    {
        return [
            'program' => $program,
            'storysets' => $storySet->programs()->where('program_id', $program->id)->get(),
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
        }

        else {
            if (Auth::user()->hasAccess('platform.community.approve')) {
                return 'A program has not been created yet. Please ask the assigned coordinator to create one.';
            }
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

        if (Auth::user()->hasAccess('platform.community.approve') && $this->program->exists) {
            if ($this->program->status == 'For Approval') {
                $commandBar[] = Button::make('Approve')
                    ->icon('check')
                    ->method('approve')
                    ->confirm('Are you sure you want to approve this program? This action cannot be undone.');
            }
        }

        if (Auth::user()->hasAccess('platform.community')) {
            if (!$this->program->exists) {
                $commandBar[] = Button::make('Create')
                    ->icon('plus')
                    ->method('createOrUpdate');
            } else {
                if ($this->program->status == 'Drafted') {
                    $commandBar[] = ModalToggle::make('Add Story Set')
                        ->icon('plus')
                        ->modal('addStorySet')
                        ->method('addStorySet');

                    $commandBar[] = Button::make('Save')
                        ->icon('pencil')
                        ->method('createOrUpdate');

                    $commandBar[] = Button::make('Submit')
                        ->icon('check')
                        ->method('submit')
                        ->confirm('Once the program is submitted, it cannot be edited.');
                }
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
        } else {
            if ($this->program->status == 'Drafted' && Auth::user()->hasAccess('platform.community')) {
                $layout[] = ProgramEditLayout::class;
            } else {
                $layout[] = Layout::legend('', [
                    Sight::make('', 'Title')
                        ->render(function () {
                            return $this->program->title;
                        }),
                    Sight::make('', 'Purpose')
                        ->render(function () {
                            return $this->program->purpose;
                        }),
                    Sight::make('', 'Indicators')
                        ->render(function () {
                            return $this->program->indicators;
                        }),
                    Sight::make('', 'Start Date')
                        ->render(function () {
                            return $this->program->start_date;
                        }),
                    Sight::make('', 'End Date')
                        ->render(function () {
                            return $this->program->end_date;
                        }),
                    Sight::make('', 'Assumptions and Risks')
                        ->render(function () {
                            return $this->program->assumptions_and_risks;
                        }),
                    Sight::make('', 'Inputs')
                        ->render(function () {
                            return $this->program->inputs;
                        }),
                    Sight::make('', 'Activities')
                        ->render(function () {
                            return $this->program->activities;
                        }),
                    Sight::make('', 'Outputs')
                        ->render(function () {
                            return $this->program->outputs;
                        }),
                    Sight::make('', 'Outcomes')
                        ->render(function () {
                            return $this->program->outcomes;
                        }),
                    Sight::make('', 'Why')
                        ->render(function () {
                            return $this->program->why;
                        }),
                ]);
            }

            $layout[] = Layout::modal('addStorySet', [
                Layout::rows([
                    Input::make('storyset.title')
                        ->title('Title')
                        ->required(),
                    Input::make('storyset.references')
                        ->title('References')
                        ->required(),
                    Input::make('programstoryset.theme')
                        ->title('Theme')
                        ->required(),
                ])
            ])
                ->title('Add Story Set');

            $layout[] = Layout::table('storysets', [
                TD::make('title', 'Title'),
                TD::make('references', 'References'),
                TD::make('', 'Theme')
                    ->render(function (StorySet $storySet) {
                        return $storySet->pivot->theme;
                    }),
            ])
                ->title('Story Sets');
        }

        return $layout;
    }

    /**
     * Handle creating or updating program
     * 
     * @param Program $program
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Program $program, Request $request)
    {
        $program->fill($request->get('program'));
        $program->status = 'For Approval';
        $program->save();

        Toast::info('Program submitted.');

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
        $programStorySet->theme = $request->get('programstoryset.theme');
        $programStorySet->save();

        Toast::info('Story set added.');
    }
}
