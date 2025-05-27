<?php

namespace Database\Factories;

use App\Models\Marker;
use App\Models\MarkerReviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkerReviewsFactory extends Factory
{
    protected $model = MarkerReviews::class;

    public function definition()
    {
        return [
            'review_stars' => $this->faker->numberBetween(1, 5),
            'review_content' => $this->faker->sentence(),
            'user_id' => User::factory(),
            'marker_id' => Marker::factory(),
        ];
    }
}
