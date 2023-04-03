<?php

namespace App\Orchid\Layouts\ProjectProgress;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ProjectProgressEditLayout extends Rows
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
            Relation::make('projectprogress.project_id')
                ->title('Project')
                ->fromModel(Project::class, 'title')
                ->applyScope('ofUser', Auth::user())
                ->required(),

            TextArea::make('projectprogress.writeup')
                ->title('Writeup'),
        ];
    }
}
