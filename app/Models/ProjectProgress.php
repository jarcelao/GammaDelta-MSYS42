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

    /**
     * Get the budget requests for the progress.
     */
    public function budgetRequests()
    {
        return $this->hasMany(ProjectProgressBudgetRequest::class);
    }

    /**
     * Get the workshops for the progress.
     */
    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }

    /**
     * Get the partners for the progress.
     */
    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
}
