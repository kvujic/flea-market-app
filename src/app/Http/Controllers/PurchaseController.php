<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // redirect edit.blade.php if there is no profile saved
        if (!$profile || !$profile->postal_code || !$profile->address) {
            return redirect()->route('profile.edit');
        }

        if (!session('address_updated')) {
            session()->forget([
                'shipping_postal_code',
                'shipping_address',
                'shipping_building',
            ]);
        }

        $item->load('purchase');

        $item = Item::with('purchase')->findOrFail($item->id);

        if ($item->is_sold) {
            return redirect()->route('profile.index');
        }


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
        $paymentMethod = $request->payment_method;

        // stripe credit card
        if ($paymentMethod === 'カード支払い') {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => (int) round($item->price),
                    ],
                    'quantity' => 1,
                ]],

                'mode' => 'payment',
                'success_url' => route('profile.index'),
                'cancel_url' => route('item.index'),
                'metadata' => [
                    'user_id' => Auth::id(),
                    'item_id' => $item->id,
                    'shipping_postal_code' => $request->shipping_postal_code,
                    'shipping_address' => $request->shipping_address,
                    'shipping_building' => $request->shipping_building,
                ]
            ]);

            return redirect($session->url);
        }

        // convenience store
        if ($paymentMethod === 'コンビニ支払い') {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $intent = \Stripe\PaymentIntent::create([
                    'amount' => (int) round($item->price),
                    'currency' => 'jpy',
                    'payment_method_types' => ['konbini'],
                    'payment_method_data' => [
                        'type' => 'konbini',
                        'billing_details' => [
                            'name' => Auth::user()->name,
                            'email' => 'succeed_immediately@test.com',
                            'phone' => '22222222220'
                        ],
                    ],
                    'payment_method_options' => [
                        'konbini' => [
                            'expires_after_days' => 3,
                        ]
                    ],

                    'metadata' => [
                        'user_id' => Auth::id(),
                        'item_id' => $item->id,
                        'shipping_postal_code' => $request->shipping_postal_code,
                        'shipping_address' => $request->shipping_address,
                        'shipping_building' => $request->shipping_building,
                    ],
                    'confirm' => true,
                ]);

                $details = $intent->next_action->konbini_display_details ?? null;

                if (!$details || empty($details->hosted_voucher_url)) {
                    return back();
                }

                return redirect($details->hosted_voucher_url);

            } catch (\Stripe\Exception\ApiErrorException $e) {

                return back();
            }

        }
    }

    // address change option
    public function edit(Item $item)
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('purchase.address', [
            'item' => $item,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => '',
        ]);
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        logger('updateAddress called');
        logger('Request data:', $request->all());
        logger('Item ID:', ['id' => $item->id]);

        session([
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);

        logger('Session data set successfully');

        return redirect()->route('purchase.purchase', ['item' => $item->id])->with('address_updated', true);
    }
}
