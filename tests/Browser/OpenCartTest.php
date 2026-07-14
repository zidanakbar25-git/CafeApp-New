<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OpenCartTest extends DuskTestCase
{
    /**
     * KU-2.3
     * Menampilkan halaman cart
     */
    public function test_open_cart_page()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/table/1')

                // tunggu halaman menu
                ->pause(2000)

                // tambah item dulu
                ->click('@add-cart')

                ->pause(1000)

                // klik icon cart
                ->click('@cart-button')

                // tunggu pindah halaman
                ->pause(2000)

                // validasi halaman cart tampil
                ->assertSee('Checkout');
        });
    }
}