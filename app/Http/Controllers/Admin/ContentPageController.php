<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContentPageController extends Controller
{
    public function index(): View
    {
        $entries = [
            ['n' => 1, 'key' => 'beranda', 'label' => 'Beranda', 'path' => '/'],
            ['n' => 2, 'key' => 'profil', 'label' => 'Profil', 'path' => '/profil'],
            ['n' => 3, 'key' => 'struktur', 'label' => 'Struktur', 'path' => '/struktur'],
            ['n' => 4, 'key' => 'jadwal', 'label' => 'Jadwal', 'path' => '/jadwal'],
            ['n' => 5, 'key' => 'pendaftaran', 'label' => 'Pendaftaran', 'path' => '/pendaftaran'],
            ['n' => 6, 'key' => 'informasi_kegiatan', 'label' => 'Informasi kegiatan', 'path' => '/informasi-kegiatan'],
            ['n' => 7, 'key' => 'kontak', 'label' => 'Kontak', 'path' => '/kontak'],
            ['n' => 8, 'key' => 'galeri', 'label' => 'Galeri', 'path' => '/galeri'],
            [
                'n' => 9,
                'key' => 'notifikasi_whatsapp',
                'label' => 'Notifikasi WhatsApp',
                'path' => '/notifikasi-whatsapp',
                'route' => 'dashboard.setting.notifikasi-whatsapp.index',
            ],
        ];

        return view('admin.pages.index', compact('entries'));
    }
}
