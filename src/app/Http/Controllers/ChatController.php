<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Transaction;
use App\Http\Requests\ChatRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Transaction $transaction, ChatRequest $request)
    {
        abort_unless(in_array(auth()->id(), [(int)$transaction->buyer_id, (int)$transaction->seller_id], true), 404);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_image', 'public');
        }

        $message = $transaction->chats()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'image' => $imagePath ? Storage::disk('public')->url($imagePath) : null,
            'is_read' => true,
        ]);

        $transaction->touch();

        return back();
    }

    public function markRead(Transaction $transaction)
    {
        $transaction->chats()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }

    public function update(Transaction $transaction, Chat $chat, Request $request)
    {
        //一時デバッグ
        //dd($transaction->id, $chat->id, $chat->transaction_id);

        abort_unless((int)$chat->transaction_id === (int)$transaction->id, 404);
        abort_unless((int)$chat->sender_id === (int)auth()->id(), 403);

        $chat->update(['message' => $request->message]);
        $transaction->touch();
        
        return back();
    }

    public function destroy(Transaction $transaction, Chat $chat)
    {
        //一時デバッグ
        //dd($transaction->id, $chat->id, $chat->transaction_id);

        abort_unless((int)$chat->transaction_id === (int)$transaction->id, 404);
        abort_unless((int)$chat->sender_id === (int)auth()->id(), 403);

        $chat->delete();
        $transaction->touch();

        return back();
    }
}
