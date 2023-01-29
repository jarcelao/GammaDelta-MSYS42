<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramProgressBudgetRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'program_progress_id',
        'account',
        'amount',
    ];
}
