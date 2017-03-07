<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
        DB::table('users')->insert([
            'name' => 'Test Moderator',
            'email' => 'moderator@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
