<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Chat;
use App\Models\Transaction;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user()
            ->load('profile')
            ->loadAvg('ratingsReceived as average_rating', 'score');
            
        $profile = $user->profile;
        $keyword = $request->input('keyword');
        $tab = $request->input('tab');

        $items = collect();
        $transactions = collect();

        $unreadTxnCount = Chat::query()
            ->whereHas('transaction', function($chatQuery) use ($user) {
                $chatQuery->where(function ($roleQuery) use ($user) {
                    $roleQuery->where('buyer_id', $user->id)
                        ->orWhere('seller_id', $user->id);
                });
            })
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();

        if($tab === 'buy') {
            $query = $user->purchases()->with('item');

            if ($keyword) {
                $query->whereHas('item', fn($q) => $q->search($keyword));
            }

            $items = $query->get()->pluck('item')->filter();

        } elseif ($tab === 'transaction') {
            $transactions = Transaction::query()
                ->with('item')
                ->where(function ($q) use ($user) {
                    $q->where('buyer_id' , $user->id)->orWhere('seller_id', $user->id);
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->whereHas('item', fn($iq) => $iq->search($keyword));
                })
                ->withCount([
                    'chats as unread_count' => function ($q) use ($user) {
                        $q->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }
                ])
                ->orderByRaw('last_message_at IS NULL, last_message_at DESC')
                ->get();

        } else {
            $items = Item::where('user_id', $user->id)
            ->search($keyword)
            ->get();
        }

        return view('mypage.profile', compact('user', 'profile', 'items', 'transactions', 'unreadTxnCount'));
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        return view('mypage.edit', compact('user', 'profile'));
    }

    public function updateProfile(ProfileRequest $request) {
        try {
            $user = auth()->user()->load('profile');
            $profile = $user->profile ?? new Profile();

            $validated = $request->validated();

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');

                if ($file->isValid()) {
                    // delete the existing image
                    if ($profile->profile_image && Storage::disk('public')->exists('profiles/' . $profile->profile_image)) {
                        Storage::disk('public')->delete('profiles/' . $profile->profile_image);
                    }

                    $extension = $file->getClientOriginalExtension();
                    $filename = uniqid() . '.' . $extension;

                    $file->storeAs('profiles', $filename, 'public');
                    $validated['profile_image'] = $filename;
                }
            }

            $profile->fill($validated);
            $profile->user_id = $user->id;
            $profile->save();

            return redirect()->route('item.index');

        } catch (\Exception $e) {

        return redirect()->back()
        ->withInput();
        }
    }
}
