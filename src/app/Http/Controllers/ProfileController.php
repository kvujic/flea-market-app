<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Chat;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user()->load('profile');
        $profile = $user->profile;
        $keyword = $request->input('keyword');
        $tab = $request->input('tab');

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

        $items = Item::withCount(['chats as unread_count' => function ($query) use ($user) {
            $query->where('sender_id', '!=', $user->id)
                  ->where('is_read', false);
        }])->get();

        if($tab === 'buy') {
            $query = $user->purchases()->with('item');

            if ($keyword) {
                $query->whereHas('item', fn($q) => $q->search($keyword));
            }

            $items = $query->get()->pluck('item')->filter();
        } elseif ($tab === 'transaction') {
            $items = Item::query()
                ->whereHas('chats', function ($chatQuery) use ($user) {
                    $chatQuery->whereHas('transaction', function ($txnQuery) use ($user) {
                        $txnQuery->where(function ($roleQuery) use ($user) {
                            $roleQuery->where('buyer_id', $user->id)
                            ->orWhere('seller_id', $user->id);
                        });
                    });
                })
                ->when($keyword, fn ($q) => $q->search($keyword))
                ->get();
        } else {
            $items = Item::where('user_id', $user->id)
            ->search($keyword)
            ->get();
        }

        return view('mypage.profile', compact('user', 'profile', 'items', 'unreadTxnCount'));
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
