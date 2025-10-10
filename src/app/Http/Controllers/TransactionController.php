<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;

class TransactionController extends Controller
{
    public function openChat(Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403);

        $transaction = Transaction::query()
            ->where('buyer_id', auth()->id())
            ->where('seller_id', $item->user_id)
            ->where('item_id', $item->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$transaction) {
            $transaction = Transaction::create([
                'buyer_id' => auth()->id(),
                'seller_id' => $item->user_id,
                'item_id' => $item->id,
                'purchase_id' => null,
                'status' => 'in_progress',
            ]);
        }

        return redirect()->route('transactions.show', $transaction);
    }

    public function show(Transaction $transaction)
    {
        abort_unless(in_array(auth()->id(), [(int)$transaction->buyer_id, (int)$transaction->seller_id], true), 404);

        $transaction->chats()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        $messages = $transaction->chats()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        $myTransactions = Transaction::with('item')
            ->where(function ($q) {
                $q->where('buyer_id', auth()->id())
                  ->orWhere('seller_id', auth()->id());
            })
            ->orderByDesc('updated_at')
            ->get();

        $otherUser = auth()->id() === (int)$transaction->buyer_id
            ? $transaction->seller
            : $transaction->buyer;

        $transaction->load(['item', 'buyer.profile', 'seller.profile']);

        return view('purchase.chat', compact('transaction', 'myTransactions', 'messages', 'otherUser'));
    }
}
