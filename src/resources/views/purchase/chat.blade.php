@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/chat.css') }}">
@endsection

@section('content')
<div class="chat-content">
    <aside class="chat-sidebar">
        <h2 class="chat-sidebar__title">その他の取引</h2>
        <ul class="chat-sidebar__list">
            @foreach($myTransactions as $t)
            @php
            $isActive = (int)$t->id === (int)$transaction->id;
            @endphp
            <li class="chat-sidebar__item {{ $isActive ? 'is-active' : '' }}">
                <a href="{{ route('transactions.show', $t->id) }}" class="chat-sidebar__link">{{ $t->item->name }}</a>
            </li>
            @endforeach
        </ul>
    </aside>

    <main class="chat-main">
        <header class="chat-header">
            <div class="chat-header__item">
                <img src="{{ asset('storage/profiles/' . ($otherUser->profile->profile_image ?? 'default-image.png')) }}" alt="profile_image" class="chat-header__avatar">
                <h1 class="chat-header__title">{{ $otherUser->name }}さんとの取引画面</h1>
            </div>
            @if (auth()->id() === (int)$transaction->buyer_id)
            <form id="completeForm" action="#" method="POST">
                @csrf
                @if (auth()->id() === $transaction->buyer_id && $transaction->status === 'in_progress')
                <button class="chat-complete__btn" type="submit">取引を完了する</button>
                @endif
            </form>
            @endif
        </header>

        <hr class="separator">

        <div class="chat-item">
            <div class="chat-item__wrap">
                <img src="{{ asset('/storage/' . $transaction->item->item_image) }}" alt="item_image" class="chat-item__image">
                <div class="chat-item__info">
                    <p class="chat-item__name">{{ $transaction->item->name }}</p>
                    <p class="chat-item__price">
                        <span class="yen">￥</span>
                        {{ number_format($transaction->item->price) }}
                    </p>
                </div>
            </div>
        </div>

        <hr class="separator">

        @if ($messages->isEmpty())
        <p class="chat-message__none">メッセージはありません</p>
        @else
        <div class="chat-thread">
            @foreach ($messages as $msg)
            @php
            $mine = $msg->sender_id === auth()->id();
            $avatar = data_get($msg->user, 'profile.profile_image', 'default-image.png');
            @endphp
            <div class="chat-message {{ $mine ? 'is-mine' : 'is-others' }}">
                <div class="chat-message__info">
                    @if (!$mine)
                    <img src="{{ asset('storage/profiles/' . ($otherUser->profile->profile_image ?? 'default-image.png')) }}" alt="" class="chat-message__avatar">
                    <span class="chat-message__writer">{{ $otherUser->name }}</span>
                    @else
                    <span class="chat-message__writer">{{ auth()->user()->name }}</span>
                    <img src="{{ asset('storage/profiles/' . $avatar) }}" alt="" class="chat-message__avatar">
                    @endif
                </div>
                @if ($msg->image)
                <p class="chat-message__image">
                    <img src="{{ $msg->image }}" alt="upload_image" class="upload-image">
                </p>
                @endif
                @if ($msg->message)
                <p class="chat-message__body">{{ $msg->message }}</p>
                @endif

                @if ($mine)
                <div class="chat-message__actions">
                    <button class="edit-btn" type="button" data-target="edit-modal-{{ $msg->id }}">編集</button>

                    <form action="{{ route('transactions.messages.destroy', ['transaction' => $transaction->id, 'chat' => $msg->id]) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="delete-btn" type="submit">削除</button>
                    </form>
                </div>

                {{-- modal for edit message --}}
                <div id="edit-modal-{{ $msg->id }}" class="modal" aria-hidden="true">
                    <div class="modal__overlay" data-close></div>
                    <div class="modal__content" role="dialog" aria-modal="true" aria-labelledby="edit-title-{{ $msg->id }}">
                        <h3 id="edit-title-{{ $msg->id }}" class="edit-title">メッセージを編集</h3>

                        <form method="POST"
                            action="{{ route('transactions.messages.update', ['transaction' => $transaction->id, 'chat' => $msg->id]) }}">
                            @csrf
                            @method('PATCH')

                            <textarea class="edit-textarea" name="message" rows="4" required>{{ old('message', $msg->message) }}</textarea>

                            @error('message')
                            <div class="form-error">{{ $message }}</div>
                            @enderror

                            <div class="modal__actions">
                                <button type="submit" class="btn-primary">更新</button>
                                <button type="button" class="btn-outline" data-close>キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <div class="chat-input">
            <div class="chat-error__message">
                @error('message')
                <div class="chat-form__error">{{ $message }}</div>
                @enderror
                @error('image')
                <div class="chat-form__error">{{ $message }}</div>
                @enderror
            </div>
            <form action="{{ route('transactions.messages.store', $transaction) }}" method="POST" enctype="multipart/form-data" class="chat-input__form" id="chatInputForm">
                @csrf
                <textarea id="chatMessageInput" class="chat-input__textarea" name="message" rows="1" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                <label class="chat-input__file">
                    <input type="file" name="image" accept="image/*" hidden id="chatImageInput">
                    <span class="chat-input__image">画像を追加</span>
                </label>
                <button class="chat-input__send" type="submit">
                    <img src="{{ asset('/images/paper-airplane.png') }}" alt="送信" class="send-image">
                </button>
            </form>
        </div>
    </main>
</div>

{{-- modal for rating --}}
<div id="rating-modal" class="modal" aria-hidden="true">
    <div class="modal__overlay"></div>
    <div class="modal__content" role="dialog" aria-modal="true" aria-labelledby="rating-title">
        <h2 id="rating-title" class="rating-modal__title">取引が完了しました。</h2>
        <hr class="line">
        <form action="{{ route('ratings.store', $transaction) }}" id="ratingForm" method="POST">
            @csrf
            <div class="rating-modal__body">
                <p class="rating-modal__message">今回の取引相手はどうでしたか？</p>
                <div class="rating-stars" aria-label="５段階評価">
                    @for ($i = 1; $i <= 5; $i++)
                        <button class="star" type="button" data-value="{{ $i }}" aria-label="{{ $i }}点">&#9733;</button>
                        @endfor
                </div>
                <input type="hidden" name="score" id="ratingInput" value="0">
            </div>
            <hr class="line">
            <div class="rating-modal__bottom">
                <p id="ratingError" class="error">{{ $errors->first('score') }}</p>
                <button id="ratingSubmitBtn" class="rating-modal__send" type="submit">送信する</button>
            </div>
        </form>
    </div>
</div>

{{-- JS --}}
<script>
    document.addEventListener('click', function(e) {
        const openBtn = e.target.closest('[data-target]');
        if (openBtn) {
            const id = openBtn.getAttribute('data-target');
            const m = document.getElementById(id);
            if (m) {
                m.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
        }
        if (e.target.hasAttribute('data-close')) {
            const m = e.target.closest('.modal');
            if (m) {
                m.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        }
    });
</script>

<script>
    (function() {
        const form = document.getElementById('chatInputForm');
        const textarea = document.getElementById('chatMessageInput');
        if (!form || !textarea) return;

        const userId = form.dataset.userId || 'guest';
        const txnId = form.dataset.transactionId || 'unknown';
        const STORAGE_KEY = `draft:chat:${userId}:${txnId}`;

        document.addEventListener('DOMContentLoaded', () => {
            const hasServerOld = textarea.value && textarea.value.trim().length > 0;
            if (!hasServerOld) {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved !== null) {
                    textarea.value = saved;
                }
            }
        });

        let saveTimer = null;
        textarea.addEventListener('input', () => {
            if (saveTimer) clearTimeout(saveTimer);
            saveTimer = setTimeout(() => {
                localStorage.setItem(STORAGE_KEY, textarea.value);
            }, 150);
        });

        window.addEventListener('beforeunload', () => {
            localStorage.setItem(STORAGE_KEY, textarea.value);
        });

        form.addEventListener('submit', () => {
            localStorage.removeItem(STORAGE_KEY);
        });
    })();
</script>

<script src="{{ asset('js/rating.js') }}"></script>

@endsection