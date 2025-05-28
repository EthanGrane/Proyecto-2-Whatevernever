<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteMarker extends Model
{
    use HasFactory;
    protected $table = 'user_favorite_markers';

    protected $fillable = [
        'user_id',
        'marker_id',
        'favorite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marker()
    {
        return $this->belongsTo(Marker::class);
    }
}
