<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repository;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Repository::create([
            "name" => "laravel-app",
            "url" => "https://github.com/example/laravel-app",
            "description" => "Main Laravel application",
            "guide" =>
                "Welcome to the Laravel App! Start by running composer install, then copy .env.example to .env and run php artisan key:generate.",
        ]);

        Repository::create([
            "name" => "api-service",
            "url" => "https://github.com/example/api-service",
            "description" => "REST API service",
            "guide" =>
                "This is our API service. Check the README for endpoint documentation.",
        ]);

        Repository::create([
            "name" => "frontend",
            "url" => "https://github.com/example/frontend",
            "description" => "React frontend",
            "guide" =>
                "Run npm install followed by npm run dev to start the development server.",
        ]);

        Repository::create([
            "name" => "mobile-app",
            "url" => "https://github.com/example/mobile-app",
            "description" => "React Native mobile app",
            "guide" =>
                "Install dependencies with npm install, then run npx expo start.",
        ]);
    }
}
