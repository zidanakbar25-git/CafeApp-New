<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CashPaymentTest extends DuskTestCase
{
    /**
     * KU-2.13
     * Pembayaran cash
     */
    public function test_cash_payment_flow()
    {
        $this->browse(function (Browser $browser) {

           

            $browser->visit('/payment/1')

                ->pause(2000)

                

                ->type('@customer-name', 'Zidan')

                ->pause(1000)

                

                ->click('@payment-cash-option')

                ->pause(1000)

                

                ->click('@pay-button')

                ->pause(3000);

           

            $browser->assertSee('Nota Pembayaran');
        });
    }
}