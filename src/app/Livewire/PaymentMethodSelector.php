<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentMethodSelector extends Component
{
    public $isOpen = false;
    public $paymentMethod = '';

    public function toggleOptions()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function selectMethod($method)
    {
        $this->paymentMethod = $method;
        $this->isOpen = false;

        $this->dispatch('setPaymentMethod', $method);
    }


    public function render()
    {
        return view('livewire.payment-method-selector');
    }
}
