<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'post_id',
        'user_id',
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function downVotes(): HasMany
    {
        return $this->hasMany(ReplyVotes::class);
    }
    /**
     * Get the comments for the blog post.
     */
    public function upVotes(): HasMany
    {
        return $this->hasMany(ReplyVotes::class);
    }
}
