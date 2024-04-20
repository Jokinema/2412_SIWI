<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatasetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('datasets')->insert([
                'username' => $faker->userName,
                'full_text' => $faker->text,
                'sentiment' => $faker->randomElement(['negatif', 'netral', 'positif']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
