<?php

namespace App\Orchid\Layouts\Community;

use App\Models\Community;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CommunityListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'communities';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->filter()
                ->sort(),
            TD::make('country', 'Country')
                ->filter(TD::FILTER_SELECT, Community::pluck('country', 'country')->toArray())
                ->sort(),
            TD::make('region', 'Region')
                ->filter(TD::FILTER_SELECT, Community::pluck('region', 'region')->toArray())
                ->sort(),
            TD::make('language', 'Language')
                ->filter(TD::FILTER_SELECT, Community::pluck('language', 'language')->toArray())
                ->sort(),
            TD::make('')
                ->render(function (Community $community) {
                    return Link::make('')
                        ->route('platform.community.manage', $community->id)
                        ->icon('pencil');
                }),
        ];
    }
}
