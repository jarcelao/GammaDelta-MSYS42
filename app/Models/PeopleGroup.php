<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class PeopleGroup extends Model
{
    use AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country',
        'region',
        'language',
    ];

    /**
     * The attributes that can be filtered.
     */
    protected $allowedFilters = [
        'name',
        'country',
        'region',
        'language',
    ];
}
