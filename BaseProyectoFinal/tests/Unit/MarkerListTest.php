<?php

namespace Tests\Unit;

use App\Models\Marker;
use App\Models\MarkerList;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarkerListTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_create_marker_list()
    {
        $user = User::factory()->create();

        MarkerList::create([
            'name' => "Marker List Name",
            'owner_user_id' => $user->id,
            'emoji_identifier' => 25,
        ]);

        $this->assertDatabaseHas('marker_list', [
            'name' => "Marker List Name",
            'emoji_identifier' => 25,
            'owner_user_id' => $user->id,
        ]);
    }

    public function test_create_good_or_bad_marker_list()
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            MarkerList::create([
                'name' => "Marker List Name",
                'owner_user_id' => $user->id,
                'emoji_identifier' => 20 * $i,  // Hay un maximo de 50, mas de 50 es un error
            ]);
        }

        $this->assertCount(2, MarkerList::where("owner_user_id", $user->id)
            ->where("emoji_identifier", ">", 0)
            ->where("emoji_identifier", "<", 50)
            ->get());
    }
}
