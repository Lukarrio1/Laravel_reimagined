<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $faker = Factory::create();
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make("password123"),
                'email_verification_token' => Str::random(16),
                'last_login_at' => Carbon::now(),
                'password_reset_token' => Str::random(32)
            ]);
        }
    }
}
