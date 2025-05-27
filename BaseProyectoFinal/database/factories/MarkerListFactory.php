<?php

namespace Database\Factories;

use App\Models\Marker;
use App\Models\MarkerList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkerListFactory extends Factory
{
    protected $model = MarkerList::class;

    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'owner_user_id' => User::factory(),
            'emoji_identifier' => rand(0,20)
        ];
    }
}
