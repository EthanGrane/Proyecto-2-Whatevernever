<?php

namespace Database\Factories;

use App\Models\FriendGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FriendGroup>
 */
class FriendGroupFactory extends Factory
{
    protected $model = FriendGroup::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'owner_user_id' => User::factory(),
        ];
    }
}
