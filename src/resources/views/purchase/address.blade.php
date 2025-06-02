@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-form">
    <h2 class="address-form__title">住所の変更</h2>
    <div class="address-form__content">
        <form class="address-form__item" action="{{ route('purchase.address', ['item' => $item->id]) }}" method="POST" novalidate>
            @csrf
            <div class="address-form__group">
                <label class="address-form__label">郵便番号</label>
                <input type="text" class="address-form__input" name="postal_code" value="{{ old('shipping_postal_code') }}">
                @error('postal_code')
                <div class="address-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="address-form__group">
                <label class="address-form__label">住所</label>
                <input type="text" class="address-form__input" name="address" value="{{ old('shipping_address') }}">
                @error('address')
                <div class="address-form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="address-form__group">
                <label class="address-form__label">建物名</label>
                <input type="text" class="address-form__input" name="building">
            </div>
           
            <div class="address-form__btn">
                <button class="address-form__btn-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection