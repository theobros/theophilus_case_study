<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Test user',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'email' => $user['email']
            ], $user);
        }
    }
}
