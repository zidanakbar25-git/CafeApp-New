<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Order;

class OrderSuccessTest extends DuskTestCase
{
    /**
     * KU-2.8
     * Berhasil masuk ke halaman order success
     */
    public function test_order_success_page()
    {
        $this->browse(function (Browser $browser) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL ACTIVE ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::where('status', 'menunggu')
                ->latest()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | OPEN PAYMENT PAGE
            |--------------------------------------------------------------------------
            */

            $browser->visit('/payment/' . $order->order_id)

                ->pause(1000)

                /*
                |--------------------------------------------------------------------------
                | INPUT CUSTOMER
                |--------------------------------------------------------------------------
                */

                ->type('customer_name', 'Zidan Testing')

                /*
                |--------------------------------------------------------------------------
                | PILIH QRIS
                |--------------------------------------------------------------------------
                */

                ->click('@payment-qris')

                /*
                |--------------------------------------------------------------------------
                | SUBMIT PAYMENT
                |--------------------------------------------------------------------------
                */

                ->click('@pay-button')

                ->pause(3000)

                /*
                |--------------------------------------------------------------------------
                | QRIS PAGE
                |--------------------------------------------------------------------------
                */

                ->click('@pay-button')

                ->pause(2000)

                /*
                |--------------------------------------------------------------------------
                | ASSERT SUCCESS PAGE
                |--------------------------------------------------------------------------
                */

                ->assertSee('Pesanan Berhasil')

                ->assertSee('Informasi Pesanan')

                ->assertSee('Rincian Pesanan');
        });
    }
}