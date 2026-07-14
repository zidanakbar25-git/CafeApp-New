<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutTest extends DuskTestCase
{
    /**
     * KU-2.9
     * Checkout cart
     */
    public function test_checkout_cart()
    {
        $this->browse(function (Browser $browser) {

            // diasumsikan cart sudah punya item
            $browser->visit('/cart/1')

                ->pause(2000)

                /*
                |--------------------------------------------------------------------------
                | CHECKOUT
                |--------------------------------------------------------------------------
                */

                ->click('@checkout-btn')

                ->pause(3000)

                /*
                |--------------------------------------------------------------------------
                | VALIDASI HALAMAN PAYMENT
                |--------------------------------------------------------------------------
                */

                ->assertSee('Pembayaran');
        });
    }
}