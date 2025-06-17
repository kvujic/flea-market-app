<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        Comment::create([
            'user_id' => 2,
            'item_id' => 1,
            'content' => "This is a sample comment.
            The comments are displayed correctly."
        ]);
    }
}
