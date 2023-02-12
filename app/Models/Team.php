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
        'team_leader',
    ];

    /**
     * Get the team members for the team.
     */
    public function teamMembers() {
        return $this->hasMany('App\Models\TeamMember');
    }

    /**
     * Get the program progress for the team.
     */
    public function programProgress()
    {
        return $this->hasMany('App\Models\TeamProgramProgress');
    }
}
