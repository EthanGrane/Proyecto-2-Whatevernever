<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marker;
use App\Models\MarkerList;
use App\Models\MarkerListMarkers;

class Marker_MarkerList_Maker_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markers = Marker::where('user_id', 1)
            ->orderBy('id', 'asc')
            ->get();


        $markerList = MarkerList::create([
            'name' => 'Lista del usuario 1',
            'owner_user_id' => 1,
            'emoji_identifier' => 5,
        ]);

        $markerList->markers()->attach($markers->pluck('id'));
    }
}
