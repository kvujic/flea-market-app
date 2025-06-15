<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Storage;

class ExhibitionController extends Controller
{
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $categories = Category::all();
        $conditions = Condition::all();
        $selectedCategoryIds = [];
        $item = null;

        return view('exhibition', compact('categories', 'conditions', 'selectedCategoryIds', 'item'));
    }

    public function store(ExhibitionRequest $request)
    {

        try {
            $validated = app(\App\Http\Requests\ExhibitionRequest::class)->validated();

            $image = $request->file('item_image');


            if (!$image) {
                throw new \Exception('ファイル形式が正しくないか、アップロードに失敗しました。商品画像は.jpegまたは.png形式でアップロードしてください');
            }

            // mimes type check
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            $mimeType = $image->getMimeType();
            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new \Exception("対応していない画像形式です{{$mimeType}}。.jpegまたは.png形式でアップロードしてください。");
            }

            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('images', $image, $filename);

            $item = Item::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'description' => $request->description,
                'price' => $request->price,
                'condition_id' => $request->condition_id,
                'user_id' => auth()->id(),
                'item_image' => "images/{$filename}", // ← ここにファイル名を保存
                'is_sold' => false,
            ]);

            $item->categories()->sync($request->categories);

            return redirect()->route('item.index')->with('success', '出品が完了しました');

        } catch (\Exception $e) {
            return back()->withErrors(['item_image' => 'アップロードに失敗しました' . $e->getMessage()])->withInput();
        }
    }
}

