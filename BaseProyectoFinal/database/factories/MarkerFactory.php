<?php

namespace Database\Factories;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkerFactory extends Factory
{
    protected $model = Marker::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'lng' => $this->faker->longitude,
            'lat' => $this->faker->latitude,
            'zoom' => 10,
            'pitch' => 0,
            'bearing' => 0,
            'user_id' => User::factory(),
        ];
    }
}
