<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Profile;
use App\Models\TodoModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $testUser= User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
        ]);
        TodoModel::factory(8)->create([
            'user_id' => $testUser->id,
        ]);

        Profile::factory(1)->create([
            'user_id'=>$testUser->id,
        ]);
        Post::factory(10)->create([
            'user_id'=>$testUser->id,

        ]);

        User::factory(10)->has(Profile::factory(1))->create();
        User::factory(4)
            ->has(TodoModel::factory()->count(15))
            ->create();

        User::factory(4)->has(Post::factory(10))->create();

    }

}
