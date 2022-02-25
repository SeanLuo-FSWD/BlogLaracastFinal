<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create([
            'email' => 'alice@alice.com',
        ]);

        Comment::factory(5)->create();

//
//        Post::factory(5)->create([
//            'user_id' => $user->id
//        ]);
    }
}
