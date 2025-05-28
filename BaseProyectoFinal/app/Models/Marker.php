<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;

    protected $table = 'markers';

    protected $fillable = [
        'name',
        'description',
        'lat',
        'lng',
        'zoom',
        'bearing',
        'pitch',
        'marker_list_id',
        'user_id',
        'emoji_identifier'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function lists()
    {
        return $this->belongsToMany(MarkerList::class, 'marker_list_markers', 'marker_id', 'marker_list_id');
    }


    // Marker Reviews
    public function reviews()
    {
        return $this->hasMany(MarkerReviews::class);
    }

    public function averageStars()
    {
        return $this->reviews()->avg('review_stars');
    }
}
