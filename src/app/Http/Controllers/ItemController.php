<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ItemController extends Controller
{


    public function index(Request $request) {

        $tab = $request->query('tab', 'recommended');
        
        if ($tab === 'mylist') {
            // in case of non-authentication
            if(!auth()->check()) {
                return redirect()->route('login');
            }
            // get mylist of authenticated user
            $items = auth()->user()->likedItems()->with('categories')->get();
        }else{
            // get recommended items
            $items = Item::with('categories')->get();
        }
        return view('items.index', compact('items', 'tab'));
    }

    public function upload(Request $request) {
        $image = $request->file('item_image');

        // decide filename
        $filename = time() . '.' . $image->getClientOriginalExtension();
        // path (storage/app/public/images)
        $savePath = storage_path('app/public/images/' . $filename);
        // resize and save with Intervention Image
        Image::make($image)->fit(300, 300)->save($savePath);
        // changes URL for publication
        $url = asset('storage/images/' . $filename);

        return response()->json([
            'filename' => $filename,
            'url' => $url,
        ]);
    }

    public function store(Request $request) {
        $image = $request->file('item_image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $savePath = storage_path('app/public/images/' . $filename);
        Image::make($image)->fit(300, 300)->save($savePath);

        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition_id' => $request->condition_id,
            'user_id' => auth()->id(), 
            'item_image' => $filename, // ← ここにファイル名を保存
            'is_sold' => false, 
        ]);
        return redirect()->route('item.index');
    }

    
}
