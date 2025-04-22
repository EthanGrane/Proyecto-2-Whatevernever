<?php

namespace Tests\Unit;

use App\Models\Marker;
use App\Models\MarkerList;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarkerTest extends TestCase
{

    /**
     * A basic unit test example.
     */
    public function test_basic_test(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test database connection.
     */
    public function test_database_connection()
    {
        $this->assertNotNull(DB::connection());
    }

    /**
     * Test the creation of a marker.
     */
    public function test_create_marker()
    {
        // Crear un usuario para asociarlo al marcador
        $user = User::create([
            'name' => 'Test User',
            'username' => 'User',
            'desciption' => 'Desc',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        // Crear una lista de marcadores
        $markerList = MarkerList::factory()->create();

        // Crear un marcador
        $marker = Marker::create([
            'name' => 'Test Marker',
            'description' => 'Test Description',
            'lng' => 100.0,
            'lat' => 100.0,
            'zoom' => 10,
            'pitch' => 45,
            'bearing' => 180,
            'marker_list_id' => $markerList->id,
            'user_id' => $user->id,
        ]);

        // Verificar que el marcador se haya insertado correctamente en la base de datos
        $this->assertDatabaseHas('markers', [
            'name' => 'Test Marker',
            'description' => 'Test Description',
            'lng' => 100.0,
            'lat' => 100.0,
            'zoom' => 10,
            'pitch' => 45,
            'bearing' => 180,
            'marker_list_id' => $markerList->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_create_good_and_bad_markers()
    {
        // Crear un usuario para asociarlo a los marcadores
        $user = User::create([
            'name' => 'Test User',
            'username' => 'UserTest',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        // Crear una lista de marcadores
        $markerList = MarkerList::factory()->create();

        for ($i = 0; $i <= 4; $i++) {
            Marker::create([
                'name' => "Good Marker $i",
                'description' => "This is a good marker $i",
                'lng' => $i > 2 ? 50 : 500,     // valor maximo es -180 / 180
                'lat' => $i > 2 ? 50 : 500,     // valor maximo es -180 / 180
                'zoom' => 10,
                'pitch' => 45,
                'bearing' => 180,
                'marker_list_id' => $markerList->id,
                'user_id' => $user->id,
            ]);
        }

        $this->assertCount(2, Marker::where('user_id', $user->id)
        ->where('lng', '<', 180)
        ->where('lat', '<', 180)
        ->get());
    }


}
