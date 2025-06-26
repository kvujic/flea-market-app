@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<div class="exhibition-form">
    <div class="exhibition-form__inner">
        <form action="{{ route('sell') }}" class="exhibition-form__form" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <h1 class="exhibition-form__title">商品の出品</h1>
            <div class="exhibition-item">
                <label class="exhibition-item__label">商品画像</label>
                <div class="image-upload__frame">
                    <div class="image-wrapper">
                        <img src="" class="image-upload__preview preview-image hidden" data-default-src="" alt="item_image">
                    </div>
                    <div class="image-control">
                        <label class="custom-file__upload">
                            <input type="file" class="image-input" name="item_image" accept="image/*" data-preview-target=".preview-image" data-label-target=".image-label">
                            <span class="image-label">画像を選択する</span>
                        </label>
                    </div>
                </div>
                <div id="upload-button-container" class="upload-button-container"></div>
            </div>
            @error('item_image')
            <div class="exhibition-form__error-message">{{ $message }}</div>
            @enderror

            <div class="exhibition-item">
                <h2 class="exhibition-item__title">商品の詳細</h2>
                <div class="line"></div>
                <div class="exhibition-item__group">
                    <label class="exhibition-item__group-label">カテゴリー</label>
                    <div class="category-list">
                        @php
                        $selectedCategoryIds = old('categories', $selectedCategoryIds ?? []);
                        echo "<script>
                            window.initialCategoryIds = " . json_encode($selectedCategoryIds) . ";
                        </script>"
                        @endphp
                        @foreach($categories as $category)
                        <div class="category-tags">
                            <div class="category-tag" data-id="{{ $category->id }}">
                                {{ $category->category }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div id="selected-categories"></div>
                    @error('categories')
                    <div class="exhibition-form__error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="exhibition-item__group">
                    <label class="exhibition-item__group-label">商品の状態</label>
                    <div class="custom-select-box">
                        <div class="selected">
                            @php
                            $selectedId = old('condition_id');
                            $selectedLabel = $conditions->firstWhere('id', $selectedId)?->condition ?? '選択してください';
                            @endphp
                            {{ $selectedLabel }}
                        </div>
                        <div class="options">
                            @foreach($conditions as $condition)
                            <div class="option {{ old('condition_id') == $condition->id ? 'selected' : '' }}" data-id="{{ $condition->id }}">
                                {{ $condition->condition }}
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="condition_id" value="{{ old('condition_id') }}">
                        @error('condition_id')
                        <div class="exhibition-form__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="exhibition-item__list">
                <h2 class="exhibition-item__title">商品名と説明</h2>
                <div class="line"></div>
                <div class="exhibition-item__group-detail">
                    <label class="exhibition-item__group-label">商品名</label>
                    <input type="text" class="exhibition-item__input" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="exhibition-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="exhibition-item__group-detail">
                    <label class="exhibition-item__group-label">ブランド名</label>
                    <input type="text" class="exhibition-item__input" name="brand" value="{{ old('brand') }}">
                </div>
                <div class="exhibition-item__group-detail">
                    <label class="exhibition-item__group-label">商品の説明</label>
                    <textarea class="exhibition-item__textarea" name="description" rows="5">{{ old('description', $item->description ?? '') }}</textarea>
                    @error('description')
                    <div class="exhibition-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="exhibition-item__group-detail">
                    <label class="exhibition-item__group-label">販売価格</label>
                    <div class="exhibition-item__input-wrapper">
                        <span class="yen-symbol">&yen;</span>
                        <input type="number" class="exhibition-item__input exhibition-item__input-price" name="price" value="{{ old('price') }}" min="0" step="1">
                    </div>
                    @error('price')
                    <div class="exhibition-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="exhibition-form__btn">
                    <button class="exhibition-form__btn-submit" type="submit">出品する</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/image-preview.js') }}"></script>
<script src="{{ asset('js/category-select.js') }}"></script>
<script src="{{ asset('js/select-box.js') }}"></script>
@endsection