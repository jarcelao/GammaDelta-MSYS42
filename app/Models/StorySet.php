<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StorySet extends Model
{
    use AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'references',
    ];

    /**
     * The programs that belong to the story set.
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_story_sets');
    }
}
