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
        'lng',
        'lat',
        'zoom',
        'pitch',
        'bearing',
        'marker_list_id',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function list()
    {
        return $this->belongsTo(User::class, 'marker_list_id');
    }

}
