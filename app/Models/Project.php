<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Project extends Model
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
        'contact_person',
        'contact_information',
        'geographical_setting',
        'economic_setting',
        'social_setting',
        'religious_setting',
        'status',
    ];

    /**
     * Get the community assigned to the project.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the progress for the project.
     */
    public function progress()
    {
        return $this->hasMany(ProjectProgress::class);
    }

    /**
     * Scope the query to only include projects under a user's communities
     */
    public function scopeOfUser($query, $user)
    {
        if ($user->hasAnyAccess(['platform.systems.roles', 'platform.systems.users']))
            return $query;

        return $query->whereIn('community_id', $user->communities()->pluck('community_id')->toArray());
    }
}
