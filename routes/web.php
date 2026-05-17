<?php

use App\Http\Controllers\Admin\GalleryAdminController;
use App\Http\Controllers\Admin\AnnouncementAdminController;
use App\Http\Controllers\Admin\BaptismRegistrationController;
use App\Http\Controllers\Admin\CongregationRegistrationController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarriageRegistrationController;
use App\Http\Controllers\Admin\RegistrationSubmissionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WorshipScheduleAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\AnnouncementController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\RegistrationController;
use App\Http\Controllers\Public\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::pattern('pageKey', 'beranda|profil|struktur|jadwal|pendaftaran|informasi_kegiatan|kontak|galeri');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest', 'throttle:8,1']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', [PageController::class, 'profil'])->name('profil');
Route::get('/struktur', [PageController::class, 'struktur'])->name('struktur');

Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal');

Route::prefix('pendaftaran')->name('pendaftaran.')->middleware('throttle:15,1')->group(function () {
    Route::get('/', [RegistrationController::class, 'index'])->name('index');
    Route::get('/jemaat', [RegistrationController::class, 'congregation'])->name('jemaat');
    Route::post('/jemaat', [RegistrationController::class, 'storeCongregation'])->name('jemaat.store');
    Route::get('/baptisan', [RegistrationController::class, 'baptism'])->name('baptisan');
    Route::post('/baptisan', [RegistrationController::class, 'storeBaptism'])->name('baptisan.store');
    Route::get('/pernikahan', [RegistrationController::class, 'marriage'])->name('pernikahan');
    Route::post('/pernikahan', [RegistrationController::class, 'storeMarriage'])->name('pernikahan.store');
    Route::get('/{slug}', [RegistrationController::class, 'show'])
        ->name('show')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::post('/{slug}', [RegistrationController::class, 'store'])
        ->name('store')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
});

Route::get('/informasi-kegiatan', [AnnouncementController::class, 'index'])->name('informasi-kegiatan');
Route::get('/informasi-kegiatan/{slug}', [AnnouncementController::class, 'show'])->name('informasi-kegiatan.show');

Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:12,1')->name('kontak.store');

Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('galeri');
Route::redirect('/album', '/galeri', 301);
Route::redirect('/album/{slug}', '/galeri', 301)->where('slug', '.*');

/* URL lama /admin → /dashboard (panel pengurus) */
Route::redirect('/admin', '/dashboard', 301);
Route::redirect('/admin/dashboard', '/dashboard', 301);
Route::redirect('/admin/settings', '/dashboard/halaman', 301);
Route::redirect('/admin/pages', '/dashboard/halaman', 301);
Route::redirect('/admin/congregations', '/dashboard/pendaftaran-data/jemaat', 301);
Route::redirect('/admin/baptisms', '/dashboard/pendaftaran-data/baptisan', 301);
Route::redirect('/admin/marriages', '/dashboard/pendaftaran-data/pernikahan', 301);
Route::redirect('/admin/announcements', '/dashboard/pengumuman', 301);
Route::redirect('/admin/schedules', '/dashboard/jadwal-ibadah', 301);
Route::redirect('/admin/contacts', '/dashboard/kontak', 301);
Route::redirect('/admin/albums', '/dashboard/galeri', 301);
Route::redirect('/dashboard/album', '/dashboard/galeri', 301);
Route::redirect('/dashboard/album/{any}', '/dashboard/galeri', 301)->where('any', '.*');
Route::permanentRedirect('/dashboard/baptisan', '/dashboard/pendaftaran-data/baptisan');
Route::permanentRedirect('/dashboard/pendaftaran-jemaat', '/dashboard/pendaftaran-data/jemaat');
Route::permanentRedirect('/dashboard/pendaftaran-baptisan', '/dashboard/pendaftaran-data/baptisan');
Route::permanentRedirect('/dashboard/pendaftaran-pernikahan', '/dashboard/pendaftaran-data/pernikahan');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', DashboardController::class)->name('index');

    Route::get('pengaturan', [SettingsController::class, 'edit'])->name('pengaturan.edit');
    Route::put('pengaturan', [SettingsController::class, 'update'])->name('pengaturan.update');

    Route::get('halaman', [ContentPageController::class, 'index'])->name('halaman.index');
    Route::get('halaman/pendaftaran/kartu/{cardKey}/edit', [CmsPageController::class, 'editPendaftaranCard'])
        ->name('halaman.pendaftaran.kartu.edit');
    Route::put('halaman/pendaftaran/kartu/{cardKey}', [CmsPageController::class, 'updatePendaftaranCard'])
        ->name('halaman.pendaftaran.kartu.update');
    Route::get('halaman/{pageKey}/edit', [CmsPageController::class, 'edit'])->name('halaman.cms.edit');
    Route::put('halaman/{pageKey}', [CmsPageController::class, 'update'])->name('halaman.cms.update');

    Route::get('pendaftaran-data/{slug}/export/csv', [RegistrationSubmissionController::class, 'exportCsv'])
        ->name('pendaftaran-data.export-csv')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran-data/{slug}', [RegistrationSubmissionController::class, 'index'])
        ->name('pendaftaran-data.index')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran-data/{slug}/{submission}', [RegistrationSubmissionController::class, 'show'])
        ->name('pendaftaran-data.show')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::delete('pendaftaran-data/{slug}/{submission}', [RegistrationSubmissionController::class, 'destroy'])
        ->name('pendaftaran-data.destroy')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');

    Route::resource('pengumuman', AnnouncementAdminController::class)
        ->parameters(['pengumuman' => 'announcement'])
        ->except(['show']);

    Route::resource('jadwal-ibadah', WorshipScheduleAdminController::class)
        ->parameters(['jadwal-ibadah' => 'schedule'])
        ->except(['show']);

    Route::get('kontak', [ContactAdminController::class, 'index'])->name('kontak.index');
    Route::get('kontak/{contact}', [ContactAdminController::class, 'show'])->name('kontak.show');
    Route::delete('kontak/{contact}', [ContactAdminController::class, 'destroy'])->name('kontak.destroy');

    Route::get('galeri', [GalleryAdminController::class, 'index'])->name('galeri.index');
    Route::post('galeri', [GalleryAdminController::class, 'store'])->name('galeri.store');
    Route::patch('galeri/{galleryItem}', [GalleryAdminController::class, 'update'])->name('galeri.update');
    Route::delete('galeri/{galleryItem}', [GalleryAdminController::class, 'destroy'])->name('galeri.destroy');
});
