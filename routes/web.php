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
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserAccountController;
use App\Http\Controllers\Admin\WhatsAppNotificationController;
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

/** Pola slug halaman CMS (untuk redirect URL lama). */
$cmsPageKeyPattern = 'beranda|profil|struktur|jadwal|pendaftaran|informasi_kegiatan|kontak|galeri';

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
Route::redirect('/admin/settings', '/dashboard/setting', 301);
Route::redirect('/admin/pages', '/dashboard/setting', 301);
Route::permanentRedirect('/dashboard/halaman', '/dashboard/setting');
Route::permanentRedirect('/dashboard/halaman/{pageKey}/edit', '/dashboard/setting/{pageKey}/edit')
    ->where('pageKey', $cmsPageKeyPattern);
Route::permanentRedirect('/dashboard/halaman/{pageKey}', '/dashboard/setting/{pageKey}/edit')
    ->where('pageKey', $cmsPageKeyPattern);
/* Modul admin lama di bawah /halaman/… — jangan arahkan ke /setting/… (menyebabkan 405). */
Route::permanentRedirect('/dashboard/halaman/pengumuman', '/dashboard/pengumuman');
Route::permanentRedirect('/dashboard/halaman/jadwal-ibadah', '/dashboard/jadwal-ibadah');
Route::permanentRedirect('/dashboard/halaman/jadwal', '/dashboard/jadwal-ibadah');
Route::permanentRedirect('/dashboard/halaman/kontak', '/dashboard/kontak');
Route::permanentRedirect('/dashboard/halaman/galeri', '/dashboard/galeri');
Route::permanentRedirect('/dashboard/halaman/akun', '/dashboard/akun');
Route::redirect('/admin/congregations', '/dashboard/pendaftaran/jemaat', 301);
Route::redirect('/admin/baptisms', '/dashboard/pendaftaran/baptisan', 301);
Route::redirect('/admin/marriages', '/dashboard/pendaftaran/pernikahan', 301);
Route::redirect('/admin/announcements', '/dashboard/pengumuman', 301);
Route::redirect('/admin/schedules', '/dashboard/jadwal-ibadah', 301);
Route::redirect('/admin/contacts', '/dashboard/kontak', 301);
Route::redirect('/admin/albums', '/dashboard/galeri', 301);
Route::redirect('/dashboard/album', '/dashboard/galeri', 301);
Route::redirect('/dashboard/album/{any}', '/dashboard/galeri', 301)->where('any', '.*');
Route::permanentRedirect('/dashboard/baptisan', '/dashboard/pendaftaran/baptisan');
Route::permanentRedirect('/dashboard/pendaftaran-baptisan', '/dashboard/pendaftaran/baptisan');
Route::permanentRedirect('/dashboard/pendaftaran-pernikahan', '/dashboard/pendaftaran/pernikahan');

/* URL lama → /dashboard/pendaftaran/{slug} */
Route::permanentRedirect('/dashboard/pendaftaran-jemaat', '/dashboard/pendaftaran/jemaat');
Route::permanentRedirect('/dashboard/pendaftaran-jemaat/export/csv', '/dashboard/pendaftaran/jemaat/export/csv');
Route::permanentRedirect('/dashboard/pendaftaran-data/{slug}/export/csv', '/dashboard/pendaftaran/{slug}/export/csv')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
Route::permanentRedirect('/dashboard/pendaftaran-data/{slug}', '/dashboard/pendaftaran/{slug}')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/jemaat', '/dashboard/pendaftaran/jemaat');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/jemaat/export/csv', '/dashboard/pendaftaran/jemaat/export/csv');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/baptisan', '/dashboard/pendaftaran/baptisan');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/baptisan/export/csv', '/dashboard/pendaftaran/baptisan/export/csv');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/pernikahan', '/dashboard/pendaftaran/pernikahan');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/pernikahan/export/csv', '/dashboard/pendaftaran/pernikahan/export/csv');
Route::permanentRedirect('/dashboard/pendaftaran-menunggu/{slug}', '/dashboard/pendaftaran/{slug}')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('dashboard.')->group(function () use ($cmsPageKeyPattern) {
    Route::get('/', DashboardController::class)->name('index');

    Route::get('pengaturan', [SettingsController::class, 'edit'])->name('pengaturan.edit');
    Route::match(['put', 'post'], 'pengaturan', [SettingsController::class, 'update'])->name('pengaturan.update');

    Route::get('profil-akun', [ProfileController::class, 'edit'])->name('profil-akun.edit');
    Route::match(['put', 'post'], 'profil-akun', [ProfileController::class, 'update'])->name('profil-akun.update');

    Route::middleware('super_admin')->group(function () use ($cmsPageKeyPattern) {
        Route::get('akun', [UserAccountController::class, 'index'])->name('akun.index');
        Route::get('akun/create', [UserAccountController::class, 'create'])->name('akun.create');
        Route::post('akun', [UserAccountController::class, 'store'])->name('akun.store');
        Route::get('akun/{user}/edit', [UserAccountController::class, 'edit'])->name('akun.edit');
        Route::match(['put', 'post'], 'akun/{user}', [UserAccountController::class, 'update'])->name('akun.update');
        Route::match(['delete', 'post'], 'akun/{user}', [UserAccountController::class, 'destroy'])->name('akun.destroy');

        Route::get('setting', [ContentPageController::class, 'index'])->name('setting.index');
        Route::get('setting/notifikasi-whatsapp', [WhatsAppNotificationController::class, 'index'])->name('setting.notifikasi-whatsapp.index');
        Route::match(['put', 'post'], 'setting/notifikasi-whatsapp/config', [WhatsAppNotificationController::class, 'updateConfig'])->name('setting.notifikasi-whatsapp.config');
        Route::post('setting/notifikasi-whatsapp/messages', [WhatsAppNotificationController::class, 'storeMessage'])->name('setting.notifikasi-whatsapp.messages.store');
        Route::match(['put', 'post'], 'setting/notifikasi-whatsapp/messages/{template}', [WhatsAppNotificationController::class, 'updateMessage'])->name('setting.notifikasi-whatsapp.messages.update');
        Route::match(['delete', 'post'], 'setting/notifikasi-whatsapp/messages/{template}', [WhatsAppNotificationController::class, 'destroyMessage'])->name('setting.notifikasi-whatsapp.messages.destroy');
        Route::post('setting/notifikasi-whatsapp/messages/{template}/test', [WhatsAppNotificationController::class, 'testMessage'])->name('setting.notifikasi-whatsapp.messages.test');
        Route::match(['put', 'post'], 'setting/notifikasi-whatsapp/contacts', [WhatsAppNotificationController::class, 'updateContacts'])->name('setting.notifikasi-whatsapp.contacts');
        Route::match(['delete', 'post'], 'setting/notifikasi-whatsapp/contacts/{recipient}', [WhatsAppNotificationController::class, 'destroyContact'])->name('setting.notifikasi-whatsapp.contacts.destroy');
        Route::post('setting/notifikasi-whatsapp/broadcasts', [WhatsAppNotificationController::class, 'storeBroadcast'])->name('setting.notifikasi-whatsapp.broadcasts.store');
        Route::match(['put', 'post'], 'setting/notifikasi-whatsapp/broadcasts/{broadcast}', [WhatsAppNotificationController::class, 'updateBroadcast'])->name('setting.notifikasi-whatsapp.broadcasts.update');
        Route::match(['delete', 'post'], 'setting/notifikasi-whatsapp/broadcasts/{broadcast}', [WhatsAppNotificationController::class, 'destroyBroadcast'])->name('setting.notifikasi-whatsapp.broadcasts.destroy');
        Route::get('setting/pendaftaran/kartu/{cardKey}/edit', [CmsPageController::class, 'editPendaftaranCard'])
            ->name('setting.pendaftaran.kartu.edit');
        Route::match(['put', 'post'], 'setting/pendaftaran/kartu/{cardKey}', [CmsPageController::class, 'updatePendaftaranCard'])
            ->name('setting.pendaftaran.kartu.update');
        Route::get('setting/{pageKey}', fn (string $pageKey) => redirect()->route('dashboard.setting.cms.edit', $pageKey, 301))
            ->where('pageKey', $cmsPageKeyPattern);
        Route::get('setting/{pageKey}/edit', [CmsPageController::class, 'edit'])->name('setting.cms.edit');
        Route::match(['put', 'post'], 'setting/{pageKey}', [CmsPageController::class, 'update'])->name('setting.cms.update');
    });

    Route::get('pendaftaran-jemaat/{submission}', fn (\App\Models\RegistrationSubmission $submission) => redirect()->route('dashboard.pendaftaran.show', ['jemaat', $submission], 301))
        ->where('submission', '[0-9]+');
    Route::get('pendaftaran-data/{slug}/{submission}', fn (string $slug, \App\Models\RegistrationSubmission $submission) => redirect()->route('dashboard.pendaftaran.show', [$slug, $submission], 301))
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::get('pendaftaran-menunggu/jemaat/{submission}', fn (\App\Models\RegistrationSubmission $submission) => redirect()->route('dashboard.pendaftaran.show', ['jemaat', $submission], 301))
        ->where('submission', '[0-9]+');
    Route::get('pendaftaran-menunggu/{slug}/{submission}', fn (string $slug, \App\Models\RegistrationSubmission $submission) => redirect()->route('dashboard.pendaftaran.show', [$slug, $submission], 301))
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);

    Route::get('pendaftaran-aktif/{slug}/export/csv', [RegistrationSubmissionController::class, 'exportCsvAccepted'])
        ->name('pendaftaran-aktif.export-csv')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran-aktif/{slug}/export/pdf', [RegistrationSubmissionController::class, 'exportPdfAccepted'])
        ->name('pendaftaran-aktif.export-pdf')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran-aktif/{slug}', [RegistrationSubmissionController::class, 'indexAccepted'])
        ->name('pendaftaran-aktif.index')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran-aktif/{slug}/{submission}/edit', [RegistrationSubmissionController::class, 'editAccepted'])
        ->name('pendaftaran-aktif.edit')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::put('pendaftaran-aktif/{slug}/{submission}', [RegistrationSubmissionController::class, 'updateAccepted'])
        ->name('pendaftaran-aktif.update')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::get('pendaftaran-aktif/{slug}/{submission}', [RegistrationSubmissionController::class, 'showAccepted'])
        ->name('pendaftaran-aktif.show')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::match(['delete', 'post'], 'pendaftaran-aktif/{slug}/{submission}', [RegistrationSubmissionController::class, 'destroyAccepted'])
        ->name('pendaftaran-aktif.destroy')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);

    Route::get('pendaftaran/{slug}/export/csv', [RegistrationSubmissionController::class, 'exportCsv'])
        ->name('pendaftaran.export-csv')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran/{slug}/export/pdf', [RegistrationSubmissionController::class, 'exportPdf'])
        ->name('pendaftaran.export-pdf')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran/{slug}', [RegistrationSubmissionController::class, 'index'])
        ->name('pendaftaran.index')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
    Route::get('pendaftaran/{slug}/{submission}/edit', [RegistrationSubmissionController::class, 'edit'])
        ->name('pendaftaran.edit')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::put('pendaftaran/{slug}/{submission}', [RegistrationSubmissionController::class, 'update'])
        ->name('pendaftaran.update')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::get('pendaftaran/{slug}/{submission}', [RegistrationSubmissionController::class, 'show'])
        ->name('pendaftaran.show')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::post('pendaftaran/{slug}/{submission}/terima', [RegistrationSubmissionController::class, 'accept'])
        ->name('pendaftaran.accept')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::post('pendaftaran/{slug}/{submission}/tolak', [RegistrationSubmissionController::class, 'reject'])
        ->name('pendaftaran.reject')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);
    Route::match(['delete', 'post'], 'pendaftaran/{slug}/{submission}', [RegistrationSubmissionController::class, 'destroy'])
        ->name('pendaftaran.destroy')
        ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*', 'submission' => '[0-9]+']);

    Route::resource('pengumuman', AnnouncementAdminController::class)
        ->parameters(['pengumuman' => 'announcement'])
        ->except(['show']);

    Route::resource('jadwal-ibadah', WorshipScheduleAdminController::class)
        ->parameters(['jadwal-ibadah' => 'schedule'])
        ->except(['show']);

    Route::get('kontak', [ContactAdminController::class, 'index'])->name('kontak.index');
    Route::get('kontak/{contact}', [ContactAdminController::class, 'show'])->name('kontak.show');
    Route::match(['delete', 'post'], 'kontak/{contact}', [ContactAdminController::class, 'destroy'])->name('kontak.destroy');

    Route::get('galeri', [GalleryAdminController::class, 'index'])->name('galeri.index');
    Route::post('galeri', [GalleryAdminController::class, 'store'])->name('galeri.store');
    Route::match(['patch', 'put', 'post'], 'galeri/{galleryItem}', [GalleryAdminController::class, 'update'])->name('galeri.update');
    Route::match(['delete', 'post'], 'galeri/{galleryItem}', [GalleryAdminController::class, 'destroy'])->name('galeri.destroy');
});
