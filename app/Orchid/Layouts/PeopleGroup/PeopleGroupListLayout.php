<?php

namespace App\Orchid\Layouts\PeopleGroup;

use App\Models\PeopleGroup;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PeopleGroupListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'peopleGroups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                    ->filter(),
            TD::make('country', 'Country')
                ->filter(TD::FILTER_SELECT, PeopleGroup::pluck('country', 'country')->toArray()),
            TD::make('region', 'Region')
                ->filter(TD::FILTER_SELECT, PeopleGroup::pluck('region', 'region')->toArray()),
            TD::make('language', 'Language')
                ->filter(TD::FILTER_SELECT, PeopleGroup::pluck('language', 'language')->toArray()),
            TD::make('')
                ->render(function (PeopleGroup $peopleGroup) {
                    return Link::make('')
                        ->route('platform.people-group.edit', $peopleGroup->id)
                        ->icon('pencil');
                }),
        ];
    }
}
