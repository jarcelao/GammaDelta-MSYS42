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
                ->required(),

            Relation::make('project.community_id')
                ->title('Community')
                ->fromModel(Community::class, 'name')
                ->applyScope('ofUser', Auth::user())
                ->required(),

            TextArea::make('project.purpose')
                ->title('Purpose'),

            TextArea::make('project.indicators')
                ->title('Indicators'),

            Input::make('project.contact_person')
                ->title('Contact Person'),

            TextArea::make('project.contact_information')
                ->title('Contact Information'),

            TextArea::make('project.geographical_setting')
                ->title('Geographical Setting'),

            TextArea::make('project.economic_setting')
                ->title('Economic Setting'),

            TextArea::make('project.social_setting')
                ->title('Social Setting'),

            TextArea::make('project.religious_setting')
                ->title('Religious Setting'),
        ];
    }
}
