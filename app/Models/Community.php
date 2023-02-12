<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Community extends Model
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

    /**
     * The attributes that can be sorted.
     */
    protected $allowedSorts = [
        'name',
        'country',
        'region',
        'language',
    ];

    /**
     * Get the program for the community.
     */
    public function program()
    {
        return $this->hasOne('App\Models\Program');
    }

    /**
     * Get the project for the community.
     */
    public function project()
    {
        return $this->hasOne('App\Models\Project');
    }
}
