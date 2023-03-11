<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
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
}
