<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $i = 150;
        while ($i--) {
            DB::table('comments')->insert([
                'user_id' => $faker->numberBetween(1,5),
                'post_id' => $faker->randomElement(range(1, 50)),
                'comment_body' => $faker->paragraph,
                'created_at' => $faker->dateTimeBetween('-1 month', '0 days'),
            ]);
        }
    }
}
