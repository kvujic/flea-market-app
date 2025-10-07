<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index(Request $request) {

       if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('item.index');
        }

        $categories = Category::all();

        $tab = $request->query('tab', 'recommended');
        $keyword = $request->input('keyword');
        $items = collect();

        if ($tab === 'mylist') {
            // in case of non-authentication
            if(!auth()->check() || !auth()->user()->hasVerifiedEmail()) {
                return view('items.index', compact('items', 'tab', 'categories'));
            }
            // get mylist of authenticated user
            $items = Item::whereHas('likes', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->with('categories')
            ->search($keyword)
            ->get();

            return view('items.index', compact('items', 'tab', 'categories'));
        }
            // get recommended items
        $query = Item::with('categories')->search($keyword);
            // exclude seller's items
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }
        $items = $query->get();

        return view('items.index', compact('items', 'tab', 'categories'));
    }

    public function show($id) {
        $item = Item::with('seller.profile', 'categories')->findOrFail($id);

        $comments = $item->comments()
        ->with('user.profile')
        ->latest()
        ->paginate(5);

        return view('items.item', compact('item', 'comments'));
    }
}
