<?php

namespace App\services;

use App\Models\Item;
use App\Models\Purchase;

class PurchaseService
{
    public function complete(array $metadata, string $method, string $transactionId, int $amount): bool
    {
        if (Purchase::where('item_id', $metadata['item_id'])->exists()) {
            return false;
        }

        Purchase::create([
            'user_id' => $metadata['user_id'],
            'item_id' => $metadata['item_id'],
            'payment_method' => $method,
            'amount' => $amount,
            'shipping_postal_code' => $metadata['shipping_postal_code'],
            'shipping_address' => $metadata['shipping_address'],
            'shipping_building' => $metadata['shipping_building'],
            'stripe_transaction_id' => $transactionId,
        ]);

        $item = Item::find($metadata['item_id']);
        if ($item) {
            $item->is_sold = true;
            $item->save();
        }

        return true;
    }
}