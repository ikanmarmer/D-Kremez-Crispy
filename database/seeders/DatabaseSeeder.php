<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'testU',
            'email' => 'testU@gmail.com',
            'role' => Role::User->value,
            'password' => 'testU',
        ]);

        User::factory()->create([
            'name' => 'testA',
            'email' => 'testA@gmail.com',
            'role' => Role::Admin->value,
            'password' => 'testA',
        ]);

        User::factory()->create([
            'name' => 'testK',
            'email' => 'testK@gmail.com',
            'role' => Role::Karyawan->value,
            'password' => 'testK',
        ]);

        User::factory()->create([
            'name' => 'User2',
            'email' => 'User2@gmail.com',
            'role' => Role::User->value,
            'password' => 'User2',
        ]);
    }
}
