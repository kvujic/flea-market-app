<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function create() {
        return view('auth.register');
    }

    public function store(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        // does not exist user or wrong password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません。'])->withInput();
        }

        // authenticated -> login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // unauthenticated users are redirected
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->route('item.index');
    }
}
