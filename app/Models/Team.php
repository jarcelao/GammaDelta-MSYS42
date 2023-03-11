<?php

namespace App\Models;

use App\Models\Community;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Team extends Model
{
    use AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_leader_id',
        'community_id',
    ];

    /**
     * Get the team leader for the team.
     */
    public function teamLeader() {
        return $this->belongsTo(TeamMember::class, 'team_leader_id');
    }

    /**
     * Get the community that owns the team.
     */
    public function community() {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the team members for the team.
     */
    public function teamMembers() {
        return $this->hasMany(TeamMember::class);
    }
}
