<?php

namespace App\Orchid\Screens\PeopleGroup;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use App\Models\PeopleGroup;

class PeopleGroupListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'peopleGroups' => PeopleGroup::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'People Groups';
    }

    /**
     * The description of the screen displayed in the header.
     */
    public function description(): ?string
    {
        return 'Browse people groups';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create')
                ->icon('plus')
                ->route('platform.people-group.edit'),
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
            Layout::table('peopleGroups', [
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
        ]),
        ];
    }
}
