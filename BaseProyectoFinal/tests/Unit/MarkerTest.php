<?php

namespace Tests\Unit;

use App\Models\Marker;
use App\Models\User;
use App\Models\MarkerList;
use App\Models\MarkerReviews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_marker_belongs_to_user()
    {
        // Crea el usuario con factory (esta vez si existe una factory)
        $user = User::factory()->create();
        // Crea el markador con la factory
        $marker = Marker::factory()->create(['user_id' => $user->id]);

        //  Verifica que el userId del marcador coincide con el del usuario
        $this->assertEquals($user->id, $marker->owner->id);
    }

    public function test_marker_belongs_to_marker_list()
    {
        // Lo mismo pero con el modelo
        $list = MarkerList::factory()->create();
        $marker = Marker::factory()->create(['marker_list_id' => $list->id]);

        $this->assertEquals($list->id, $marker->list->id);
    }

    public function test_marker_has_reviews()
    {
        $marker = Marker::factory()->create();
        $review = MarkerReviews::factory()->create(['marker_id' => $marker->id]);

        // Comprueba que la review se encuentra en la lista de reviews
        $this->assertTrue($marker->reviews->contains($review));
    }

    public function test_marker_average_stars()
    {
        $marker = Marker::factory()->create();

        MarkerReviews::factory()->create(['marker_id' => $marker->id, 'review_stars' => 4]);
        MarkerReviews::factory()->create(['marker_id' => $marker->id, 'review_stars' => 2]);

        // Comprueba que el promedio es 3.0
        $this->assertEquals(3.0, $marker->averageStars());
    }
}
