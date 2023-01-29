<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramProgress extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'program_id',
        'writeup',
        'status',
    ];

    /**
     * Get the program that owns the progress report.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the budget requests for the progress report.
     */
    public function budgetRequests()
    {
        return $this->hasMany(ProgramProgressBudgetRequest::class);
    }
}
