<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Services\Slug;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Slug $slug)
    {
        $faker = Faker::create();
        $i = 50;
        while ($i--) {
            $title = $faker->sentence;
            $theSlug = $slug->createSlug($title);
            DB::table('posts')->insert(
                [
                'post_title' => $title,
                'post_slug' => $theSlug,
                'is_approved' => true,
                'user_id' => $faker->numberBetween(1,5),
                'post_body' => $faker->paragraph,
                'created_at' => $faker->dateTimeBetween('-3 month', '0 days'),
                'updated_at' => $faker->dateTimeBetween('-5 days', '0 days'),
                ]
            );
        }
    }
}
