<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class PaymentSummary extends Component
{
    public $paymentMethod = '';

    #[On('setPaymentMethod')]
    public function setPaymentMethod($value)
    {
        $this->paymentMethod = $value;
    }

    public function render()
    {
        return view('livewire.payment-summary');
    }
}
