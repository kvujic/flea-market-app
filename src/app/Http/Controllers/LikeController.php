<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    /*
    public function show(Item $item)
{
    $item->load(['likedUsers', 'comments', 'user', 'categories']);
    return view('item', compact('item'));
}
    */


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
