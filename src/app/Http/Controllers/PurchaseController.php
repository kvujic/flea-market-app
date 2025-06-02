<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show(Item $item) {
        $user = auth()->user();
        $profile = $user->profile;

        return view('purchase.purchase', [
            'item' => $item,
            'postal_code' => old('shipping_postal_code', $profile->postal_code),
            'address' => old('shipping_address', $profile->address),
            'building' => old('shipping_building', ''),
        ]);
    }

    // purchase processing
    public function store(PurchaseRequest $request, Item $item) {
        $purchase =Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => 'card',
            'amount' => $item->price,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
            'payment_date' => now(),
            'stripe_transaction_id' => null,
        ]);
        return redirect()->route('item.index')->with('status', '購入が完了しました');
    }

    // address change option
    public function edit (Item $item) {
        $user = Auth::user();
        $profile = $user->profile;

        return view('purchase.address', [
            'item' => $item,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => '',
        ]);
    }

    // address change processing
    public function updateAddress(AddressRequest $request, Item $item) {
        session([
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);
        return redirect()->route('purchase.purchase', ['item' => $item->id]);
    }
}
