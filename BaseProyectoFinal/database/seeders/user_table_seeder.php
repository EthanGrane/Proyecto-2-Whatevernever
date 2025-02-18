<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Nette\Utils\Random;

class user_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'username' => "admin",
            'name' => "AdminName",
            'email' => "Admin@Admin.com",
            'password' => Hash::make('password'),
            'last_lng' => mt_rand(-9000,3000) / 1000,
            'last_lat' => mt_rand(37000,43000) / 1000,
        ]);

        foreach (range(1, 50) as $index) 
        {
            DB::table('users')->insert([
                'username' => $faker->userName,
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('password'),
                'last_lng' => mt_rand(-9000,3000) / 1000,
                'last_lat' => mt_rand(37000,43000) / 1000,
            ]);
        }
    }
}
