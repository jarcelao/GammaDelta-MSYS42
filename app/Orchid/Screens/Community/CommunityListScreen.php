<?php

namespace App\Orchid\Screens\Community;

use App\Orchid\Layouts\Community\CommunityListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Models\Community;

class CommunityListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'communities' => Community::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Community Dashboard';
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
                ->route('platform.community.create'),
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
            CommunityListLayout::class,
        ];
    }
}
