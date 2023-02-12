<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use App\Orchid\Layouts\Community\CommunityEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CommunityManageScreen extends Screen
{
    /**
     * @var Community
     */
    public $community;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Community $community): iterable
    {
        return [
            'community' => $community,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->community->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            
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
            Layout::block(CommunityEditLayout::class)
                ->title('Community Details')
                ->commands(
                    Button::make('Save')
                        ->icon('check')
                        ->method('saveDetails')
                ),
        ];
    }

    /**
     * Handle the form submission.
     *
     * @param Community $community
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDetails(Community $community, Request $request)
    {
        $community->fill($request->get('community'))->save();
        Toast::success('Community updated');
        return redirect()->route('platform.community');
    }
}
