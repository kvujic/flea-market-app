<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;
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

        if($tab === 'buy') {
            $items = $user->purchases()
            ->with(['item' => function ($query) use ($keyword) {
                $query->search($keyword);
            }])
            ->get()
            ->pluck('item')
            ->filter();
        } else {
            $items = Item::where('user_id', $user->id)
            ->search($keyword)
            ->get();
        }

        return view('mypage.profile', compact('user', 'profile', 'items'));
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

            return redirect()->route('profile.index')->with('success', 'プロフィールを更新しました');

        } catch (\Exception $e) {

        return redirect()->back()
        ->withInput()
        ->with('error', 'プロフィールの更新に失敗しました');
        }
    }
}
