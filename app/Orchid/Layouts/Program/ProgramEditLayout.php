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
                ->required(),
            
            Relation::make('program.community_id')
                ->title('Community')
                ->fromModel(Community::class, 'name')
                ->required(),

            TextArea::make('program.purpose')
                ->title('Purpose'),

            TextArea::make('program.indicators')
                ->title('Indicators'),

            Input::make('program.start_date')
                ->title('Start Date')
                ->type('date'),

            Input::make('program.end_date')
                ->title('End Date')
                ->type('date'),

            TextArea::make('program.assumptions_and_risks')
                ->title('Assumptions and Risks'),

            TextArea::make('program.inputs')
                ->title('Inputs'),

            TextArea::make('program.activities')
                ->title('Activities'),

            TextArea::make('program.outputs')
                ->title('Outputs'),
            
            TextArea::make('program.outcomes')
                ->title('Outcomes'),

            TextArea::make('program.why')
                ->title('Why'),
        ];
    }
}
