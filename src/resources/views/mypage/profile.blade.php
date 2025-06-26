@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
<div class="profile-content">
    <div class="profile__user">
        <div class="profile__user-info">
            @php
            $profileImagePath = $profile && $profile->profile_image && Storage::disk('public')->exists('profiles/' . $profile->profile_image)
            ? 'storage/profiles/' . $profile->profile_image
            : 'storage/profiles/default-image.png';
            @endphp
            <img src="{{ asset($profileImagePath) }}" alt="profile-image" class="profile__user-image">
            <p class="profile__user-name">{{ $profile && $profile->name ? $profile->name : $user->name }}</p>
        </div>
        <div class="profile-user__edit">
            <div class="profile-edit__link">
                <a href="{{ route('profile.edit') }}" class="profile-edit__link-btn">プロフィールを編集</a>
            </div>
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    </div>
    <div class="profile-tab__menu">
        <a href="{{ route('profile.index', array_filter(['tab' => 'sell', 'keyword' => request('keyword')])) }}" class="profile-tab__link {{ request('tab') !== 'buy' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('profile.index', array_filter(['tab' => 'buy', 'keyword' => request('keyword')])) }}" class="profile-tab__link {{ request('tab') === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>
    <hr class="separator">
</div>
@if ($items -> isEmpty())
<p class="item-no-list">商品がありません</p>
@else
<div class="profile__item-list">
    @foreach ($items as $item)
    <div class="item-card {{ $item->is_sold ? 'is_sold' : '' }}">
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