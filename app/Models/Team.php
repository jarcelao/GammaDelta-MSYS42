<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Team extends Model
{
    use AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_leader',
    ];

    /**
     * The attributes that can be filtered.
     */
    public $allowedFilters = [
        'team_leader',
    ];

    /**
     * The attributes that can be sorted.
     */
    public $allowedSorts = [
        'team_leader',
    ];

    /**
     * Get the community that owns the team.
     */
    public function community() {
        return $this->belongsTo('App\Models\Community');
    }

    /**
     * Get the team members for the team.
     */
    public function teamMembers() {
        return $this->hasMany('App\Models\TeamMember');
    }
}
