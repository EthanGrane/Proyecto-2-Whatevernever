<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkerList extends Model
{
    use HasFactory;

    protected $table = 'marker_list';

    protected $fillable = [
        'name',
        'owner_user_id',
        'emoji_identifier'
    ];

    public function markers()
    {
        return $this->belongsToMany(Marker::class, 'marker_list_markers', 'marker_list_id', 'marker_id');
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function markerCount()
    {
        return $this->markers()->count();
    }

    public function averageStars()
    {
        return $this->markers()->with('reviews')->get()
            ->flatMap(fn($marker) => $marker->reviews)
            ->avg('review_stars');
    }
}
