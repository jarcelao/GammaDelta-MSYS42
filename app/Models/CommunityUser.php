<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class CommunityUser extends Model
{
    use AsSource;

    protected $table = 'community_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'community_id',
        'user_id',
    ];

    /**
     * Get the community that owns the user.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the user that belongs to the community.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
