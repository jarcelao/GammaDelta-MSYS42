<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
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
}
