<?php

namespace App\Orchid\Screens\Community;

use App\Models\Community;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CommunityEditScreen extends Screen
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
        return $this->community->exists ? 'Edit Community' : 'Create Community';
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
                ->method('createOrUpdate')
                ->canSee(!$this->community->exists),

            Button::make('Update')
                ->icon('check')
                ->method('createOrUpdate')
                ->canSee($this->community->exists),

            ModalToggle::make('Delete')
                ->modal('confirmDelete')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->community->exists),
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
            Layout::rows([
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
            ]),

            Layout::modal('confirmDelete', [
                Layout::rows([]),
            ])
            ->title('Are you sure?')
            ->applyButton('Delete'),
        ];
    }

    /**
     * Handle the form submission.
     *
     * @param Community $community
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Community $community, Request $request)
    {
        if ($community->exists) {
            $community->fill($request->get('community'))->save();
            Toast::success('Community updated');
            return redirect()->route('platform.community.edit', $community);
        }

        $community->fill($request->get('community'))->save();
        Toast::success('Community created');
        return redirect()->route('platform.community');
    }

    /**
     * Handle the form submission.
     *
     * @param Community $community
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Community $community)
    {
        $community->delete();
        Toast::success('Community deleted');
        return redirect()->route('platform.community');
    }
}
