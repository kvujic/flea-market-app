<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;

class LikeController extends Controller
{
    public function store(Item $item) {
        $user = auth()->user();

        $like = Like::where('user_id', $user->id)
        ->where('item_id', $item->id)
        ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }
        return back();
    }

}
