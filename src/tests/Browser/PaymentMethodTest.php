<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_payment_method_selection_reflects_in_summary() {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'item_image' => 'default.jpeg',
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
            ->visit(route('purchase.purchase'))
            ->assertSee('支払い方法')

            //convenience store
            ->click('.custom-select-box .selected')
            ->pause(200)
            ->click('.custom-select-box .option[data-id="コンビニ払い”]')
            ->pause(300)

            // verify both the subtotal column and hidden input are correctly reflected
            ->assertSeeIn('#selected_method', 'コンビニ払い')
            ->assertInputValue('payment_method', 'コンビニ払い');
        });
    }

    
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
}
