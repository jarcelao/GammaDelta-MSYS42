<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class TeamMember extends Model
{
    use AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the team for the team member.
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
