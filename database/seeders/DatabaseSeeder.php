<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = \App\Models\User::factory()->create([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'Account',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $Users = \App\Models\User::factory(5)->create();

        $allUsers = $Users->concat([$admin]);

        \App\Models\Car::factory(50)->create([
            'user_id' => fn() => $allUsers->random()->id,
        ]);
    }
}
