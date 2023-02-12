<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use App\Orchid\Layouts\Community\CommunityEditLayout;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Community $community, Request $request)
    {
        $community->fill($request->get('community'))->save();
        Toast::success('Community created');
        return redirect()->route('platform.community');
    }
}