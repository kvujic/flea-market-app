@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <h2 class="verify-email__title">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </h2>
    @if(session('status'))
    <div class="alert alert-danger">{{ session('status') }}</div>
    @endif
    <div class="verify-email__link">
        <a href="http://localhost:8025" class="verify-email__link-btn">認証はこちらから</a>
        <form action="{{ route('verification.send') }}" class="verify-email__form" method="POST">
            @csrf
            <div class="verify-email__form-send">
                <button class="verify-email__form-btn" type="submit">認証メールを再送する</button>
            </div>

        </form>
    </div>
</div>


@endsection