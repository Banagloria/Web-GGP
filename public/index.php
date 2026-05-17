<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

/*
 * Darurat produksi: jika migrasi/tabel `sessions` belum ada tetapi .env memakai
 * SESSION_DRIVER=database, buat berkas kosong ini di server:
 *   touch storage/framework/force-file-session
 * lalu `php artisan config:clear` (tanpa config:cache dulu). Hapus berkas ini setelah migrate.
 * Dotenv `safeLoad` tidak menimpa variabel yang sudah diset di sini.
 */
if (file_exists(__DIR__.'/../storage/framework/force-file-session')) {
    putenv('SESSION_DRIVER=file');
    $_ENV['SESSION_DRIVER'] = 'file';
    $_SERVER['SESSION_DRIVER'] = 'file';
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
