@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')
<div class="edit-profile">
    <div class="edit-profile__inner">
        <form class="edit-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <h1 class="edit-form__title">プロフィール設定</h1>
            <div class="image-upload__frame">

                @php
                $profileImagePath = !empty($profile) && !empty($profile->profile_image)
                ? 'storage/profiles/' . $profile->profile_image
                : 'storage/profiles/default-profile.svg';

                $shouldShowImage = !empty($profile) && !empty($profile->profile_image);
                @endphp

                <img src="{{ asset($profileImagePath) }}" class="edit-image__image preview-image {{ $shouldShowImage ? '' : 'hidden' }}" data-default-src="{{ asset('storage/profiles/default-profile.svg') }}" alt="profile_image">

                <div class="image__control">
                    <label class="custom-file__upload">
                        <input type="file" class="image-input" name="{{ isset($profile) ? 'profile_image' : 'item_image' }}" accept="image/*" data-preview-target=".preview-image" data-label-target=".image-label">
                        <span class="image-label">画像を選択する</span>
                    </label>
                    @error('profile_image')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="edit-form__content">
                <div class="edit-form__group">
                    <label class="edit-form__label">ユーザー名</label>
                    <input type="text" class="edit-form__input" name="name" value="{{ old('name', $profile->name ?? '') }}">
                    @error('name')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="edit-form__group">
                    <label class="edit-form__label">郵便番号</label>
                    <input type="text" class="edit-form__input" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                    @error('postal_code')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="edit-form__group">
                    <label class="edit-form__label">住所</label>
                    <input type="text" class="edit-form__input" name="address" value="{{ old('address', $profile->address ?? '') }}">
                    @error('address')
                    <div class="edit-form__error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="edit-form__group">
                    <label class="edit-form__label">建物名</label>
                    <input type="text" class="edit-form__input" name="building" value="{{ old('building', $profile->building ?? '') }}">
                </div>

                <div class="edit-form__btn">
                    <button class="edit-form__btn-submit" type="submit">更新する</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/image-preview.js') }}"></script>
@endsection