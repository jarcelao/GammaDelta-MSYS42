<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'people_group_id',
        'team_leader',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'people_group_id',
        'team_leader',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'people_group_id',
        'team_leader',
    ];

    /**
     * Get the team members for the team.
     */
    public function teamMembers() {
        return $this->hasMany('App\Models\TeamMember');
    }
}
