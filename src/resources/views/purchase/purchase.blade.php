@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store', ['item' => $item->id]) }}" class="purchase-form" method="POST">
    @csrf
    <div class="purchase-content">
        <div class="purchase-inner">
            <div class="purchase-group">
                <div class="purchase-group__data purchase-item">
                    <div class="purchase-item__image-wrapper">
                        <img class="purchase-item__image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                        @if ($item->is_sold)
                        <div class="sold-ribbon">SOLD</div>
                        @endif
                    </div>
                    <div class="purchase-item__item">
                        <h1 class="purchase-item__title">{{ $item->name }}</h1>
                        <p class="purchase-item__price"><span class="purchase-item__price-yen">&yen;</span>{{ number_format($item->price) }}</p>
                    </div>
                </div>
                <div class="line"></div>
                <div class="purchase-group__data payment-method">
                    <h2 class="purchase-group__label">支払い方法</h2>
                    @livewire('payment-method-selector')
                </div>
                @error('payment_method')
                <div class="purchase-form__error">{{ $message }}</div>
                @enderror
                <div class="line"></div>
                <div class="purchase-group__data shipping-address">
                    <div class="shipping-address__header">
                        <h2 class="purchase-group__label">配送先</h2>
                        <a href="{{ route('purchase.address', ['item' => $item->id]) }}" class="shipping-address__change">変更する</a>
                    </div>
                    <div class="shipping-address__body">
                        <p class="shipping-address__postal-code">〒{{ $postal_code }}</p>
                        <p class="shipping-address__address">{{ $address }}</p>
                        @if(!empty($building))
                        <p class="shipping-address__building">{{ $building }}</p>
                        @endif
                        <input type="hidden" name="shipping_postal_code" value="{{ $postal_code }}">
                        <input type="hidden" name="shipping_address" value="{{ $address }}">
                        <input type="hidden" name="shipping_building" value="{{ $building }}">
                    </div>
                </div>
                <div class="purchase-form__error">
                    @error('postal_code')
                    <div class="purchase-form__error-message">{{ $message }}</div>
                    @enderror
                    @error('address')
                    <div class="purchase-form__error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="line"></div>
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
                            @livewire('payment-summary')
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