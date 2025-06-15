<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_can_be_selected_and_reflected_in_summary()
    {
        Livewire::test('payment-method-selector')
        ->call('toggleOptions')
        ->assertSet('isOpen', true);

        Livewire::test('payment-method-selector')
        ->call('selectMethod','コンビニ払い')
        ->assertSet('paymentMethod', 'コンビニ払い');

        Livewire::test('payment-summary')
        ->dispatch('setPaymentMethod', 'コンビニ払い')
        ->assertSee('コンビニ払い');
    }
}
