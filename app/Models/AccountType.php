<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{

    protected $hidden = ['created_at','updated_at'];
    use HasFactory;
}
