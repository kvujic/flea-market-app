@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="item-content">
    <div class="tab-menu">
        <a href="{{ route('item.index', array_filter(['tab' => 'recommended', 'keyword' => request('keyword')])) }}" class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('item.index', array_filter(['tab' => 'mylist', 'keyword' => request('keyword')])) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <hr class="separator">

    @if ($items -> isEmpty())
    <p class="item-no-list">商品がありません</p>
    @else
    <div class="item-list">
        @foreach ($items as $item)
        <div class="item-card {{ $item->is_sold ? 'is_sold' : '' }}">
            <a href="{{ $item->is_sold ? 'javascript:void(0);' : route('item.show', $item->id) }}" class="item-link">
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
</div>
@endsection