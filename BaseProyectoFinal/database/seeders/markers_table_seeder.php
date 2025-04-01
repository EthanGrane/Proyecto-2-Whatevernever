<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Nette\Utils\Random;

class markers_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $descriptions = [
            "Just as you take my hand Just as you write my number down Just as the drinks arrive Just as they play your favorite song As your bad day disappears No longer wound up like a spring Before you had too much Come back in focus again",
            "The walls are bending shape They've got a Cheshire cat grin All blurring into one This place is on a mission Before the night owl Before the animal noises Closed circuit cameras Before you're comatose",
            "Before you run away from me Before you're lost between the notes The beat goes round and round The beat goes round and round I never really got there I just pretended that I had Words are blunt instruments Words are sawed-off shotguns",
            "Come on and let it out Come on and let it out Come on and let it out Come on and let it out",
            "Before you run away from me Before you're lost between the notes Just as you take the mic Just as you dance, dance, dance",
            "Jigsaw falling into place So there is nothing to explain You eye each other as you pass She looks back, you look back"];

        foreach (range(1, 150) as $index) 
        {
            DB::table('markers')->insert([
                'name' => $faker->country(),
                'description' => $descriptions[$index % count($descriptions)],
                'lng' => mt_rand(-9000,3000) / 1000,
                'lat' => mt_rand(37000,43000) / 1000,
                'user_id' => mt_rand(1,50),
                'marker_list_id' => mt_rand(1,50)
            ]);
        }
    }
}
