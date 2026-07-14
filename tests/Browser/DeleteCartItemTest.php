<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteCartItemTest extends DuskTestCase
{
    /**
     * KU-2.7
     * Menghapus item cart
     */
    public function test_delete_cart_item()
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

            // pastikan item ada
            $browser->assertPresent('@cart-item');

            /*
            |--------------------------------------------------------------------------
            | DELETE ITEM
            |--------------------------------------------------------------------------
            */

            $browser->click('@delete-btn')

                ->pause(1000)

                // klik konfirmasi hapus
                ->click('@confirm-delete')

                ->pause(2000);

            /*
            |--------------------------------------------------------------------------
            | VALIDASI ITEM HILANG
            |--------------------------------------------------------------------------
            */

            $browser->assertMissing('@cart-item');
        });
    }
}