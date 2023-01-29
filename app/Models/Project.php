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
        'user_id',
        'group_id',
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
     * Get the user that owns the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group assigned to the project.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
