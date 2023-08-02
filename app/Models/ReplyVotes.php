<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyVotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply_id','user_id','vote'
    ];
}
