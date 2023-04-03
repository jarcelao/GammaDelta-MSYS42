<?php

namespace App\Orchid\Layouts\Community;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CommunityEditLayout extends Rows
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
            Input::make('community.name')
                    ->title('Name')
                    ->placeholder('Maranao')
                    ->required(),
                
            Input::make('community.country')
                ->title('Country')
                ->placeholder('PH')
                ->help('Enter a two-letter country code.')
                ->required(),

            Input::make('community.region')
                ->title('Region')
                ->placeholder('Marawi')
                ->required(),

            Input::make('community.language')
                ->title('Language')
                ->placeholder('Filipino')
                ->required(),
        ];
    }
}
