<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'project_id',
        'writeup',
        'status',
    ];

    /**
     * Get the project that owns the progress.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
