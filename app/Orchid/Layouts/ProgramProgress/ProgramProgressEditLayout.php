<?php

namespace App\Orchid\Layouts\ProgramProgress;

use App\Models\Program;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ProgramProgressEditLayout extends Rows
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
            Relation::make('programprogress.program_id')
                ->title('Program')
                ->fromModel(Program::class, 'title')
                ->required(),

            TextArea::make('programprogress.writeup')
                ->title('Writeup'),
        ];
    }
}
