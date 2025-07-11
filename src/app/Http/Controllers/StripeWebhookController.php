<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\services\PurchaseService;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function handle(Request $request)
    {

        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                // skip if it already registered
                if (Purchase::where('item_id', $session->metadata->item_id)->exists()) {
                    return response('Already processed', 200);
                }

                // purchase registration
                Purchase::create([
                    'user_id' => $session->metadata->user_id,
                    'item_id' => $session->metadata->item_id,
                    'payment_method' => 'カード支払い',
                    'amount' => $session->amount_total,
                    'shipping_postal_code' => $session->metadata->shipping_postal_code,
                    'shipping_address' => $session->metadata->shipping_address,
                    'shipping_building' => $session->metadata->shipping_building,
                    'stripe_transaction_id' => $session->id,
                    'payment_date' => now(),
                ]);

                $item = Item::find($session->metadata->item_id);
                if ($item && !$item->is_sold) {
                    $item->is_sold = true;
                    $item->save();
                }

                break;

            case 'payment_intent.succeeded':
                $intent = $event->data->object;

                // check if payment_method is konbini
                if (!empty($intent->metadata->item_id) && 
                    !Purchase::where('item_id', $intent->metadata->item_id)->exists()) {

                        Purchase::create([
                            'user_id' => $intent->metadata->user_id,
                            'item_id' => $intent->metadata->item_id,
                            'payment_method' => 'コンビニ支払い',
                            'amount' => $intent->amount,
                            'shipping_postal_code' => $intent->metadata->shipping_postal_code,
                            'shipping_address' => $intent->metadata->shipping_address,
                            'shipping_building' => $intent->metadata->shipping_building,
                            'stripe_transaction_id' => $intent->id,
                            'payment_date' => now(),
                        ]);

                        $item = Item::find($intent->metadata->item_id);
                        if ($item && !$item->is_sold) {
                            $item->is_sold = true;
                            $item->save();
                        }
                    }

                    break;
        }

        return response('Webhook handled', 200);

    }
}
