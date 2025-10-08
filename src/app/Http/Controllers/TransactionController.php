<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\Purchase;

class TransactionController extends Controller
{
    public function openChat(Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403);

        $transaction = Transaction::query()
            ->where('buyer_id', auth()->id())
            ->where('seller_id', $item->user_id)
            ->where('item_id', $item->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            $transaction = Transaction::create([
                'buyer_id' => auth()->id(),
                'seller_id' => $item->user_id,
                'item_id' => $item->id,
                'purchase_id' => null,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('transactions.show', $transaction);
    }

    public function show(Transaction $transaction)
    {
        abort_unless(in_array(auth()->id(), [(int)$transaction->buyer_id, (int)$transaction->seller_id], true), 404);

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
        $messages = $transaction->chats()
            ->with('user.profile')
            ->orderBy('created_at')
            ->get();

        return view('purchase.chat', compact('transaction', 'myTransactions', 'messages', 'otherUser'));
    }

    public function bindPurchase(Transaction $transaction, Request $request)
    {
        abort_unless((int)$transaction->buyer_id === (int)auth()->id(), 403);
        abort_if($transaction->status === 'completed', 400);

        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile || empty($profile->address)) {
            return back()->withErrors(['address' => 'プロフィールに住所が登録されていません']);
        }

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $transaction->item_id,
            'payment_method' => 'コンビニ支払い',
            'amount' => $transaction->item->price,
            'shipping_postal_code' => $profile->postal_code,
            'shipping_address' => $profile->address,
        ]);

        $transaction->update([
            'purchase_id' => $purchase->id,
            'status' => 'completed',
        ]);

        // Mail::to($user->email)->send(new TransactionCompleteMail($transaction));

        return back()->with('status', 'completed');
    }
}
