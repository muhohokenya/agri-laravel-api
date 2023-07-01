<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{

    protected $hidden = ['pivot'];
    use HasFactory;

    protected $guarded = [];

    protected $table = 'interest_user';
}
