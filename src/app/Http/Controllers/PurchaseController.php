<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show(Item $item) {
        $user = auth()->user();
        $profile = $user->profile;

        // redirect edit.blade.php if there is no profile saved
        if (!$profile || !$profile->postal_code || !$profile->address) {
            return redirect()->route('profile.edit');
        }

        $item->load('purchase');

        return view('purchase.purchase', [
            'item' => $item,
            'postal_code' => session('shipping_postal_code', $profile->postal_code),
            'address' => session('shipping_address', $profile->address),
            'building' => session('shipping_building', $profile->building),
        ]);
    }

    // purchase processing
    public function store(PurchaseRequest $request, Item $item) 
    {
        // stripe
        if ($request->payment_method === 'カード支払い') {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => $item->price, // *１円単位（１０００円＝１０００）
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item' => $item->id]),
                'cancel_url' => route('purchase.cancel', ['item' => $item->id]),
            ]);

            return redirect($session->url);
        }


        // convenience store payment
        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'amount' => $item->price,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
            'payment_date' => now(),
            'stripe_transaction_id' => null,
        ]);

        $item->is_sold = true;
        $item->save();

        session()->forget([
            'shipping_postal_code',
            'shipping_address',
            'shipping_building',
        ]);

        return redirect()->route('item.index')->with('status', '商品の購入が完了しました');
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
    public function updateAddress(PurchaseRequest $request, Item $item) {
        logger('updateAddress called');
        logger('Request data:', $request->all());
        logger('Item ID:', ['id' => $item->id]);

        session([
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);

        logger('Session data set successfully');
        
        return redirect()->route('purchase.purchase', ['item' => $item->id])->with('status', '住所を更新しました');
    }
}

