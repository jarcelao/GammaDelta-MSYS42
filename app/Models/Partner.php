<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Partner extends Model
{
    use AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_progress_id',
        'point_person',
        'cluster',
    ];

    /**
     * Get the progress that owns the partner.
     */
    public function projectProgress()
    {
        return $this->belongsTo(ProjectProgress::class);
    }
}
