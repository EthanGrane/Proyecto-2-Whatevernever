<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Nette\Utils\Random;

class friends_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 200) as $index) {
            $sender = mt_rand(1, 50);
            $reciver = mt_rand(1, 50);
            if ($sender == $reciver) {
                $sender += 1;

                if ($sender > 50)
                    $sender = 1;
            }

            DB::table('friends')->insert([
                'request_status' => $faker->boolean(),
                'sender_user_id' => $sender,
                'reciver_user_id' => $reciver
            ]);
        }
    }
}
