<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('🔥 Stripe Webhook Received:', $request->all());


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

        if ($event->type === 'checkout.session.completed') {
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
            ]);

            $item = Item::find($session->metadata->item_id);
            if ($item) {
                $item->is_sold = true;
                $item->save();
            }
        }
        return response('Webhook handled', 200);
    }
}
