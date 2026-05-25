<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\BlogPostFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ],
        );

        $this->call(UsersTableSeeder::class);
        $this->call(BlogCategoriesTableSeeder::class);
        BlogPostFactory::new()->count(100)->create();
    }
}
