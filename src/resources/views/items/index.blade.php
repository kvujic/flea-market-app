@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-content">
    <div class="tab-menu">
        <a href="{{ route('item.index', array_filter(['tab' => 'recommended', 'keyword' => request('keyword')])) }}" class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('item.index', array_filter(['tab' => 'mylist', 'keyword' => request('keyword')])) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <hr class="separator">

    <div class="item-list">
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
</div>
@endsection