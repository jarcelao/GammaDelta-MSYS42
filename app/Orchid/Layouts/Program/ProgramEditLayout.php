<?php

namespace App\Orchid\Layouts\Program;

use Illuminate\Support\Facades\Auth;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

use App\Models\Community;

class ProgramEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('program.title')
                ->title('Title')
                ->maxlength(100)
                ->required(),

            Relation::make('program.community_id')
                ->title('Community')
                ->fromModel(Community::class, 'name')
                ->applyScope('ofUser', Auth::user())
                ->required(),

            TextArea::make('program.purpose')
                ->title('Purpose')
                ->maxlength(5000)
                ->required(),

            TextArea::make('program.indicators')
                ->title('Indicators')
                ->maxlength(5000)
                ->required(),

            Input::make('program.start_date')
                ->title('Start Date')
                ->maxlength(100)
                ->type('date'),

            Input::make('program.end_date')
                ->title('End Date')
                ->maxlength(100)
                ->type('date'),

            TextArea::make('program.assumptions_and_risks')
                ->title('Assumptions and Risks')
                ->maxlength(5000)
                ->required(),

            TextArea::make('program.inputs')
                ->maxlength(5000)
                ->title('Inputs'),

            TextArea::make('program.activities')
                ->maxlength(5000)
                ->title('Activities'),

            TextArea::make('program.outputs')
                ->maxlength(5000)
                ->title('Outputs'),

            TextArea::make('program.outcomes')
                ->maxlength(5000)
                ->title('Outcomes'),

            TextArea::make('program.why')
                ->maxlength(5000)
                ->title('Why'),
        ];
    }
}
