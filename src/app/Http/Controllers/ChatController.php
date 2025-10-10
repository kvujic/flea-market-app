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

        $transaction->chats()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'image' => $imagePath ? Storage::disk('public')->url($imagePath) : null,
            'is_read' => false,
        ]);

        $transaction->update(['last_message_at' => now()]);

        return back();
    }

    public function markRead(Transaction $transaction)
    {
        abort_unless(in_array(auth()->id(), [(int)$transaction->buyer_id, (int)$transaction->seller_id], true), 404);

        $affected = $transaction->chats()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'updated' => $affected]);
        }

        return back();
    }

    public function update(Transaction $transaction, Chat $chat, ChatRequest $request)
    {
        abort_unless((int)$chat->transaction_id === (int)$transaction->id, 404);
        abort_unless((int)$chat->sender_id === (int)auth()->id(), 403);

        $chat->update(['message' => $request->message]);
        $transaction->touch();

        return back();
    }

    public function destroy(Transaction $transaction, Chat $chat)
    {
        abort_unless((int)$chat->transaction_id === (int)$transaction->id, 404);
        abort_unless((int)$chat->sender_id === (int)auth()->id(), 403);

        $chat->delete();
        $transaction->touch();

        return back();
    }
}
