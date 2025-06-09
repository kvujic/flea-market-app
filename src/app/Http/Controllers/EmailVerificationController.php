<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function show() {
        return view('auth.verify');
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();
        Auth::login($request->user());
        return redirect()->route('profile.edit');
        }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', '認証用メールを再送信しました。');
    }
}
