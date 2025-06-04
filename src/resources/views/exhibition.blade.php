@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<div class="exhibition-form">
    <div class="exhibition-form__inner">
        <form action="{{ route('sell') }}" class="exhibition-form__form" method="POST">
            @csrf
            <h1>商品の出品</h1>
            <div class="exhibition-item">
                <label class="exhibition-item__label">商品画像</label>
                <div class="image-upload__frame">
                    <img src="" class="image-upload__preview preview-image" data-default-src="" alt="item_image">
                    <div class="image__control">
                        <label class="custom-file__upload">
                            <input type="file" class="image-input" name="{{ isset($profile) ? 'profile_image' : 'item_image' }}" accept="image/*" data-preview-target=".preview-image" data-label-target=".image-label">
                            <span class="image-label">画像を選択する</span>
                        </label>
                        @error('item_image')
                        <div class="edit-form__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="exhibition-item">
                <h2 class="exhibition-item__title">商品の詳細</h2>
                <div class="line"></div>
                <div class="exhibition-item__group">

                    <label class="exhibition-item__group-label">カテゴリー</label>
                    <div class="category-list">
                        @foreach($categories as $category)
                        <div class="category-tag {{ in_array($category->id, old('categories', [])) ? 'selected' : ''}}" data-id="{{ $category->id }}">{{ $category->category->id}}</div>
                        @endforeach
                    </div>
                    <input type="hidden" class="selected-category" name="categories" id="selectedCategories">
                </div>
                @error('category')
                <div class="edit-form__error">{{ $message }}</div>
                @enderror

                <div class="exhibition-item__group">
                    <label class="exhibition-item__group-label">商品の状態</label>
                    <div class="custom-select-box">
                        <div class="selected">{{ old('condition') ? old('condition') : '選択してください' }}</div>
                        <div class="options">
                            @foreach($conditions as $condition)
                            <div class="option {{ old('condition') === $condition->condition ? 'selected' : '' }}">{{ $condition->condition }}</div>
                            @endforeach
                        </div>
                        <input type="hidden" name="condition" id="condition" value="{{ old('condition') }}">
                    </div>
                    @error('condition')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="exhibition-item__list">
                <h2 class="exhibition-item__title">商品名と説明</h2>
                <div class="line"></div>
                <div class="exhibition-item__group">
                    <label class="exhibition-item">商品名</label>
                    <input type="text" class="exhibition-item__input" name="name" value="{{ old([$item->name]) }}">
                    @error('name')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="exhibition-item__group">
                    <label class="exhibition-item">ブランド名</label>
                    <input type="text" class="exhibition-item__input" name="brand" value="{{ old([$item->brand]) }}">
                </div>
                <div class="exhibition-item__group">
                    <label class="exhibition-item">商品の説明</label>
                    <textarea class="exhibition-item__textarea" name="description" rows="5">{{ old([$item->description]) }}"></textarea>
                    @error('description')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="exhibition-item__group">
                    <label class="exhibition-item__group-label">販売価格</label>
                    <span class="yen-symbol">&yen;</span>
                    <input type="number" class="exhibition-item__name" name="name" value="{{ old([$item->price]) }}">
                    @error('price')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/image-preview.js') }}"></script>
@endsection