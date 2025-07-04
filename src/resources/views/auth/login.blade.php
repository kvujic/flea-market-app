@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <h1 class="login-form__title">ログイン</h1>
    <div class="login-form__content">
        <form class="login-form__item" action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="login-form__group">
                <label class="login-form__label">メールアドレス</label>
                <input type="email" class="login-form__input" name="email" value="{{ old('email') }}">
                @error('email')
                <div class="login-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="login-form__group">
                <label class="login-form__label">パスワード</label>
                <input type="password" class="login-form__input" name="password">
                @error('password')
                <div class="login-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="login-form__btn">
                <button class="login-form__btn-submit" type="submit">ログインする</button>
            </div>
            <div class="login-form__link">
                <a href="/register" class="login-form__link-register">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection