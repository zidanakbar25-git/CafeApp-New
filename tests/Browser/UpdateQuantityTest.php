<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateQuantityTest extends DuskTestCase
{
    public function test_update_item_quantity()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/table/1')

                ->pause(2000)

                ->click('@add-cart')

                ->pause(1000)

                ->click('@cart-button')

                ->pause(2000);

            // ambil qty awal
            $initialQty = (int) $browser->text('@qty-value');

            /*
            |--------------------------------------------------------------------------
            | TAMBAH QUANTITY
            |--------------------------------------------------------------------------
            */

            $browser->click('@qty-plus')

                ->pause(2000)

                ->assertSeeIn('@qty-value', (string) ($initialQty + 1));

            /*
            |--------------------------------------------------------------------------
            | KURANG QUANTITY
            |--------------------------------------------------------------------------
            */

            $browser->click('@qty-minus')

                ->pause(2000)

                ->assertSeeIn('@qty-value', (string) $initialQty);
        });
    }
}