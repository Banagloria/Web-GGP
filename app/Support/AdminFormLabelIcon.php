<?php

namespace App\Support;

final class AdminFormLabelIcon
{
    private const DEFAULT = 'fa-solid fa-tag';

    /** @var array<string, string> */
    private const MAP = [
        'baris nama' => 'fa-solid fa-church',
        'nama lengkap' => 'fa-solid fa-user',
        'nama mempelai pria' => 'fa-solid fa-person',
        'nama mempelai wanita' => 'fa-solid fa-person-dress',
        'nama field' => 'fa-solid fa-code',
        'tempat lahir' => 'fa-solid fa-location-pin',
        'tanggal lahir' => 'fa-solid fa-cake-candles',
        'tanggal baptis' => 'fa-solid fa-water',
        'tanggal rencana' => 'fa-solid fa-calendar-days',
        'tanggal tayang' => 'fa-solid fa-calendar-check',
        'jenis kelamin' => 'fa-solid fa-venus-mars',
        'judul utama' => 'fa-solid fa-heading',
        'judul panel' => 'fa-solid fa-window-maximize',
        'judul formulir' => 'fa-solid fa-file-pen',
        'judul emas' => 'fa-solid fa-crown',
        'judul putih' => 'fa-solid fa-heading',
        'header kolom' => 'fa-solid fa-table-columns',
        'kolom 1' => 'fa-regular fa-clock',
        'ikon kolom' => 'fa-solid fa-icons',
        'ikon label' => 'fa-solid fa-icons',
        'ikon fa' => 'fa-solid fa-icons',
        'ikon' => 'fa-solid fa-icons',
        'catatan footer' => 'fa-brands fa-whatsapp',
        'catatan singkat' => 'fa-solid fa-comment',
        'petunjuk' => 'fa-solid fa-circle-info',
        'pengantar' => 'fa-solid fa-comment-dots',
        'tampil di situs' => 'fa-solid fa-globe',
        'pesan sukses' => 'fa-solid fa-circle-check',
        'label tombol' => 'fa-solid fa-paper-plane',
        'teks keterangan' => 'fa-solid fa-asterisk',
        'baris textarea' => 'fa-solid fa-align-left',
        'opsi pilihan' => 'fa-solid fa-list',
        'facebook' => 'fa-brands fa-facebook',
        'instagram' => 'fa-brands fa-instagram',
        'youtube' => 'fa-brands fa-youtube',
        'twitter' => 'fa-brands fa-x-twitter',
        'mempelai' => 'fa-solid fa-heart',
        'identitas' => 'fa-solid fa-id-card',
        'kontak' => 'fa-solid fa-address-book',
        'sosial' => 'fa-solid fa-share-nodes',
        'hero' => 'fa-solid fa-image',
        'visi' => 'fa-solid fa-eye',
        'email' => 'fa-solid fa-envelope',
        'telepon' => 'fa-solid fa-phone',
        'whatsapp' => 'fa-brands fa-whatsapp',
        'alamat' => 'fa-solid fa-location-dot',
        'deskripsi' => 'fa-solid fa-align-left',
        'tanggal' => 'fa-solid fa-calendar-day',
        'baptis' => 'fa-solid fa-water',
        'pernikahan' => 'fa-solid fa-ring',
        'rencana' => 'fa-solid fa-calendar',
        'publik' => 'fa-solid fa-eye',
        'urutan' => 'fa-solid fa-arrow-down-wide-short',
        'status' => 'fa-solid fa-circle-check',
        'catatan' => 'fa-solid fa-note-sticky',
        'judul' => 'fa-solid fa-heading',
        'isi' => 'fa-solid fa-file-lines',
        'nama' => 'fa-solid fa-signature',
        'usia' => 'fa-solid fa-hashtag',
        'waktu' => 'fa-regular fa-clock',
        'jam mulai' => 'fa-solid fa-play',
        'jam selesai' => 'fa-solid fa-stop',
        'jam' => 'fa-solid fa-clock',
        'script' => 'fa-solid fa-scroll',
        'foto' => 'fa-solid fa-camera',
        'pilih foto' => 'fa-solid fa-images',
        'placeholder' => 'fa-solid fa-i-cursor',
        'tipe' => 'fa-solid fa-shapes',
        'lebar' => 'fa-solid fa-arrows-left-right',
        'field' => 'fa-solid fa-list-ul',
        'label' => 'fa-solid fa-tag',
        'nilai' => 'fa-solid fa-hashtag',
        'tampilkan' => 'fa-solid fa-list-ol',
        'wajib' => 'fa-solid fa-asterisk',
        'subjek' => 'fa-solid fa-tag',
        'pesan' => 'fa-solid fa-message',
        'dikirim' => 'fa-solid fa-paper-plane',
        'id' => 'fa-solid fa-hashtag',
        'ttl' => 'fa-solid fa-cake-candles',
        'mempelai pria' => 'fa-solid fa-person',
        'mempelai wanita' => 'fa-solid fa-person-dress',
        'panel' => 'fa-solid fa-table-columns',
        'formulir' => 'fa-solid fa-file-pen',
        'tombol' => 'fa-solid fa-paper-plane',
        'kirim' => 'fa-solid fa-paper-plane',
        'url' => 'fa-solid fa-link',
        'tautan' => 'fa-solid fa-link',
    ];

    public static function for(string $text, ?string $explicit = null): string
    {
        if ($explicit !== null && trim($explicit) !== '') {
            return self::normalize($explicit);
        }

        $normalized = mb_strtolower(trim(strip_tags($text)));
        if ($normalized === '') {
            return self::DEFAULT;
        }

        $keys = array_keys(self::MAP);
        usort($keys, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));

        foreach ($keys as $needle) {
            if (str_contains($normalized, $needle)) {
                return self::MAP[$needle];
            }
        }

        return self::DEFAULT;
    }

    private static function normalize(string $icon): string
    {
        $icon = trim($icon);
        if ($icon === '') {
            return self::DEFAULT;
        }

        if (! str_contains($icon, 'fa-')) {
            return 'fa-solid fa-'.ltrim($icon, '-');
        }

        return $icon;
    }
}
