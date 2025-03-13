<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_user_id',
    ];
}
