<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DownloadReceiptTest extends DuskTestCase
{
    /**
     * KU-2.9
     * Download struk berhasil dijalankan
     */
    public function test_download_receipt_button()
    {
        $this->browse(function (Browser $browser) {

            /*
            |--------------------------------------------------------------------------
            | OPEN SUCCESS PAGE
            |--------------------------------------------------------------------------
            */

            $browser->visit('/payment/success/1')

                ->pause(2000)

                /*
                |--------------------------------------------------------------------------
                | ASSERT BUTTON EXISTS
                |--------------------------------------------------------------------------
                */

                ->assertSee('Download Struk')

                /*
                |--------------------------------------------------------------------------
                | CLICK DOWNLOAD
                |--------------------------------------------------------------------------
                */

                ->click('@download-receipt')

                ->pause(3000)

                /*
                |--------------------------------------------------------------------------
                | PAGE STILL STABLE
                |--------------------------------------------------------------------------
                */

                ->assertSee('Pesanan Berhasil');
        });
    }
}