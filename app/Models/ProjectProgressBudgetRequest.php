<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgressBudgetRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_progress_id',
        'account',
        'amount',
    ];
}
