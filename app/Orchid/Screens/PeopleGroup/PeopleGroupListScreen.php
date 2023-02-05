<?php

namespace App\Orchid\Screens\PeopleGroup;

use App\Orchid\Layouts\PeopleGroup\PeopleGroupListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
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
        return 'People Group';
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
            PeopleGroupListLayout::class,
        ];
    }
}
