<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL internal WAHA (Docker)
    |--------------------------------------------------------------------------
    |
    | Dipakai Laravel untuk memanggil API WAHA dari container Web_Gereja.
    | Host di database (tab Config) tetap URL publik untuk tombol Open Config.
    |
    */

    'internal_url' => env('WAHA_INTERNAL_URL', 'http://WA_Gereja:3000'),

];
