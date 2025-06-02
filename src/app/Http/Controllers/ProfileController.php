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

    public function index()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        return view('profile.edit', compact('user', 'profile'));
    }

    public function updateProfile(ProfileRequest $request) {
        $user = auth()->user();
        $validated = $request->validated();
        $profile = $user->profile ?? new Profile();

        if ($request->hasFile('profile_image')) {
            $filename = $request->file('profile_image')->store('public/profiles');
            $profile->profile_image = basename($filename);
        }

        $profile->fill($validated);
        $profile->user_id = $user->id;
        $profile->save();

        return redirect()->route('profile.index');
    }
} 