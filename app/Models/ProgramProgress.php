<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class ProgramProgress extends Model
{
    use AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'program_id',
        'writeup',
    ];

    /**
     * The attributes that can be sorted.
     */
    public $allowedSorts = [
        'created_at',
    ];

    /**
     * The attributes that can be filtered.
     */
    public $allowedFilters = [
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
