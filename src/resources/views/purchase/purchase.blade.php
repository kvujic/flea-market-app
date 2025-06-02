@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.purchase', $item) }}" class="purchase-form" method="POST">
    @csrf
    <div class="purchase-content">
        <div class="purchase-inner">
            <div class="purchase-group">
                <div class="purchase-group__data purchase-item">
                    <img class="purchase-item__image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                    <div class="purchase-item__item">
                        <h1 class="purchase-item__title">{{ $item->name }}</h1>
                        <p class="purchase-item__price"><span class="purchase-item__price-yen">&yen;</span>{{ number_format($item->price) }}</p>
                    </div>
                </div>
                <div class="purchase-group__data payment-method">
                    <h2 class="purchase-group__label">支払い方法</h2>
                    <div class="purchase-form__select-box">
                        <div class="purchase-form__selected">選択してください</div>
                        <div class="purchase-form__options">
                            <div class="purchase-form__option">コンビニ払い</div>
                            <div class="purchase-form__option">カード払い</div>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" 
                </div>
            
                <div class="purchase-group__data shipping-address">
                <div class="shipping-address__header">
                    <h2 class="purchase-group__label">配送先</h2>
                    <a href="{{ route('purchase.address', ['item' => $item->id]) }}" class="shipping-address__change">変更する</a>
                </div>
                <p class="shipping-address__postal-code">〒{{ session('shipping_postal_code') ?? auth()->user()->profile->postal_code }}</p>
                <p class="shipping-address__address">{{ session('shipping_address') ?? auth()->user()->profile->address }}</p>
                @if(session('shipping_building'))
                {{ '' . session('shipping_building') }}
                @endif
            </div>
        </div>
        <div class="purchase-group">
            <div class="purchase-group__confirmation">
                <table class="confirmation-table">
                    <tr class="confirmation-table__row">
                        <th class="confirmation-table__label">商品代金</th>
                        <td class="confirmation-table__price"><span class="confirmation-price-yen">&yen;</span>{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="confirmation-table__row">
                        <th class="confirmation-table__label">支払い方法</th>
                        <td class="confirmation-table__method">{{ session('payment_method') }}</td>
                    </tr>
                </table>
            </div>
            <div class="purchase-group__btn">
                <button class="purchase-form__btn-buy" type="submit">購入する</button>
            </div>
        </div>
    </div>
    </div>
</form>
@endsection

@section('js')
    @vite('resources/js/purchase-select.js')
@endsection