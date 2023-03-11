<?php

namespace App\Orchid\Screens\Program;

use App\Models\Program;
use App\Orchid\Layouts\Program\ProgramEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
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
    public function query(Program $program): iterable
    {
        return [
            'program' => $program,
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
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Approve')
                ->icon('check'),
            Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate'),
            Button::make('Submit')
                ->icon('envelope'),
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
            ProgramEditLayout::class,
        ];
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
}
