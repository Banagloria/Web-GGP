<?php

namespace App\Support;

/**
 * Ikon halaman publik (Font Awesome) yang dapat disunting lewat CMS per halaman.
 *
 * @phpstan-type IconMeta array{label: string, default: string}
 */
final class CmsPageIconDefaults
{
    private const PENDAFTARAN_DYNAMIC_ICON_PATTERN = '/^form_[a-z0-9_]+_(leaf|h1|intro|submit)$/';

    /**
     * @return array<string, IconMeta>
     */
    public static function schema(string $pageKey): array
    {
        return match ($pageKey) {
            'beranda' => [
                'layout_skip_link' => ['label' => 'Header — tombol “langsung ke isi”', 'default' => 'fa-solid fa-arrow-down-long'],
                'layout_header_tagline' => ['label' => 'Header — ikon tagline di desktop', 'default' => 'fa-solid fa-church'],
                'hero_ornament' => ['label' => 'Hero — ornamen pemisah (salib)', 'default' => 'fa-solid fa-cross'],
                'sidebar_section' => ['label' => 'Kolom Jelajahi — ikon judul bagian', 'default' => 'fa-solid fa-compass'],
                'sidebar_card_arrow' => ['label' => 'Kolom Jelajahi — panah kartu', 'default' => 'fa-solid fa-arrow-right'],
                'footer_contact_heading' => ['label' => 'Footer — judul blok Kontak', 'default' => 'fa-solid fa-phone-volume'],
                'footer_phone_row' => ['label' => 'Footer — baris telepon', 'default' => 'fa-solid fa-phone'],
                'footer_email_row' => ['label' => 'Footer — baris email', 'default' => 'fa-solid fa-envelope'],
                'footer_address_heading' => ['label' => 'Footer — judul blok Alamat', 'default' => 'fa-solid fa-location-dot'],
                'footer_map_pin_row' => ['label' => 'Footer — ikon pin alamat', 'default' => 'fa-solid fa-map-pin'],
                'footer_social_heading' => ['label' => 'Footer — judul Media sosial', 'default' => 'fa-solid fa-share-nodes'],
                'nav_mobile_chevron' => ['label' => 'Menu mobile — chevron kanan tiap item', 'default' => 'fa-solid fa-chevron-right'],
            ],
            'profil' => self::profilStrukturIcons(),
            'struktur' => self::profilStrukturIcons(),
            'jadwal' => [
                'breadcrumb_home' => ['label' => 'Breadcrumb — beranda', 'default' => 'fa-solid fa-house'],
                'breadcrumb_sep' => ['label' => 'Breadcrumb — pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'breadcrumb_current' => ['label' => 'Breadcrumb — halaman ini', 'default' => 'fa-solid fa-calendar-days'],
                'h1' => ['label' => 'Judul utama (H1)', 'default' => 'fa-solid fa-calendar-check'],
                'intro' => ['label' => 'Paragraf pengantar', 'default' => 'fa-solid fa-circle-exclamation'],
                'mobile_day' => ['label' => 'Kartu mobile — badge hari', 'default' => 'fa-solid fa-calendar-week'],
                'mobile_time' => ['label' => 'Kartu mobile — waktu', 'default' => 'fa-regular fa-clock'],
                'mobile_location' => ['label' => 'Kartu mobile — lokasi', 'default' => 'fa-solid fa-location-dot'],
                'empty_mobile' => ['label' => 'Jadwal kosong (mobile)', 'default' => 'fa-regular fa-calendar-xmark'],
                'table_day' => ['label' => 'Tabel desktop — kolom hari', 'default' => 'fa-solid fa-calendar-week'],
                'table_time' => ['label' => 'Tabel desktop — kolom waktu', 'default' => 'fa-regular fa-clock'],
                'table_activity' => ['label' => 'Tabel desktop — kolom kegiatan', 'default' => 'fa-solid fa-book-bible'],
                'table_location' => ['label' => 'Tabel desktop — kolom lokasi', 'default' => 'fa-solid fa-location-dot'],
                'row_time' => ['label' => 'Baris tabel — ikon jam', 'default' => 'fa-regular fa-clock'],
                'empty_desktop' => ['label' => 'Jadwal kosong (tabel)', 'default' => 'fa-regular fa-calendar-xmark'],
                'section_upcoming' => ['label' => 'Judul bagian mendatang', 'default' => 'fa-solid fa-hourglass-half'],
                'section_completed' => ['label' => 'Judul bagian selesai', 'default' => 'fa-solid fa-circle-check'],
                'show_next' => ['label' => 'Tombol selanjutnya', 'default' => 'fa-solid fa-chevron-down'],
                'action_edit' => ['label' => 'Kolom aksi — edit', 'default' => 'fa-solid fa-pen'],
                'action_delete' => ['label' => 'Kolom aksi — hapus', 'default' => 'fa-solid fa-trash'],
            ],
            'pendaftaran' => [
                'index_success' => ['label' => 'Indeks — notifikasi sukses', 'default' => 'fa-solid fa-circle-check'],
                'index_breadcrumb_home' => ['label' => 'Indeks — breadcrumb beranda', 'default' => 'fa-solid fa-house'],
                'index_breadcrumb_sep' => ['label' => 'Indeks — breadcrumb pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'index_breadcrumb_current' => ['label' => 'Indeks — breadcrumb halaman ini', 'default' => 'fa-solid fa-clipboard-list'],
                'index_h1' => ['label' => 'Indeks — judul utama', 'default' => 'fa-solid fa-file-signature'],
                'index_intro' => ['label' => 'Indeks — pengantar', 'default' => 'fa-solid fa-circle-info'],
                'index_card_arrow' => ['label' => 'Indeks — panah kartu', 'default' => 'fa-solid fa-arrow-right'],
                'form_breadcrumb_home' => ['label' => 'Form — breadcrumb beranda', 'default' => 'fa-solid fa-house'],
                'form_breadcrumb_sep' => ['label' => 'Form — breadcrumb pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'form_breadcrumb_mid' => ['label' => 'Form — breadcrumb “Pendaftaran”', 'default' => 'fa-solid fa-clipboard-list'],
                'form_jemaat_leaf' => ['label' => 'Form jemaat — breadcrumb akhir', 'default' => 'fa-solid fa-user-plus'],
                'form_jemaat_h1' => ['label' => 'Form jemaat — judul', 'default' => 'fa-solid fa-user'],
                'form_jemaat_intro' => ['label' => 'Form jemaat — pengantar', 'default' => 'fa-solid fa-circle-info'],
                'form_jemaat_submit' => ['label' => 'Form jemaat — kirim', 'default' => 'fa-solid fa-paper-plane'],
                'form_baptism_leaf' => ['label' => 'Form baptisan — breadcrumb akhir', 'default' => 'fa-solid fa-droplet'],
                'form_baptism_h1' => ['label' => 'Form baptisan — judul', 'default' => 'fa-solid fa-water'],
                'form_baptism_intro' => ['label' => 'Form baptisan — pengantar', 'default' => 'fa-solid fa-circle-info'],
                'form_baptism_submit' => ['label' => 'Form baptisan — kirim', 'default' => 'fa-solid fa-paper-plane'],
                'form_marriage_leaf' => ['label' => 'Form nikah — breadcrumb akhir', 'default' => 'fa-solid fa-ring'],
                'form_marriage_h1' => ['label' => 'Form nikah — judul', 'default' => 'fa-solid fa-heart'],
                'form_marriage_intro' => ['label' => 'Form nikah — pengantar', 'default' => 'fa-solid fa-circle-info'],
                'form_marriage_submit' => ['label' => 'Form nikah — kirim', 'default' => 'fa-solid fa-paper-plane'],
            ],
            'informasi_kegiatan' => [
                'index_breadcrumb_home' => ['label' => 'Daftar — breadcrumb beranda', 'default' => 'fa-solid fa-house'],
                'index_breadcrumb_sep' => ['label' => 'Daftar — breadcrumb pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'index_breadcrumb_current' => ['label' => 'Daftar — breadcrumb halaman ini', 'default' => 'fa-solid fa-bullhorn'],
                'index_h1' => ['label' => 'Daftar — judul utama', 'default' => 'fa-solid fa-bullhorn'],
                'index_card_date' => ['label' => 'Daftar — ikon tanggal kartu', 'default' => 'fa-regular fa-calendar'],
                'index_card_arrow' => ['label' => 'Daftar — panah “baca selengkapnya”', 'default' => 'fa-solid fa-arrow-right'],
                'index_empty' => ['label' => 'Daftar — kosong', 'default' => 'fa-regular fa-folder-open'],
                'show_back' => ['label' => 'Detail — tautan kembali', 'default' => 'fa-solid fa-arrow-left-long'],
                'show_page_h1' => ['label' => 'Detail — judul halaman', 'default' => 'fa-solid fa-bullhorn'],
                'show_h1' => ['label' => 'Detail — ikon judul artikel', 'default' => 'fa-solid fa-newspaper'],
                'show_date' => ['label' => 'Detail — ikon tanggal', 'default' => 'fa-regular fa-calendar'],
            ],
            'kontak' => [
                'breadcrumb_home' => ['label' => 'Breadcrumb — beranda', 'default' => 'fa-solid fa-house'],
                'breadcrumb_sep' => ['label' => 'Breadcrumb — pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'breadcrumb_current' => ['label' => 'Breadcrumb — halaman ini', 'default' => 'fa-solid fa-address-book'],
                'h1' => ['label' => 'Judul utama', 'default' => 'fa-solid fa-address-book'],
                'form_heading' => ['label' => 'Formulir — judul', 'default' => 'fa-solid fa-envelope-open-text'],
                'status_success' => ['label' => 'Notifikasi terkirim', 'default' => 'fa-solid fa-circle-check'],
                'submit' => ['label' => 'Tombol kirim', 'default' => 'fa-solid fa-paper-plane'],
            ],
            'galeri' => [
                'breadcrumb_home' => ['label' => 'Breadcrumb — beranda', 'default' => 'fa-solid fa-house'],
                'breadcrumb_sep' => ['label' => 'Breadcrumb — pemisah', 'default' => 'fa-solid fa-chevron-right'],
                'breadcrumb_current' => ['label' => 'Breadcrumb — halaman ini', 'default' => 'fa-solid fa-images'],
                'h1' => ['label' => 'Judul utama', 'default' => 'fa-solid fa-camera-retro'],
                'intro' => ['label' => 'Pengantar', 'default' => 'fa-solid fa-circle-info'],
                'lightbox_close' => ['label' => 'Lightbox — tutup', 'default' => 'fa-solid fa-xmark'],
                'lightbox_prev' => ['label' => 'Lightbox — sebelumnya', 'default' => 'fa-solid fa-chevron-left'],
                'lightbox_next' => ['label' => 'Lightbox — berikutnya', 'default' => 'fa-solid fa-chevron-right'],
                'empty_message' => ['label' => 'Kosong — belum ada foto', 'default' => 'fa-regular fa-images'],
            ],
            default => [],
        };
    }

    /**
     * @return array<string, IconMeta>
     */
    private static function profilStrukturIcons(): array
    {
        return [
            'breadcrumb_home' => ['label' => 'Breadcrumb — beranda', 'default' => 'fa-solid fa-house'],
            'breadcrumb_sep' => ['label' => 'Breadcrumb — pemisah', 'default' => 'fa-solid fa-chevron-right'],
            'breadcrumb_current' => ['label' => 'Breadcrumb — halaman ini', 'default' => 'fa-regular fa-file-lines'],
            'h1' => ['label' => 'Judul utama halaman', 'default' => 'fa-solid fa-book-open'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function validationRules(string $pageKey): array
    {
        $rules = [];
        foreach (array_keys(self::schema($pageKey)) as $key) {
            $rules['page_icons.'.$key] = ['nullable', 'string', 'max:120'];
        }

        return $rules;
    }

    /**
     * Gabungkan isian tersimpan dengan bawaan dan normalisasi ke kelas FA.
     *
     * @return array<string, string>
     */
    public static function normalizeStored(string $pageKey, mixed $stored): array
    {
        $schema = self::schema($pageKey);
        if ($schema === []) {
            return [];
        }
        $stored = is_array($stored) ? $stored : [];
        $out = [];
        foreach ($schema as $key => $meta) {
            $out[$key] = CmsIcon::toFontAwesome(
                trim((string) ($stored[$key] ?? '')),
                $meta['default']
            );
        }

        if ($pageKey === 'pendaftaran') {
            foreach ($stored as $key => $value) {
                if (! is_string($key) || isset($schema[$key])) {
                    continue;
                }
                if (! self::isPendaftaranDynamicIconKey($key)) {
                    continue;
                }
                $out[$key] = CmsIcon::toFontAwesome(
                    trim((string) $value),
                    self::pendaftaranDynamicIconDefault($key)
                );
            }
        }

        return $out;
    }

    public static function pendaftaranDynamicIconMeta(string $iconKey): ?array
    {
        if (! self::isPendaftaranDynamicIconKey($iconKey)) {
            return null;
        }

        return [
            'label' => 'Form dinamis — ikon '.self::pendaftaranDynamicIconSuffixLabel($iconKey),
            'default' => self::pendaftaranDynamicIconDefault($iconKey),
        ];
    }

    private static function isPendaftaranDynamicIconKey(string $iconKey): bool
    {
        return (bool) preg_match(self::PENDAFTARAN_DYNAMIC_ICON_PATTERN, $iconKey);
    }

    private static function pendaftaranDynamicIconDefault(string $iconKey): string
    {
        return match (true) {
            str_ends_with($iconKey, '_leaf') => 'fa-solid fa-user-plus',
            str_ends_with($iconKey, '_h1') => 'fa-solid fa-file-signature',
            str_ends_with($iconKey, '_submit') => 'fa-solid fa-paper-plane',
            default => 'fa-solid fa-circle-info',
        };
    }

    private static function pendaftaranDynamicIconSuffixLabel(string $iconKey): string
    {
        return match (true) {
            str_ends_with($iconKey, '_leaf') => 'breadcrumb akhir',
            str_ends_with($iconKey, '_h1') => 'judul halaman (H1)',
            str_ends_with($iconKey, '_submit') => 'tombol kirim',
            default => 'pengantar',
        };
    }
}
