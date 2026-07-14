<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerNameValidationTest extends DuskTestCase
{
    /**
     * KU-2.10
     * Validasi nama customer wajib diisi
     */
    public function test_customer_name_validation()
    {
        $this->browse(function (Browser $browser) {

            // langsung ke halaman payment
            $browser->visit('/payment/1')

                ->pause(2000)

                /*
                |--------------------------------------------------------------------------
                | KOSONGKAN INPUT NAMA
                |--------------------------------------------------------------------------
                */

                ->clear('@customer-name')

                ->pause(1000)

                /*
                |--------------------------------------------------------------------------
                | KLIK BAYAR
                |--------------------------------------------------------------------------
                */

                ->click('@pay-button')

                ->pause(2000);

            /*
            |--------------------------------------------------------------------------
            | VALIDASI HTML5 REQUIRED
            |--------------------------------------------------------------------------
            */

            $validationMessage = $browser->script("
                return document.querySelector('[dusk=\"customer-name\"]').validationMessage;
            ");

            $this->assertNotEmpty($validationMessage);
        });
    }
}