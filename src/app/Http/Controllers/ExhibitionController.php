<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $categories = Category::all();
        $conditions = Condition::all();

        return view('exhibition', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $image = $request->file('item_image');
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();

        $image->storeAs('images', $filename, 'public');

        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition_id' => $request->condition_id,
            'user_id' => auth()->id(),
            'item_image' => "images/{$filename}", // ← ここにファイル名を保存
            'is_sold' => false,
        ]);

        return redirect()->route('item.index');
    }
}

