
<div class="custom-select-box">
    <div class="selected" wire:click="toggleOptions">{{ $paymentMethod ?: '選択してください' }}</div>
<div class="options {{ $isOpen ? 'active' : '' }}">
    <div class="option {{ $paymentMethod === 'コンビニ払い' ? 'selected' : '' }}" wire:click='selectMethod("コンビニ払い")' data-id="コンビニ払い">コンビニ払い</div>
    <div class="option {{ $paymentMethod === 'カード支払い' ? 'selected' : '' }}" wire:click='selectMethod("カード支払い")' data-id="カード支払い">カード支払い</div>
</div>
<input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
</div>


{{--
<div>
    <select class="custom-select-box" name="payment_method" wire:model="paymentMethod">
        <option class="option selected" value="" disabled>選択してください</option>
        <option class="option" value="コンビニ払い">コンビニ払い</option>
        <option class="option" value="カード支払い">カード支払い</option>
    </select>

    @livewire('payment-summary', [paymentMethod' => $paymentMethod], key('summary'))
</div>
--}}