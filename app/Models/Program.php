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
        'user_id',
        'group_id',
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
     * Get the user that owns the program.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group assigned to the program.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the progress reports for the program.
     */
    public function progress()
    {
        return $this->hasMany(ProgramProgress::class);
    }
}
