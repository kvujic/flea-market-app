@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/item.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-content">
        <div class="item-image">
            <div class="item-image__wrapper">
                <img class="item-image__image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                @if ($item->is_sold)
                <div class="sold-ribbon">SOLD</div>
                @endif
            </div>
        </div>
        <div class="item-data">
            <div class="item-data__group">
                <h1 class="item-title">{{ $item->name }}</h1>
                <p class="item-brand">{{ $item->brand }}</p>
                <p class="item-price">&yen;<span class="item-price__number">{{ number_format($item->price) }}</span>（税込）</p>

                <div class="item-meta">
                    {{-- like button --}}
                    @if(Auth::check())
                    <form action="{{ route('item.like', $item->id) }}" method="POST" class="icon">
                        @csrf
                        <button class="icon-like" type="submit">
                            @if (auth()->check() && auth()->user()->likedItems()->where('item_id', $item->id)->exists())
                            <img src="{{ asset('images/star-color.svg') }}" alt="liked" class="icon-like__img">
                            @else
                            <img src="{{ asset('images/star.svg') }}" alt="like" class="icon-like__img">
                            @endif
                            <span class="liked-count">{{ $item->likedByUsers->count() }}</span>
                        </button>
                    </form>
                    @else
                    <div class="icon icon-disabled">
                        <a href="{{ route('login') }}" class="icon-like">
                            <img src="{{ asset('images/star.svg') }}" alt="liked" class="icon-like__image">
                            <span class="liked-count">{{ $item->likedByUsers->count() }}</span>
                        </a>
                    </div>
                    @endif
                    {{-- comment count display --}}
                    <div class="icon-comment">
                        <img src="{{ asset('images/chat-bubble.svg') }}" alt="comment" class="icon-comment__image">
                        <span class="comment-count">{{ $item->comments->count() }}</span>
                    </div>
                </div>

                <div class="purchase-link">
                    <a href="{{ route('purchase.purchase', $item->id) }}" class="purchase-btn">購入手続きへ</a>
                </div>
            </div>

            <div class="item-data__group description">
                <h2 class="item-detail__title">商品説明</h2>
                <p class="item-detail__text">{!! nl2br(e($item->description)) !!}</p>
            </div>
            <div class="item-data__group info">
                <h2 class="item-detail__title">商品の情報</h2>
                <table class="item-detail__table">
                    <tr class="item-detail__category">
                        <th class="item-detail__label">カテゴリー</th>
                        <td class="category-item">
                            <div class="category-tags">
                                @foreach($item->categories as $category)
                                <span class="category-tag">{{ $category->category }}</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr class="item-detail__condition">
                        <th class="item-detail__label">商品の状態</th>
                        <td class="condition-item">{{ $item->condition->condition }}</td>
                    </tr>
                </table>
            </div>

            <div class="item-data__group">
                <h2 class="item-detail__title comment">コメント( {{ $item->comments->count() }} )</h2>
                <div class="comment-box">
                    @if ($comments->isEmpty())
                    <p class="comment-empty">コメントはまだありません</p>
                    @else
                    @foreach($comments as $comment)
                    <div class="comment-user__info">
                        @if ($comment->user->profile && $comment->user->profile->profile_image)
                        <img src="{{ asset('storage/profiles/' . $comment->user->profile->profile_image) }}" alt="profile-image" class="comment-user__image">
                        @else
                        <img src="{{ asset('storage/profiles/default-profile.svg') }}" alt="default-image" class="comment-user__image">
                        @endif
                        <p class="comment-user__name">{{ $comment->user->name }}</p>
                    </div>
                    <p class="comment-body">{!! nl2br(e($comment->content)) !!}</p>
                    @endforeach
                    @endif
                    <div class="comment-pagination">
                        {{ $comments->links('vendor.pagination.default') }}
                    </div>
                </div>
                <form action="{{ route('item.comment', $item->id) }}" class="comment-form" method="POST">
                    @csrf
                    <div class="comment-form__group">
                        <label class="comment-form__label">商品へのコメント<label>
                        <textarea class="comment-form__text" name="content" rows="5">{{ old('content') }}</textarea>
                        @error('content')
                        <div class="comment-form__error">{{ $message }}</div>
                        @enderror
                        <button class="comment-form__btn" type="submit">コメントを送信する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection