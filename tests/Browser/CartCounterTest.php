<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CartCounterTest extends DuskTestCase
{
    /**
     * KU-2.2
     * Menampilkan jumlah item pada cart
     */
    public function test_cart_counter_updates()
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