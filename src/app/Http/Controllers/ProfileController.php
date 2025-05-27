<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        // ユーザーが既にプロフィールを持っている場合はホームページにリダイレクト
        if (Auth::user()->profile) {
            return redirect()->route('home');
        }

        return view('profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        $profile = new Profile($validated);
        $profile->user_id = Auth::id();
        $profile->save();

        return redirect()->route('home');
    }
} 