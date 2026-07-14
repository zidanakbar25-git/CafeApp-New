<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerNameInputTest extends DuskTestCase
{
    /**
     * KU-2.11
     * Input nama customer
     */
    public function test_input_customer_name()
    {
        $this->browse(function (Browser $browser) {

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
                | PILIH PAYMENT METHOD
                |--------------------------------------------------------------------------
                */

                ->click('@payment-qris')

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
                | VALIDASI REDIRECT
                |--------------------------------------------------------------------------
                */

                ->assertSee('Pembayaran QRIS');
        });
    }
}