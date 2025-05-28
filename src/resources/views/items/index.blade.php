@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-content">
    <div class="tab-menu">
        <a href="{{ route('item.index', ['tab' => 'recommended']) }}" class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('item.index', ['tab' => 'mylist']) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <hr class="separator">

    <div class="item-list">
        @if ($items -> isEmpty())
        <p>商品がありません</p>
        @else
        @foreach ($items as $item)
        <div class="item-card">
            <img src="{{ asset('storage/images/' . $item->item_image) }}" alt="{{ $item->name }}" class="item_image">
            @if ($item->is_sold)
            <div class="sold-ribbon">SOLD</div>
            @endif
            <p class="item_label">{{ $item->name }}</p>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection