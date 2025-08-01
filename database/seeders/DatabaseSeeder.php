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
        User::firstOrCreate(['username' => 'kijokk'],
[
            'email' => 'rzkyrzky@a',
            'password' => Hash::make('rzkyrzky'),
            'role' => 'moderator',
        ]);

        User::firstOrCreate(['username' => 'lwkeyfwu'],
[
            'email' => 'arsyl@a',
            'password' => Hash::make('password'),
            'role' => 'moderator',
        ]);

        User::firstOrCreate(['username' => 'kemskuy'],
[
            'email' => 'kemal@a',
            'password' => Hash::make('password'),
            'role' => 'moderator',
        ]);

        User::firstOrCreate(['username' => 'pleee'],
[
            'email' => 'pleee@a',
            'password' => Hash::make('password'),
            'role' => 'moderator',
        ]);

        User::firstOrCreate(['username' => 'riifahri'],
[
            'email' => 'fahri@a',
            'password' => Hash::make('password'),
            'role' => 'moderator',
        ]);

        User::firstOrCreate(['username' => 'user1'],
[
            'email' => 'awokawok@a',
            'password' => Hash::make('awokawok'),
        ]);

        User::firstOrCreate(['username' => 'user2'],
[
            'email' => 'awokawok2@a',
            'password' => Hash::make('awokawok2'),
        ]);
    }
}