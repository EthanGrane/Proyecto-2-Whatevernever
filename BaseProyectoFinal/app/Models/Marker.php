<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Marker extends Pivot
{
    use HasFactory;

    protected $table = 'markers';

    protected $fillable = [
        'name',
        'description',
        'lng',
        'lat',
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
