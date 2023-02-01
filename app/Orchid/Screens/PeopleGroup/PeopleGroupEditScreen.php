<?php

namespace App\Orchid\Screens\PeopleGroup;

use App\Models\PeopleGroup;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PeopleGroupEditScreen extends Screen
{
    /**
     * @var PeopleGroup
     */
    public $peopleGroup;

    /**
     * Fetch data to be displayed on the screen.
     * 
     * @return array
     */
    public function query(PeopleGroup $peopleGroup): iterable
    {
        return [
            'peopleGroup' => $peopleGroup,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->peopleGroup->exists ? 'Edit People Group' : 'Create People Group';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Details such as name, country, region, and language.';
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
                ->canSee(!$this->peopleGroup->exists),

            Button::make('Update')
                ->icon('check')
                ->method('createOrUpdate')
                ->canSee($this->peopleGroup->exists),

            Button::make('Delete')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->peopleGroup->exists),
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
                Input::make('peopleGroup.name')
                    ->title('Name')
                    ->placeholder('Maranao')
                    ->required(),
                
                Input::make('peopleGroup.country')
                    ->title('Country')
                    ->placeholder('PH')
                    ->help('Enter a two-letter country code.')
                    ->required(),

                Input::make('peopleGroup.region')
                    ->title('Region')
                    ->placeholder('Marawi')
                    ->required(),

                Input::make('peopleGroup.language')
                    ->title('Language')
                    ->placeholder('Filipino')
                    ->required(),
            ]),
        ];
    }

    /**
     * Handle the form submission.
     *
     * @param PeopleGroup $peopleGroup
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(PeopleGroup $peopleGroup, Request $request)
    {
        if ($peopleGroup->exists) {
            $peopleGroup->fill($request->get('peopleGroup'))->save();
            Toast::success('People group updated');
            return redirect()->route('platform.people-group.edit', $peopleGroup);
        }

        $peopleGroup->fill($request->get('peopleGroup'))->save();
        Toast::success('People group created');
        return redirect()->route('platform.people-group');
    }

    /**
     * Handle the form submission.
     *
     * @param PeopleGroup $peopleGroup
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(PeopleGroup $peopleGroup)
    {
        $peopleGroup->delete();
        Toast::success('People group deleted');
        return redirect()->route('platform.people-group');
    }
}
