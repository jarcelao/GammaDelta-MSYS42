<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Program extends Model
{
    use AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'community_id',
        'title',
        'purpose',
        'indicators',
        'start_date',
        'end_date',
        'assumptions_and_risks',
        'inputs',
        'activities',
        'outputs',
        'outcomes',
        'why',
        'status',
    ];

    /**
     * Get the group assigned to the program.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the progress reports for the program.
     */
    public function progress()
    {
        return $this->hasMany(ProgramProgress::class);
    }

    /**
     * Scope the query to only include programs under a user's communities
     */
    public function scopeOfUser($query, $user)
    {
        if ($user->hasAnyAccess(['platform.systems.roles', 'platform.systems.users']))
            return $query;

        return $query->whereIn('community_id', $user->communities()->pluck('community_id')->toArray());
    }
}
