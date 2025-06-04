<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $tab = $request->input('tab');
        if($tab === 'buy') {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        } else {
            $items = $user->items;
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
        $user = auth()->user();
        $profile = $user->profile ?? new Profile();

        if ($request->hasFile('profile_image')) {
            $filename = $request->file('profile_image')->store('public/profiles');
            $profile->profile_image = basename($filename);
        }

        $profile->user_id = $user->id;
        $profile->save();

        return redirect()->route('profile.index');
    }
} 