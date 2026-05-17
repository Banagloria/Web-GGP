<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    /*
    | Tanpa realpath(): jika folder belum ada, realpath mengembalikan false dan Blade bisa error.
    | Override dengan VIEW_COMPILED_PATH di .env jika perlu.
    */
    'compiled' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),

];
