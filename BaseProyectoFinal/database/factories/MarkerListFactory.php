<?php
namespace Database\Factories;

use App\Models\MarkerList;
use App\Models\User; // Asegúrate de importar el modelo User si lo usas
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarkerList>
 */
class MarkerListFactory extends Factory
{
    protected $model = MarkerList::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'owner_user_id' => User::factory(), // Crear un usuario asociado con este marker
            'emoji_identifier' => rand(0,50),
        ];
    }
}

?>