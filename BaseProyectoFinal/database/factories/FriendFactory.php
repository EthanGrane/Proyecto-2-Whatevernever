<?php

namespace Database\Factories;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Friend>
 */
class FriendFactory extends Factory
{
    protected $model = Friend::class;

    public function definition()
    {
        return [
            'request_status' => $this->faker->randomElement([0,1]),
            'sender_user_id' => User::factory(),
            'reciver_user_id' => User::factory(),
        ];
    }
}
