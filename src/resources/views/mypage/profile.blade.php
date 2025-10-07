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
            <div class="profile-user__group">
                <p class="profile__user-name">{{ $profile && $profile->name ? $profile->name : $user->name }}</p>
                @if ($user->average_rating)
                <p class="profile__rating">
                    @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $user->average_rating ? 'star-colored' : 'star-empty' }}">★</span>
                    @endfor
                </p>
                @endif
            </div>
        </div>

        <div class="profile-info__edit">
            <div class="profile-edit__link">
                <a href="{{ route('profile.edit') }}" class="profile-edit__link-btn">プロフィールを編集</a>
            </div>
        </div>
    </div>
    <div class="profile-tab__menu">
        <a href="{{ route('profile.index', array_filter(['tab' => 'sell', 'keyword' => request('keyword')])) }}" class="profile-tab__link {{ request('tab') === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('profile.index', array_filter(['tab' => 'buy', 'keyword' => request('keyword')])) }}" class="profile-tab__link {{ request('tab') === 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="{{ route('profile.index', array_filter(['tab' => 'transaction', 'keyword' => request('keyword')])) }}" class="profile-tab__link {{ request('tab') === 'transaction' ? 'active': '' }}">
            取引中の商品
            @if (!empty($unreadTxnCount) && $unreadTxnCount > 0)
            <span class="badge">{{ $unreadTxnCount > 99 ? '99+' : $unreadTxnCount }}</span>
            @endif
        </a>
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
            <span class="sold-ribbon">SOLD</span>
            @endif

            @if (request('tab') === 'transaction' && !empty($item->unread_count) && $item->unread_count > 0)
            <span class="unread-badge">
                {{ $item->unread_count > 99 ? '99' : $item->unread_count }}
            </span>
            @endif

        </a>
    </div>
    @endforeach
    @endif
</div>
@endsection