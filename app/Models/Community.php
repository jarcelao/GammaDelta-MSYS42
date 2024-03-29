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
     * Get the user for the community.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the team for the community.
     */
    public function team()
    {
        return $this->hasOne('App\Models\Team');
    }

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

    /**
     * Scope a query to only include communities of a given user.
     */
    public function scopeOfUser($query, $user)
    {
        if ($user->hasAnyAccess(['platform.systems.roles', 'platform.systems.users'])) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}
