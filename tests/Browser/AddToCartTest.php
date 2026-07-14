<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddToCartTest extends DuskTestCase
{
    /**
     * KU-2.1
     * Tambah menu ke cart
     */
    public function test_add_menu_to_cart()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/table/1')

                ->pause(2000);

            

            $initialCount = (int) $browser->text('@cart-count');

            

            $browser->click('@add-cart')

                ->pause(2000);

            

            $updatedCount = (int) $browser->text('@cart-count');

            

            $this->assertTrue($updatedCount > $initialCount);
        });
    }
}