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
            'peopleGroups' => PeopleGroup::paginate(),
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
                ->icon('pencil')
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
                TD::make('name', 'Name'),
                TD::make('country', 'Country'),
                TD::make('region', 'Region'),
                TD::make('language', 'Language'),
                TD::make('Actions')
                    ->render(function (PeopleGroup $peopleGroup) {
                        return Link::make('Edit')
                            ->route('platform.people-group.edit', $peopleGroup->id);
                    }),
            ]),
        ];
    }
}
