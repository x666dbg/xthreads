<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'rzky',
            'email' => 'rzky@a',
            'password' => Hash::make('rzkyrzky'),
        ]);

        User::create([
            'username' => 'user1',
            'email' => 'awokawok@a',
            'password' => Hash::make('awokawok'),
        ]);

        User::create([
            'username' => 'user2',
            'email' => 'awokawok2@a',
            'password' => Hash::make('awokawok2'),
        ]);
    }
}
