<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamProgramProgress extends Model
{
    protected $table = 'team_program_progress';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'team_id',
        'program_progress_id',
        'active_status',
        'cycle_level',
    ];

    /**
     * The attributes for which you can use filters in url.
     * 
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'team_id',
        'program_progress_id',
        'active_status',
        'cycle_level',
    ];
}
