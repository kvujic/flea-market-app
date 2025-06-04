@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
<div class="profile-content">
    <div class="profile__user">

        <div class="profile__user-info">
            @if ($profile && $profile->profile_image)
            <img src="{{ asset('storage/profiles/' . $profile->profile_image) }}" alt="profile-image" class="profile__user-image">
            @else
            <img src="{{ asset('storage/profiles/default-profile.svg') }}" alt="default-image" class="profile__user-image">
            @endif
            <p class="profile__user-name">{{ $user->name }}</p>
        </div>
        <div class="profile-edit__link">
            <a href="{{ route('profile.edit') }}" class="profile-edit__link-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="profile-tab__menu">
        <a href="{{ route('profile.index', array_filter(['tab' => 'sell', 'keyword' => request('keyword')])) }}" class="{{ request('tab') !== 'buy' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('profile.index', array_filter(['tab' => 'buy', 'keyword' => request('keyword')])) }}" class="{{ request('tab') === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <hr class="separator">
</div>

<div class="profile__item-list">
    @if ($items -> isEmpty())
    <p>商品がありません</p>
    @else
    @foreach ($items as $item)
    <div class="item-card">
        <a href="{{ route('item.show', $item->id) }}" class="item-link">
            <div class="item-wrapper">
                <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" class="item-image">
            </div>
            <p class="item-label">{{ $item->name }}</p>
            @if ($item->is_sold)
            <div class="sold-ribbon">SOLD</div>
            @endif
        </a>
    </div>
    @endforeach
    @endif
</div>
@endsection
