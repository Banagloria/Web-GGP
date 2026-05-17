<?php

namespace App\Support;

/**
 * Konten bawaan halaman publik (digabung dengan isian database CMS).
 */
final class CmsPublicPageDefaults
{
    public const PAGE_KEYS = [
        'beranda',
        'profil',
        'struktur',
        'jadwal',
        'pendaftaran',
        'informasi_kegiatan',
        'kontak',
        'galeri',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function defaultsFor(string $pageKey): array
    {
        return match ($pageKey) {
            'beranda' => self::beranda(),
            'profil' => self::profil(),
            'struktur' => self::struktur(),
            'jadwal' => self::jadwal(),
            'pendaftaran' => self::pendaftaran(),
            'informasi_kegiatan' => self::informasiKegiatan(),
            'kontak' => self::kontak(),
            'galeri' => self::galeri(),
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private static function beranda(): array
    {
        return [
            'church_name_line1' => 'GEREJA GERAKAN PANTEKOSTA',
            'church_name_line2' => 'Syalom Timika',
            'site_logo_url' => null,
            'header_tagline' => 'Situs resmi jemaat — informasi ibadah & pelayanan',
            'hero_image_url' => null,
            'hero_script_top' => 'Selamat Datang di',
            'hero_title_gold' => 'Gereja Gerakan Pantekosta',
            'hero_title_white' => 'Syalom Timika',
            'hero_script_bottom' => 'Tuhan Memberkati',
            'hero_buttons' => [
                ['key' => 'btn1', 'label' => 'Kenali gereja kami', 'url' => '/profil', 'style' => 'primary', 'icon' => 'fa-solid fa-hands-praying'],
                ['key' => 'btn2', 'label' => 'Jadwal ibadah', 'url' => '/jadwal', 'style' => 'secondary', 'icon' => 'fa-solid fa-calendar-days'],
                ['key' => 'btn3', 'label' => 'Hubungi kami', 'url' => '/kontak', 'style' => 'link', 'icon' => 'fa-solid fa-envelope-open-text'],
            ],
            'vision_icon' => 'fa-solid fa-cross',
            'vision_title' => 'Visi & panggilan kami',
            'vision_body' => "Melayani Tuhan dengan sukacita.\nMenjadi keluarga iman yang mengasihi sesama.\nMembawa kabar baik di tengah masyarakat Timika dan sekitarnya.",
            'sidebar_section_title' => 'Jelajahi',
            'sidebar_cards' => [
                [
                    'key' => 'c1',
                    'icon' => 'fa-solid fa-bullhorn',
                    'title' => 'Informasi kegiatan',
                    'subtitle' => 'Pengumuman & agenda',
                    'url' => '/informasi-kegiatan',
                ],
                [
                    'key' => 'c2',
                    'icon' => 'fa-solid fa-images',
                    'title' => 'Galeri jemaat',
                    'subtitle' => 'Momen persekutuan',
                    'url' => '/galeri',
                ],
                [
                    'key' => 'c3',
                    'icon' => 'fa-solid fa-clipboard-list',
                    'title' => 'Pendaftaran',
                    'subtitle' => 'Jemaat, baptisan & lainnya',
                    'url' => '/pendaftaran',
                ],
            ],
            'nav' => [
                ['route' => '/', 'label' => 'Beranda', 'icon' => 'fa-solid fa-house'],
                ['route' => '/profil', 'label' => 'Profil', 'icon' => 'fa-solid fa-circle-info'],
                ['route' => '/struktur', 'label' => 'Struktur', 'icon' => 'fa-solid fa-sitemap'],
                ['route' => '/jadwal', 'label' => 'Jadwal', 'icon' => 'fa-solid fa-calendar-days'],
                ['route' => '/pendaftaran', 'label' => 'Pendaftaran', 'icon' => 'fa-solid fa-clipboard-list'],
                ['route' => '/informasi-kegiatan', 'label' => 'Informasi kegiatan', 'icon' => 'fa-solid fa-bullhorn'],
                ['route' => '/kontak', 'label' => 'Kontak', 'icon' => 'fa-solid fa-envelope'],
                ['route' => '/galeri', 'label' => 'Galeri', 'icon' => 'fa-solid fa-images'],
            ],
            'footer_quick_links' => [
                ['route' => '/jadwal', 'label' => 'Jadwal', 'icon' => ''],
                ['route' => '/kontak', 'label' => 'Kontak', 'icon' => ''],
                ['route' => '/pendaftaran', 'label' => 'Daftar', 'icon' => ''],
            ],
            'footer_social_links' => [
                ['url' => 'https://www.facebook.com/', 'icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
                ['url' => 'https://twitter.com/', 'icon' => 'fa-brands fa-x-twitter', 'label' => 'X'],
                ['url' => 'https://www.instagram.com/', 'icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
                ['url' => 'https://www.youtube.com/', 'icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
            ],
            'footer_headings' => [
                'contact' => 'Kontak',
                'address' => 'Alamat',
                'social' => 'Media sosial',
            ],
            'footer_copyright_text' => '© {year} Syalom Timika',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function profil(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Profil Gereja',
            'title' => 'Profil Gereja',
            'body' => <<<'HTML'
<h2>Sejarah singkat</h2>
<p>Gereja Gerakan Pantekosta Syalom Timika melayani jemaat dengan firman Tuhan, persekutuan Roh Kudus, dan pelayanan kasih di tengah kota Timika.</p>
<h2>Misi</h2>
<ul>
<li>Memuridkan anggota jemaat melalui pembinaan Alkitabiah.</li>
<li>Melayani komunitas dengan integritas dan kasih Kristus.</li>
<li>Menjalin kerja sama dengan gereja seiman di wilayah Mimika.</li>
</ul>
<h2>Nilai-nilai</h2>
<p><strong>Kasih, setia, dan kerendahan hati</strong> menjadi landasan setiap pelayanan kami.</p>
HTML,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function struktur(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Struktur Organisasi',
            'title' => 'Struktur Organisasi',
            'body' => <<<'HTML'
<h2>Majelis gereja</h2>
<p>Berikut struktur pelayanan (data dummy untuk tampilan):</p>
<ul>
<li><strong>Pendeta / gembala sidang</strong> — pimpinan rohani dan visi jemaat</li>
<li><strong>Sekretaris</strong> — administrasi surat-menyurat dan dokumen</li>
<li><strong>Bendahara</strong> — keuangan dan pelaporan kas</li>
<li><strong>Tim doa &amp; intersessi</strong> — koordinasi ibadah doa</li>
<li><strong>Tim multimedia</strong> — audio, visual, dan dokumentasi</li>
<li><strong>Sekolah Minggu &amp; pemuda</strong> — pembinaan generasi muda</li>
</ul>
<p>Untuk nama lengkap pengurus, silakan hubungi sekretariat gereja.</p>
HTML,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function jadwal(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Jadwal',
            'h1' => 'Jadwal ibadah',
            'intro' => '',
            'empty_message' => 'Belum ada jadwal.',
            'section_upcoming_title' => 'Jadwal mendatang',
            'section_completed_title' => 'Jadwal selesai',
            'show_next_label' => 'Selanjutnya',
            'table_headers_upcoming' => ['Waktu', 'Hari', 'Kegiatan', 'Lokasi', 'Aksi'],
            'table_headers_completed' => ['Waktu', 'Hari', 'Kegiatan', 'Lokasi', 'Aksi'],
            'table_column_icons_upcoming' => [
                'fa-regular fa-clock',
                'fa-solid fa-calendar-week',
                'fa-solid fa-book-bible',
                'fa-solid fa-location-dot',
                'fa-solid fa-gear',
            ],
            'table_column_icons_completed' => [
                'fa-regular fa-clock',
                'fa-solid fa-calendar-week',
                'fa-solid fa-book-bible',
                'fa-solid fa-location-dot',
                'fa-solid fa-gear',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function pendaftaran(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Pendaftaran',
            'h1' => 'Pendaftaran',
            'intro' => '',
            'cards' => [
                [
                    'key' => 'jemaat',
                    'icon' => 'fa-solid fa-user',
                    'arrow_icon' => 'fa-solid fa-arrow-right',
                    'title' => 'Pendaftaran jemaat',
                    'description' => 'Formulir bagi warga baru atau transfer kartu jemaat.',
                    'cta_label' => 'Isi formulir',
                    'url' => '/pendaftaran/jemaat',
                ],
                [
                    'key' => 'baptis',
                    'icon' => 'fa-solid fa-droplet',
                    'arrow_icon' => 'fa-solid fa-arrow-right',
                    'title' => 'Baptisan air',
                    'description' => 'Pendaftaran calon yang akan dibaptis.',
                    'cta_label' => 'Isi formulir',
                    'url' => '/pendaftaran/baptisan',
                ],
                [
                    'key' => 'nikah',
                    'icon' => 'fa-solid fa-ring',
                    'arrow_icon' => 'fa-solid fa-arrow-right',
                    'title' => 'Pernikahan gerejawi',
                    'description' => 'Pendaftaran perkawinan untuk dilayani di gereja.',
                    'cta_label' => 'Isi formulir',
                    'url' => '/pendaftaran/pernikahan',
                ],
            ],
            'card_details' => \App\Support\PendaftaranCardCms::defaultCardDetails(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function informasiKegiatan(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Informasi kegiatan',
            'h1' => 'Informasi kegiatan',
            'empty_message' => 'Belum ada pengumuman.',
            'read_more_label' => 'Baca selengkapnya',
            'show_page_h1' => 'Detail kegiatan',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function kontak(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Kontak',
            'h1' => 'Kontak',
            'form_heading' => 'Kirim pesan',
            'form_hint' => 'Isi formulir berikut; tim kami akan menghubungi Anda kembali.',
            'submit_label' => 'Kirim pesan',
            'success_message' => 'Pesan Anda telah terkirim. Tuhan memberkati.',
            'form_fields' => [
                ['name' => 'name', 'type' => 'text', 'width' => 'setengah', 'label' => 'Nama lengkap', 'placeholder' => 'Nama lengkap', 'required' => true, 'max' => 255],
                ['name' => 'email', 'type' => 'email', 'width' => 'setengah', 'label' => 'Alamat email', 'placeholder' => 'Alamat email', 'required' => true, 'max' => 255],
                ['name' => 'phone', 'type' => 'text', 'width' => 'setengah', 'label' => 'Nomor telepon', 'placeholder' => 'Nomor telepon', 'required' => true, 'max' => 50],
                ['name' => 'subject', 'type' => 'text', 'width' => 'setengah', 'label' => 'Subjek pesan', 'placeholder' => 'Subjek pesan', 'required' => true, 'max' => 255],
                ['name' => 'message', 'type' => 'textarea', 'width' => 'panjang', 'label' => 'Tulis pesan Anda', 'placeholder' => 'Tulis pesan Anda', 'required' => true, 'max' => 5000, 'rows' => 5],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function galeri(): array
    {
        return [
            'breadcrumb_home' => 'Beranda',
            'breadcrumb_current' => 'Galeri',
            'h1' => 'Galeri foto',
            'intro' => '',
            'empty_message' => 'Belum ada foto di galeri.',
            'lightbox_title' => 'Galeri foto — tampilan besar',
            'lightbox_close_label' => 'Tutup',
            'lightbox_prev_label' => 'Foto sebelumnya',
            'lightbox_next_label' => 'Foto berikutnya',
        ];
    }
}
