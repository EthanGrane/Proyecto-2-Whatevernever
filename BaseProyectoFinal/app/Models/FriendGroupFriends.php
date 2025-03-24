<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendGroupFriends extends Model
{
    use HasFactory;

    protected $table = 'friend_groups_friends';

    protected $fillable = [
        'friends_id',
        'friend_group_id',
    ];
}
