<?php

namespace App\Orchid\Screens\Community;

use App\Orchid\Layouts\Community\CommunityListLayout;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->hasAccess('platform.community.approve')) {
            return [
                'communities' => Community::with('program', 'project')
                    ->filters()
                    ->paginate(),
            ];
        }

        if (Auth::user()->hasAccess('platform.community')) {
            return [
                'communities' => Community::where('user_id', auth()->user()->id)
                    ->with('program', 'project')
                    ->paginate(),
            ];
        }

        return [];
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
        if (Auth::user()->hasAccess('platform.community')) {
            return [
                Link::make('Create')
                    ->icon('plus')
                    ->route('platform.community.create'),
            ];
        }

        return [];
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
