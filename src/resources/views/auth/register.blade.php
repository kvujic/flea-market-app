@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__title">会員登録</h2>
    <div class="register-form__content">
        <form class="register-form__item" action="{{ route('register') }}" method="POST" novalidate>
            @csrf
            <div class="register-form__group">
                <label class="register-form__label">ユーザー名</label>
                <input type="text" class="register-form__input" name="name" value="{{ old('name') }}">
                @error('name')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="register-form__group">
                <label class="register-form__label">メールアドレス</label>
                <input type="email" class="register-form__input" name="email" value="{{ old('email') }}">
                @error('email')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="register-form__group">
                <label class="register-form__label">パスワード</label>
                <input type="password" class="register-form__input" name="password">
                @error('password')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="register-form__group">
                <label class="register-form__label">確認用パスワード</label>
                <input type="password" class="register-form__input" name="password_confirmation" autocomplete="new-password">
                @error('password_confirmation')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="register-form__btn">
                <button class="register-form__btn-submit" type="submit">登録する</button>
            </div>
            <div class="register-form__link">
                <a href="/login" class="register-form__link-login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection