<?php

namespace Database\Seeders\User;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(30)->create();

        $faker = \Faker\Factory::create();
        User::query()->create([
            'name' => $faker->name(),
            'email' => 'test@test.ru',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'avatar' => 'https://source.unsplash.com/random',
            'about' => $faker->text(400),
        ]);
    }
}
