<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreditCardPaymentTest extends DuskTestCase
{
    /**
     * KU-2.14
     * Payment credit card
     */
    public function test_credit_card_payment_flow()
    {
        $this->browse(function (Browser $browser) {

            /*
            |--------------------------------------------------------------------------
            | HALAMAN PAYMENT
            |--------------------------------------------------------------------------
            */

            $browser->visit('/payment/1')

                ->pause(2000)

                /*
                |--------------------------------------------------------------------------
                | INPUT NAMA
                |--------------------------------------------------------------------------
                */

                ->type('@customer-name', 'Zidan')

                ->pause(1000)

                /*
                |--------------------------------------------------------------------------
                | PILIH CREDIT CARD
                |--------------------------------------------------------------------------
                */

                ->click('@payment-cc-option')

                ->pause(1000)

                /*
                |--------------------------------------------------------------------------
                | KLIK BAYAR
                |--------------------------------------------------------------------------
                */

                ->click('@pay-button')

                ->pause(3000)

                /*
                |--------------------------------------------------------------------------
                | VALIDASI HALAMAN CREDIT CARD
                |--------------------------------------------------------------------------
                */

                ->assertSee('Credit Card');

               
        });
    }
}