<?php

namespace Database\Seeders;

use App\Models\Abis;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AbisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('id', ">", 1)->get();

        $users->each(
            fn(User $user) => Abis::create(
                ['user_id' => $user->id, 'abis_number' => Str::random(3) . '-' . Str::random(3)]
            )
        );
    }
}
