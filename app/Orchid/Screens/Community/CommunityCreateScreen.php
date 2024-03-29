<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use App\Models\CommunityUser;
use App\Orchid\Layouts\Community\CommunityEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CommunityCreateScreen extends Screen
{
    /**
     * Query data.
     */
    public function query(): array
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Community';
    }

    /**
     * The screen's action buttons
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->method('create'),
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
            CommunityEditLayout::class,
        ];
    }

    /**
     * Handle the form submission.
     *
     * @param Community $community
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Community $community, Request $request)
    {
        if (Community::where('name', $request->get('community')['name'])->first()) {
            Toast::error('Community name already exists');
            return redirect()->back();
        }

        $community->fill($request->get('community'));
        $community->user_id = $request->user()->id;
        $community->save();

        $communityUser = new CommunityUser();
        $communityUser->community_id = $community->id;
        $communityUser->user_id = $request->user()->id;
        $communityUser->save();

        Toast::success('Community created');
        return redirect()->route('platform.community');
    }
}
