<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class marker_list_seeder extends Seeder
{

    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) 
        {
            DB::table('marker_list')->insert([
                'name' => $faker->country(),
                'owner_user_id' => mt_rand(0,50)
            ]);
        }
    }
}
