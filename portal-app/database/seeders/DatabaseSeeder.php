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
        User::factory()->create([
            "name" => "Test User 1",
            "email" => "user1@example.com",
            "password" => "password",
        ]);

        User::factory()->create([
            "name" => "Test User 2",
            "email" => "user2@example.com",
            "password" => "password",
        ]);

        $this->call(RepositorySeeder::class);
    }
}
