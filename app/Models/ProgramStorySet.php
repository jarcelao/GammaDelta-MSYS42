<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStorySet extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'program_id',
        'story_set_id',
        'theme',
    ];

    /**
     * Get the program that owns the story set.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the story set that belongs to the program.
     */
    public function storySet()
    {
        return $this->belongsTo(StorySet::class);
    }
}
