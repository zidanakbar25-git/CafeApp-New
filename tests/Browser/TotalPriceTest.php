<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TotalPriceTest extends DuskTestCase
{
    /**
     * KU-2.8
     * Menghitung total harga
     */
    public function test_total_price_updates()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/table/1')

                ->pause(2000)

                // tambah item
                ->click('@add-cart')

                ->pause(1000)

                // buka cart
                ->click('@cart-button')

                ->pause(2000);

            /*
            |--------------------------------------------------------------------------
            | AMBIL TOTAL AWAL
            |--------------------------------------------------------------------------
            */

            $initialTotal = $browser->text('@grand-total');

            /*
            |--------------------------------------------------------------------------
            | UPDATE QUANTITY
            |--------------------------------------------------------------------------
            */

            $browser->click('@qty-plus')

                ->pause(2000);

            /*
            |--------------------------------------------------------------------------
            | AMBIL TOTAL BARU
            |--------------------------------------------------------------------------
            */

            $updatedTotal = $browser->text('@grand-total');

            /*
            |--------------------------------------------------------------------------
            | VALIDASI TOTAL BERUBAH
            |--------------------------------------------------------------------------
            */

            $this->assertNotEquals($initialTotal, $updatedTotal);
        });
    }
}