<div class="custom-select-box">
    <div class="selected" wire:click="toggleOptions">{{ $paymentMethod ?: '選択してください' }}</div>
    <div class="options {{ $isOpen ? 'active' : '' }}">
        <div class="option {{ $paymentMethod === 'コンビニ払い' ? 'selected' : '' }}" wire:click='selectMethod("コンビニ払い")' data-id="コンビニ払い">コンビニ払い</div>
        <div class="option {{ $paymentMethod === 'カード支払い' ? 'selected' : '' }}" wire:click='selectMethod("カード支払い")' data-id="カード支払い">カード支払い</div>
    </div>
    <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
</div>