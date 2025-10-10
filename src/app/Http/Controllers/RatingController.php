<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Rating;
use App\Models\Purchase;
use App\Mail\TransactionCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $user = auth()->user();

        abort_unless(in_array($user->id, [$transaction->buyer_id, $transaction->seller_id]), 403);

        $alreadyRated = $transaction->ratings()
            ->where('rater_id', $user->id)
            ->exists();

        if ($alreadyRated) {
            return back()->withErrors(['score' => '既に評価済みです']);
        }

        $ratedId = ($user->id === $transaction->buyer_id)
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $transaction->ratings()->create([
            'rater_id' => $user->id,
            'rated_id' => $ratedId,
            'score' => $validated['score'],
        ]);

        if ($user->id === $transaction->buyer_id) {
            $transaction->update(['status' => 'waiting_for_seller']);

            Mail::to($transaction->seller->email)
                ->send(new TransactionCompleted($transaction));

            return redirect()->route('item.index')->with('success', '取引を完了しました。出品者の評価をお待ちください。');
        }

        // purchase when both users are rated
        if ($transaction->ratings()->count() >= 2) {
            $purchase = Purchase::create([
                'user_id' => $transaction->buyer_id,
                'item' => $transaction->item_id,
                'payment_method' => 'コンビニ支払い',
                'amount' => $transaction->item->price,
                'shipping_postal_code' => $transaction->buyer->profile->postal_code,
                'shipping_address' => $transaction->buyer->profile->address,
            ]);

            $transaction->update([
                'purchase_id' => $purchase->id,
                'status' => 'completed',
            ]);
        }

        return redirect()->route('item.index')->with('success', '評価を送信し、取引が完了しました。');
    }
}
