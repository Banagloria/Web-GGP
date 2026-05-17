<?php

use Illuminate\Support\Facades\Route;

Route::get('/v1/ping', fn () => response()->json([
    'ok' => true,
    'service' => 'web-gereja',
]));
