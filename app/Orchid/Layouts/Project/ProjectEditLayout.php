<?php

namespace App\Orchid\Layouts\Project;

use App\Models\Community;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ProjectEditLayout extends Rows
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
            Input::make('project.title')
                ->title('Title')
                ->maxlength(100)
                ->required(),

            Relation::make('project.community_id')
                ->title('Community')
                ->fromModel(Community::class, 'name')
                ->applyScope('ofUser', Auth::user())
                ->required(),

            TextArea::make('project.purpose')
                ->title('Purpose')
                ->maxlength(5000)
                ->required(),

            TextArea::make('project.indicators')
                ->title('Indicators')
                ->maxlength(5000)
                ->required(),

            Input::make('project.contact_person')
                ->maxlength(100)
                ->title('Contact Person'),

            TextArea::make('project.contact_information')
                ->maxlength(5000)
                ->title('Contact Information'),

            TextArea::make('project.geographical_setting')
                ->maxlength(5000)
                ->title('Geographical Setting'),

            TextArea::make('project.economic_setting')
                ->maxlength(5000)
                ->title('Economic Setting'),

            TextArea::make('project.social_setting')
                ->maxlength(5000)
                ->title('Social Setting'),

            TextArea::make('project.religious_setting')
                ->maxlength(5000)
                ->title('Religious Setting'),
        ];
    }
}
