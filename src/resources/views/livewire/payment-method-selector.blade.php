
<div class="custom-select-box">
    <div class="selected" wire:click="toggleOptions">{{ $paymentMethod ?: '選択してください' }}</div>
<div class="options {{ $isOpen ? 'active' : '' }}">
    <div class="option {{ $paymentMethod === 'コンビニ支払い' ? 'selected' : '' }}" wire:click='selectMethod("コンビニ支払い")' data-id="コンビニ支払い">コンビニ支払い</div>
    <div class="option {{ $paymentMethod === 'カード支払い' ? 'selected' : '' }}" wire:click='selectMethod("カード支払い")' data-id="カード支払い">カード支払い</div>
</div>
<input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
</div>
