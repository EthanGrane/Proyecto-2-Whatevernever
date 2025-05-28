<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Marker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserFavoriteMarker>
 */
class UserFavoriteMarkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'marker_id' => Marker::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'favorite' => $this->faker->boolean(80),
        ];
    }
}
