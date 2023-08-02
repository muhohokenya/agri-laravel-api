<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','title','description'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /*Count the votes for a post*/
    public function votes():hasMany
    {
        return $this->hasMany(PostVotes::class);
    }


}
