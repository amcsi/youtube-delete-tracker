<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach(range(1, 50) as $index)
        {
            $createdAt = $faker->dateTimeThisYear;
            Video::create([
                'external_video_id' => $faker->password,
                'external_channel_id' => $faker->password,
                'title' => $faker->sentence,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
