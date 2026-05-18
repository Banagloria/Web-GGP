<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Backup database otomatis — dihasilkan oleh: php artisan church:export-seed
 * Diekspor: 2026-05-18 15:58:15 JST
 *
 * Restore ke database kosong (setelah migrate):
 *   php artisan db:seed --class=DatabaseBackupSeeder
 *
 * Atau tambahkan ke DatabaseSeeder::run() jika ingin selalu memakai backup ini.
 */
class DatabaseBackupSeeder extends Seeder
{
    /** @var array<string, list<array<string, mixed>>> */
    private const DATA = array (
  'users' => 
  array (
    0 => 
    array (
      'id' => 1,
      'name' => 'Admin Gereja',
      'email' => 'admin@gmail.com',
      'profile_photo_url' => NULL,
      'email_verified_at' => '2026-05-17 18:53:12',
      'password' => '$2y$12$QQwle6kOLN0i1ejGsY2ZEu4r6Rx7A95WXAyyMccaoItqpFV444S1.',
      'role' => 'admin',
      'remember_token' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-18 15:54:39',
    ),
  ),
  'site_settings' => 
  array (
    0 => 
    array (
      'id' => 1,
      'key' => 'church_name_line1',
      'value' => 'GEREJA GERAKAN PANTEKOSTA',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'key' => 'church_name_line2',
      'value' => 'Syalom Timika',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    2 => 
    array (
      'id' => 3,
      'key' => 'church_phone',
      'value' => '0812 4031 1377',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 4,
      'key' => 'church_email',
      'value' => 'sekretariat@syalom-timika.test',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 5,
      'key' => 'church_address',
      'value' => 'Jl. Kelimutu No. 12, Distrik Mimika Baru, Timika, Papua 99971',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 6,
      'key' => 'footer_whatsapp_note',
      'value' => '(via WhatsApp / Telepon)',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    6 => 
    array (
      'id' => 7,
      'key' => 'social_facebook',
      'value' => 'https://www.facebook.com/',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    7 => 
    array (
      'id' => 8,
      'key' => 'social_twitter',
      'value' => 'https://twitter.com/',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    8 => 
    array (
      'id' => 9,
      'key' => 'social_instagram',
      'value' => 'https://www.instagram.com/',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    9 => 
    array (
      'id' => 10,
      'key' => 'social_youtube',
      'value' => 'https://www.youtube.com/',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    10 => 
    array (
      'id' => 11,
      'key' => 'hero_image_url',
      'value' => 'https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=1600&q=80',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    11 => 
    array (
      'id' => 12,
      'key' => 'hero_script_top',
      'value' => 'Selamat Datang di',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    12 => 
    array (
      'id' => 13,
      'key' => 'hero_title_gold',
      'value' => 'Gereja Gerakan Pantekosta',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-15 02:24:25',
    ),
    13 => 
    array (
      'id' => 14,
      'key' => 'hero_title_white',
      'value' => 'Syalom Timika',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-15 02:24:25',
    ),
    14 => 
    array (
      'id' => 15,
      'key' => 'hero_script_bottom',
      'value' => 'Tuhan Memberkati',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    15 => 
    array (
      'id' => 16,
      'key' => 'vision_title',
      'value' => 'Visi & panggilan kami',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    16 => 
    array (
      'id' => 17,
      'key' => 'vision_body',
      'value' => 'Melayani Tuhan dengan sukacita.
Menjadi keluarga iman yang mengasihi sesama.
Membawa kabar baik di tengah masyarakat Timika dan sekitarnya.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    17 => 
    array (
      'id' => 18,
      'key' => 'site_logo_url',
      'value' => '',
      'created_at' => '2026-05-15 02:24:25',
      'updated_at' => '2026-05-15 02:25:33',
    ),
  ),
  'pages' => 
  array (
    0 => 
    array (
      'id' => 1,
      'slug' => 'profil',
      'title' => 'Profil Gereja',
      'body' => '<h2>Sejarah singkat</h2>
<p>Gereja Gerakan Pantekosta Syalom Timika melayani jemaat dengan firman Tuhan, persekutuan Roh Kudus, dan pelayanan kasih di tengah kota Timika.</p>
<h2>Misi</h2>
<ul>
<li>Memuridkan anggota jemaat melalui pembinaan Alkitabiah.</li>
<li>Melayani komunitas dengan integritas dan kasih Kristus.</li>
<li>Menjalin kerja sama dengan gereja seiman di wilayah Mimika.</li>
</ul>
<h2>Nilai-nilai</h2>
<p><strong>Kasih, setia, dan kerendahan hati</strong> menjadi landasan setiap pelayanan kami.</p>',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'slug' => 'struktur',
      'title' => 'Struktur Organisasi',
      'body' => '<h2>Majelis gereja</h2>
<p>Berikut struktur pelayanan (data dummy untuk tampilan):</p>
<ul>
<li><strong>Pendeta / gembala sidang</strong> — pimpinan rohani dan visi jemaat</li>
<li><strong>Sekretaris</strong> — administrasi surat-menyurat dan dokumen</li>
<li><strong>Bendahara</strong> — keuangan dan pelaporan kas</li>
<li><strong>Tim doa &amp; intersessi</strong> — koordinasi ibadah doa</li>
<li><strong>Tim multimedia</strong> — audio, visual, dan dokumentasi</li>
<li><strong>Sekolah Minggu &amp; pemuda</strong> — pembinaan generasi muda</li>
</ul>
<p>Untuk nama lengkap pengurus, silakan hubungi sekretariat gereja.</p>',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
  ),
  'cms_page_contents' => 
  array (
    0 => 
    array (
      'id' => 1,
      'page_key' => 'beranda',
      'data' => '{"nav": [{"icon": "fa-solid fa-house", "label": "Beranda", "route": "/"}, {"icon": "fa-solid fa-circle-info", "label": "Profil", "route": "/profil"}, {"icon": "fa-solid fa-sitemap", "label": "Struktur", "route": "/struktur"}, {"icon": "fa-solid fa-calendar-days", "label": "Jadwal", "route": "/jadwal"}, {"icon": "fa-solid fa-clipboard-list", "label": "Pendaftaran", "route": "/pendaftaran"}, {"icon": "fa-solid fa-bullhorn", "label": "Informasi kegiatan", "route": "/informasi-kegiatan"}, {"icon": "fa-solid fa-envelope", "label": "Kontak", "route": "/kontak"}, {"icon": "fa-solid fa-images", "label": "Galeri", "route": "/galeri"}], "vision_body": "Melayani Tuhan dengan sukacita.\\nMenjadi keluarga iman yang mengasihi sesama.\\nMembawa kabar baik di tengah masyarakat Timika dan sekitarnya.", "vision_icon": "fa-solid fa-cross", "hero_buttons": [{"key": "btn1", "url": "/profil", "icon": "fa-solid fa-hands-praying", "label": "Kenali gereja kami", "style": "primary"}, {"key": "btn2", "url": "/jadwal", "icon": "fa-solid fa-calendar-days", "label": "Jadwal ibadah", "style": "secondary"}, {"key": "btn3", "url": "/kontak", "icon": "fa-solid fa-envelope-open-text", "label": "Hubungi kami", "style": "link"}], "vision_title": "Visi & panggilan kami", "sidebar_cards": [{"key": "c1", "url": "/informasi-kegiatan", "icon": "fa-solid fa-bullhorn", "title": "Informasi kegiatan", "subtitle": "Pengumuman & agenda"}, {"key": "c2", "url": "/galeri", "icon": "fa-solid fa-images", "title": "Galeri jemaat", "subtitle": "Momen persekutuan"}, {"key": "c3", "url": "/pendaftaran", "icon": "fa-solid fa-clipboard-list", "title": "Pendaftaran", "subtitle": "Jemaat, baptisan & lainnya"}], "site_logo_url": null, "header_tagline": "Situs resmi jemaat — informasi ibadah & pelayanan", "hero_image_url": null, "footer_headings": {"social": "Media sosial", "address": "Alamat", "contact": "Kontak"}, "hero_script_top": "Selamat Datang di", "hero_title_gold": "Gereja Gerakan Pantekosta", "hero_title_white": "Syalom Timika", "church_name_line1": "GEREJA GERAKAN PANTEKOSTA", "church_name_line2": "Syalom Timika", "footer_quick_links": [{"icon": "", "label": "Jadwal", "route": "/jadwal"}, {"icon": "", "label": "Kontak", "route": "/kontak"}, {"icon": "", "label": "Daftar", "route": "/pendaftaran"}], "hero_script_bottom": "Tuhan Memberkati", "footer_social_links": [{"url": "https://www.facebook.com/", "icon": "fa-brands fa-facebook-f", "label": "Facebook"}, {"url": "https://twitter.com/", "icon": "fa-brands fa-x-twitter", "label": "X"}, {"url": "https://www.instagram.com/", "icon": "fa-brands fa-instagram", "label": "Instagram"}, {"url": "https://www.youtube.com/", "icon": "fa-brands fa-youtube", "label": "YouTube"}], "footer_copyright_text": "© {year} Syalom Timika", "sidebar_section_title": "Jelajahi"}',
      'created_at' => '2026-05-15 02:24:25',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    1 => 
    array (
      'id' => 2,
      'page_key' => 'profil',
      'data' => '{"body": "<h2>Sejarah singkat</h2>\\n<p>Gereja Gerakan Pantekosta Syalom Timika melayani jemaat dengan firman Tuhan, persekutuan Roh Kudus, dan pelayanan kasih di tengah kota Timika.</p>\\n<h2>Misi</h2>\\n<ul>\\n<li>Memuridkan anggota jemaat melalui pembinaan Alkitabiah.</li>\\n<li>Melayani komunitas dengan integritas dan kasih Kristus.</li>\\n<li>Menjalin kerja sama dengan gereja seiman di wilayah Mimika.</li>\\n</ul>\\n<h2>Nilai-nilai</h2>\\n<p><strong>Kasih, setia, dan kerendahan hati</strong> menjadi landasan setiap pelayanan kami.</p>", "title": "Profil Gereja", "breadcrumb_home": "Beranda", "breadcrumb_current": "Profil Gereja"}',
      'created_at' => '2026-05-15 23:46:01',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    2 => 
    array (
      'id' => 3,
      'page_key' => 'struktur',
      'data' => '{"body": "<h2>Majelis gereja</h2>\\n<p>Berikut struktur pelayanan (data dummy untuk tampilan):</p>\\n<ul>\\n<li><strong>Pendeta / gembala sidang</strong> — pimpinan rohani dan visi jemaat</li>\\n<li><strong>Sekretaris</strong> — administrasi surat-menyurat dan dokumen</li>\\n<li><strong>Bendahara</strong> — keuangan dan pelaporan kas</li>\\n<li><strong>Tim doa &amp; intersessi</strong> — koordinasi ibadah doa</li>\\n<li><strong>Tim multimedia</strong> — audio, visual, dan dokumentasi</li>\\n<li><strong>Sekolah Minggu &amp; pemuda</strong> — pembinaan generasi muda</li>\\n</ul>\\n<p>Untuk nama lengkap pengurus, silakan hubungi sekretariat gereja.</p>", "title": "Struktur Organisasi", "breadcrumb_home": "Beranda", "breadcrumb_current": "Struktur Organisasi"}',
      'created_at' => '2026-05-15 23:59:32',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    3 => 
    array (
      'id' => 4,
      'page_key' => 'jadwal',
      'data' => '{"h1": "Jadwal ibadah", "intro": "", "empty_message": "Belum ada jadwal.", "breadcrumb_home": "Beranda", "show_next_label": "Selanjutnya", "breadcrumb_current": "Jadwal", "section_upcoming_title": "Jadwal mendatang", "table_headers_upcoming": ["Waktu", "Hari", "Kegiatan", "Lokasi", "Aksi"], "section_completed_title": "Jadwal selesai", "table_headers_completed": ["Waktu", "Hari", "Kegiatan", "Lokasi", "Aksi"], "table_column_icons_upcoming": ["fa-regular fa-clock", "fa-solid fa-calendar-week", "fa-solid fa-book-bible", "fa-solid fa-location-dot", "fa-solid fa-gear"], "table_column_icons_completed": ["fa-regular fa-clock", "fa-solid fa-calendar-week", "fa-solid fa-book-bible", "fa-solid fa-location-dot", "fa-solid fa-gear"]}',
      'created_at' => '2026-05-17 10:13:27',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    4 => 
    array (
      'id' => 5,
      'page_key' => 'pendaftaran',
      'data' => '{"h1": "Pendaftaran", "cards": [{"key": "jemaat", "url": "/pendaftaran/jemaat", "icon": "fa-solid fa-user", "title": "Pendaftaran jemaat", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Formulir bagi warga baru atau transfer kartu jemaat."}, {"key": "baptis", "url": "/pendaftaran/baptisan", "icon": "fa-solid fa-droplet", "title": "Baptisan air", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Pendaftaran calon yang akan dibaptis."}, {"key": "nikah", "url": "/pendaftaran/pernikahan", "icon": "fa-solid fa-ring", "title": "Pernikahan gerejawi", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Pendaftaran perkawinan untuk dilayani di gereja."}], "intro": "", "card_details": {"nikah": {"title": "Pendaftaran pernikahan gerejawi", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "mempelai", "icon": "fa-solid fa-heart", "title": "Data mempelai", "groups": [{"fields": [{"icon": "fa-solid fa-user", "name": "groom_name", "type": "text", "label": "Nama mempelai pria", "width": "full", "required": true, "placeholder": "Nama lengkap"}, {"icon": "fa-solid fa-user", "name": "bride_name", "type": "text", "label": "Nama mempelai wanita", "width": "full", "required": true, "placeholder": "Nama lengkap"}], "layout": "stack"}], "subtitle": "Nama lengkap kedua mempelai"}, {"key": "jadwal", "icon": "fa-solid fa-calendar-days", "title": "Jadwal & kontak", "groups": [{"fields": [{"icon": "fa-solid fa-calendar-day", "name": "wedding_date", "type": "date", "label": "Tanggal rencana", "width": "full", "required": false, "placeholder": ""}, {"icon": "fa-solid fa-phone", "name": "phone", "type": "tel", "label": "Telepon / WhatsApp", "width": "full", "required": false, "placeholder": "08xxxxxxxxxx"}], "layout": "grid"}], "subtitle": "Rencana tanggal dan nomor telepon"}], "subtitle": "Data mempelai akan diverifikasi sebelum penjadwalan konseling pranikah.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-ring", "text": "Nama mempelai sesuai KTP atau akta kelahiran."}, {"icon": "fa-solid fa-phone", "text": "Nomor telepon aktif untuk koordinasi sekretariat."}, {"icon": "fa-solid fa-shield-halved", "text": "Data dijaga untuk keperluan pelayanan pernikahan gerejawi."}], "steps": ["Isi data kedua mempelai dan kontak yang bisa dihubungi.", "Tim gereja memverifikasi data dan menjadwalkan konseling pranikah.", "Konfirmasi jadwal pernikahan gerejawi setelah persyaratan terpenuhi."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Pernikahan", "form_header": {"icon": "fa-solid fa-ring", "title": "Formulir pernikahan gerejawi", "subtitle": ""}}, "baptis": {"title": "Pendaftaran baptisan air", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "identitas", "icon": "fa-solid fa-user", "title": "Data calon baptisan", "groups": [{"fields": [{"icon": "fa-solid fa-signature", "name": "full_name", "type": "text", "label": "Nama lengkap", "width": "full", "required": true, "placeholder": "Nama sesuai dokumen"}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-hashtag", "name": "age", "type": "number", "label": "Usia", "width": "full", "required": false, "placeholder": "Tahun"}, {"icon": "fa-solid fa-venus-mars", "name": "gender", "type": "select", "label": "Jenis kelamin", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "layout": "grid"}], "subtitle": "Identitas calon baptisan"}, {"key": "jadwal", "icon": "fa-solid fa-calendar-day", "title": "Jadwal", "groups": [{"fields": [{"icon": "fa-solid fa-calendar-day", "name": "baptism_date", "type": "date", "label": "Tanggal baptis (rencana)", "width": "full", "required": false, "placeholder": ""}], "layout": "stack"}], "subtitle": "Rencana tanggal baptisan"}], "subtitle": "Isi data calon baptisan. Pendamping rohani akan menghubungi untuk jadwal wawancara.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-user", "text": "Cantumkan nama lengkap calon baptisan seperti di dokumen resmi."}, {"icon": "fa-solid fa-calendar-day", "text": "Tanggal rencana dapat disesuaikan setelah wawancara."}, {"icon": "fa-solid fa-shield-halved", "text": "Data hanya untuk keperluan pelayanan dan pendampingan rohani."}], "steps": ["Isi data calon baptisan dengan lengkap dan jujur.", "Pendamping rohani meninjau dan menjadwalkan wawancara.", "Konfirmasi jadwal baptisan air di gereja."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Baptisan", "form_header": {"icon": "fa-solid fa-water", "title": "Formulir baptisan air", "subtitle": ""}}, "jemaat": {"title": "Pendaftaran Jemaat", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "identitas", "icon": "fa-solid fa-user", "title": "Identitas", "groups": [{"fields": [{"icon": "fa-solid fa-signature", "name": "full_name", "type": "text", "label": "Nama lengkap", "width": "full", "required": true, "placeholder": "Contoh: Andreas Wanimbo"}], "layout": "stack"}], "subtitle": "Nama seperti tercantum di dokumen resmi"}, {"key": "kelahiran", "icon": "fa-solid fa-cake-candles", "title": "Kelahiran", "groups": [{"fields": [{"icon": "fa-solid fa-location-dot", "name": "birth_place", "type": "text", "label": "Tempat lahir", "width": "full", "required": false, "placeholder": "Kota / kabupaten"}, {"icon": "fa-solid fa-calendar-day", "name": "birth_date", "type": "date", "label": "Tanggal lahir", "width": "full", "required": false, "placeholder": ""}], "layout": "grid"}, {"fields": [{"icon": "fa-solid fa-venus-mars", "name": "gender", "type": "select", "label": "Jenis kelamin", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "layout": "stack"}], "subtitle": "Tempat, tanggal, dan jenis kelamin"}, {"key": "domisili", "icon": "fa-solid fa-house-chimney", "title": "Domisili", "groups": [{"fields": [{"icon": "fa-solid fa-map-location-dot", "name": "address", "rows": 3, "type": "textarea", "label": "Alamat lengkap", "width": "full", "required": false, "placeholder": "Jl., RT/RW, kelurahan, kota"}], "layout": "stack"}], "subtitle": "Alamat tempat tinggal saat ini"}, {"key": "kontak", "icon": "fa-solid fa-address-book", "title": "Kontak", "groups": [{"fields": [{"icon": "fa-solid fa-phone", "name": "phone", "type": "tel", "label": "Telepon / WhatsApp", "width": "full", "required": false, "placeholder": "08xxxxxxxxxx"}, {"icon": "fa-solid fa-envelope", "name": "email", "type": "email", "label": "Email", "width": "full", "required": false, "placeholder": "nama@email.com"}], "layout": "grid"}], "subtitle": "Agar tim dapat menghubungi Anda"}], "subtitle": "Daftarkan diri Anda sebagai jemaat dengan melengkapi data berikut.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-id-card", "text": "Siapkan nama lengkap dan tempat lahir seperti di KTP atau akta."}, {"icon": "fa-solid fa-phone", "text": "Nomor telepon aktif agar mudah dihubungi sekretariat."}, {"icon": "fa-solid fa-shield-halved", "text": "Data hanya digunakan untuk keperluan pelayanan jemaat."}], "steps": ["Isi identitas & data kelahiran sesuai dokumen resmi.", "Tim sekretariat meninjau dalam beberapa hari kerja.", "Anda dihubungi untuk konfirmasi atau kelengkapan berkas."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Jemaat", "form_header": {"icon": "fa-solid fa-pen-to-square", "title": "Formulir data jemaat", "subtitle": ""}}}, "breadcrumb_home": "Beranda", "breadcrumb_current": "Pendaftaran"}',
      'created_at' => '2026-05-17 16:39:41',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    5 => 
    array (
      'id' => 6,
      'page_key' => 'informasi_kegiatan',
      'data' => '{"h1": "Informasi kegiatan", "intro": "", "show_page_h1": "Detail kegiatan", "empty_message": "Belum ada pengumuman.", "breadcrumb_home": "Beranda", "read_more_label": "Baca selengkapnya", "breadcrumb_current": "Informasi kegiatan"}',
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    6 => 
    array (
      'id' => 7,
      'page_key' => 'kontak',
      'data' => '{"h1": "Kontak", "form_hint": "Isi formulir berikut; tim kami akan menghubungi Anda kembali.", "page_icons": {"h1": "fa-solid fa-address-book", "submit": "fa-solid fa-paper-plane", "form_heading": "fa-solid fa-envelope-open-text", "breadcrumb_sep": "fa-solid fa-chevron-right", "status_success": "fa-solid fa-circle-check", "breadcrumb_home": "fa-solid fa-house", "breadcrumb_current": "fa-solid fa-address-book"}, "form_fields": [{"max": 255, "name": "name", "type": "text", "label": "Nama lengkap", "width": "setengah", "required": true, "placeholder": "Nama lengkap"}, {"max": 255, "name": "email", "type": "email", "label": "Alamat email", "width": "setengah", "required": true, "placeholder": "Alamat email"}, {"max": 50, "name": "phone", "type": "text", "label": "Nomor telepon", "width": "setengah", "required": true, "placeholder": "Nomor telepon"}, {"max": 255, "name": "subject", "type": "text", "label": "Subjek pesan", "width": "setengah", "required": true, "placeholder": "Subjek pesan"}, {"max": 5000, "name": "message", "rows": 5, "type": "textarea", "label": "Tulis pesan Anda", "width": "panjang", "required": true, "placeholder": "Tulis pesan Anda"}], "form_heading": "Kirim pesan", "submit_label": "Kirim pesan", "breadcrumb_home": "Beranda", "success_message": "Pesan Anda telah terkirim. Tuhan memberkati.", "breadcrumb_current": "Kontak"}',
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 20:03:19',
    ),
    7 => 
    array (
      'id' => 8,
      'page_key' => 'galeri',
      'data' => '{"h1": "Galeri foto", "intro": "", "empty_message": "Belum ada foto di galeri.", "lightbox_title": "Galeri foto — tampilan besar", "breadcrumb_home": "Beranda", "breadcrumb_current": "Galeri", "lightbox_next_label": "Foto berikutnya", "lightbox_prev_label": "Foto sebelumnya", "lightbox_close_label": "Tutup"}',
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 18:53:12',
    ),
  ),
  'worship_schedules' => 
  array (
    0 => 
    array (
      'id' => 1,
      'day_of_week' => 0,
      'schedule_date' => '2026-05-10',
      'starts_at' => '09:00:00',
      'ends_at' => '11:00:00',
      'activity_name' => 'Ibadah Minggu utama',
      'location' => 'Gedung utama',
      'extra_columns' => '["Ibadah Minggu utama", "Gedung utama"]',
      'is_active' => 1,
      'sort_order' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'day_of_week' => 0,
      'schedule_date' => '2026-05-10',
      'starts_at' => '17:00:00',
      'ends_at' => '19:00:00',
      'activity_name' => 'Ibadah sore / pemuda',
      'location' => 'Aula gereja',
      'extra_columns' => '["Ibadah sore / pemuda", "Aula gereja"]',
      'is_active' => 1,
      'sort_order' => 2,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    2 => 
    array (
      'id' => 3,
      'day_of_week' => 1,
      'schedule_date' => '2026-05-11',
      'starts_at' => '18:30:00',
      'ends_at' => '20:30:00',
      'activity_name' => 'Pelatihan pelayanan multimedia',
      'location' => 'Ruang multimedia',
      'extra_columns' => '["Pelatihan pelayanan multimedia", "Ruang multimedia"]',
      'is_active' => 1,
      'sort_order' => 10,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 5,
      'day_of_week' => 3,
      'schedule_date' => '2026-05-13',
      'starts_at' => '19:00:00',
      'ends_at' => '21:00:00',
      'activity_name' => 'Doa tengah minggu',
      'location' => 'Ruang doa',
      'extra_columns' => '["Doa tengah minggu", "Ruang doa"]',
      'is_active' => 1,
      'sort_order' => 3,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 6,
      'day_of_week' => 4,
      'schedule_date' => '2026-05-14',
      'starts_at' => '17:00:00',
      'ends_at' => '19:00:00',
      'activity_name' => 'Persiapan paduan suara',
      'location' => 'Aula',
      'extra_columns' => '["Persiapan paduan suara", "Aula"]',
      'is_active' => 1,
      'sort_order' => 12,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 7,
      'day_of_week' => 5,
      'schedule_date' => '2026-05-15',
      'starts_at' => '19:30:00',
      'ends_at' => '21:30:00',
      'activity_name' => 'Youth night',
      'location' => 'Aula gereja',
      'extra_columns' => '["Youth night", "Aula gereja"]',
      'is_active' => 1,
      'sort_order' => 4,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    6 => 
    array (
      'id' => 8,
      'day_of_week' => 6,
      'schedule_date' => '2026-05-16',
      'starts_at' => '08:00:00',
      'ends_at' => '10:00:00',
      'activity_name' => 'Sekolah Minggu',
      'location' => 'Ruang anak',
      'extra_columns' => '["Sekolah Minggu", "Ruang anak"]',
      'is_active' => 1,
      'sort_order' => 5,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    7 => 
    array (
      'id' => 9,
      'day_of_week' => 6,
      'schedule_date' => '2026-05-16',
      'starts_at' => '10:00:00',
      'ends_at' => '12:00:00',
      'activity_name' => 'Kelas pemuda (diskusi Alkitab)',
      'location' => 'Ruang pemuda',
      'extra_columns' => '["Kelas pemuda (diskusi Alkitab)", "Ruang pemuda"]',
      'is_active' => 1,
      'sort_order' => 6,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    8 => 
    array (
      'id' => 10,
      'day_of_week' => 2,
      'schedule_date' => '2026-05-19',
      'starts_at' => '10:00:00',
      'ends_at' => '12:00:00',
      'activity_name' => 'Ibadah doa wanita',
      'location' => 'Ruang doa',
      'extra_columns' => '["Selasa", "Ibadah doa wanita", "Ruang doa"]',
      'is_active' => 1,
      'sort_order' => 11,
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 18:53:12',
    ),
  ),
  'announcements' => 
  array (
    0 => 
    array (
      'id' => 1,
      'title' => 'Ibadah Perayaan Natal',
      'slug' => 'ibadah-natal',
      'body' => '<p>Jemaat diundang hadir dalam sukacita perayaan Natal bersama.</p><p><em>— Sekretariat</em></p>',
      'published_at' => '2026-05-05 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    1 => 
    array (
      'id' => 2,
      'title' => 'Puasa bersama &amp; doa syafaat',
      'slug' => 'puasa-bersama-bulan-ini',
      'body' => '<p>Setiap Rabu pukul 19.00 WIT: doa puasa bersama.</p>',
      'published_at' => '2026-05-12 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    2 => 
    array (
      'id' => 3,
      'title' => 'Sekolah Minggu — jadwal khusus',
      'slug' => 'sekolah-minggu-libur',
      'body' => '<p>Pada minggu tertentu SM dimulai lebih awal. Cek papan di loby.</p>',
      'published_at' => '2026-05-15 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    3 => 
    array (
      'id' => 4,
      'title' => 'Gotong royong kebersihan halaman gereja',
      'slug' => 'gotong-royong-pembersihan',
      'body' => '<p>Sabtu pagi — merapikan taman dan parkiran.</p>',
      'published_at' => '2026-05-16 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    4 => 
    array (
      'id' => 5,
      'title' => 'Undangan rapat anggota',
      'slug' => 'undangan-rapat-anggota',
      'body' => '<p>Rapat program triwulanan; presensi dibuka 30 menit sebelum mulai.</p>',
      'published_at' => '2026-05-17 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    5 => 
    array (
      'id' => 6,
      'title' => 'Kolekte untuk pembangunan',
      'slug' => 'kolekte-khusus',
      'body' => '<p>Informasi rekening dan transparansi lapangan tersedia di sekretariat.</p>',
      'published_at' => '2026-05-14 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    6 => 
    array (
      'id' => 7,
      'title' => 'Ibadah keluarga bulanan',
      'slug' => 'ibadah-keluarga',
      'body' => '<p>Setiap minggu pertama jam 16.00 di aula.</p>',
      'published_at' => '2026-05-13 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    7 => 
    array (
      'id' => 8,
      'title' => 'Pelatihan calon diaken',
      'slug' => 'pelatihan-diaken',
      'body' => '<p>Pendaftaran melalui pendeta sidang.</p>',
      'published_at' => '2026-05-11 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    8 => 
    array (
      'id' => 9,
      'title' => 'Retreat pemuda regional',
      'slug' => 'retreat-pemuda',
      'body' => '<p>Informasi transportasi menghubungi koordinator pemuda.</p>',
      'published_at' => '2026-05-10 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    9 => 
    array (
      'id' => 10,
      'title' => 'Bakti sosial lingkungan',
      'slug' => 'bakti-sosial',
      'body' => '<p>Pengumpulan sembako setiap minggu ketiga.</p>',
      'published_at' => '2026-05-09 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    10 => 
    array (
      'id' => 11,
      'title' => 'Malam pujian bersama',
      'slug' => 'malam-pujian',
      'body' => '<p>Undangan terbuka untuk jemaat se-Papua bagian selatan.</p>',
      'published_at' => '2026-05-08 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    11 => 
    array (
      'id' => 12,
      'title' => 'Sosialisasi kartu jemaat digital',
      'slug' => 'sosialisasi-kartu-jemaat',
      'body' => '<p>Tim sekretariat akan mendampingi pengisian formulir.</p>',
      'published_at' => '2026-05-07 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    12 => 
    array (
      'id' => 13,
      'title' => '[Draft] Kebaktian tahun baru',
      'slug' => 'draft-kebaktian-tahun-baru',
      'body' => '<p>Rencana jadwal — belum dipublikasikan.</p>',
      'published_at' => NULL,
      'is_published' => 0,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    13 => 
    array (
      'id' => 14,
      'title' => '[Draft] Misi urban Timika',
      'slug' => 'draft-misi-urban',
      'body' => '<p>Koordinasi dengan pemerintah distrik.</p>',
      'published_at' => NULL,
      'is_published' => 0,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    14 => 
    array (
      'id' => 15,
      'title' => '[Draft] Survey kepuasan jemaat',
      'slug' => 'draft-survey-kepuasan',
      'body' => '<p>Form online akan diumumkan.</p>',
      'published_at' => NULL,
      'is_published' => 0,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    15 => 
    array (
      'id' => 17,
      'title' => 'PENGUMUMAN DENGAN JUDUL SANGAT PANJANG UNTUK MENGUJI PECAH BARIS PADA KARTU DAFTAR INFORMASI KEGIATAN DAN HALAMAN DETAIL — bagian akhir judul',
      'slug' => 'uji-judul-panjang-tampilan',
      'body' => '<p>Ini isi pengumuman dummy dengan paragraf panjang untuk menguji tipografi di tema gelap. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. Tuhan memberkati jemaat-Nya. </p><h2>Subjudul contoh</h2><p>Daftar poin:</p><ul><li>Poin pertama dengan teks cukup panjang agar terlihat perilaku wrap pada layar sempit.</li><li>Poin kedua.</li><li>Poin ketiga.</li></ul>',
      'published_at' => '2026-05-06 18:53:12',
      'is_published' => 1,
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    16 => 
    array (
      'id' => 18,
      'title' => 'halo1',
      'slug' => 'halo1',
      'body' => 'halo2',
      'published_at' => '2026-05-17 23:45:00',
      'is_published' => 1,
      'created_at' => '2026-05-17 19:43:09',
      'updated_at' => '2026-05-17 19:43:09',
    ),
  ),
  'congregation_registrations' => 
  array (
    0 => 
    array (
      'id' => 1,
      'full_name' => 'Lukas Paitapi',
      'birth_date' => '1995-03-15',
      'birth_place' => 'Timika',
      'gender' => 'Laki-laki',
      'address' => 'SP 3 Kwamki',
      'phone' => '081298765001',
      'email' => 'lukas.demo@mail.test',
      'status' => 'submitted',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'full_name' => 'Debora Kaisma',
      'birth_date' => '2001-11-02',
      'birth_place' => 'Nabire',
      'gender' => 'Perempuan',
      'address' => 'Jalan Trans Timika',
      'phone' => '081298765002',
      'email' => NULL,
      'status' => 'submitted',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    2 => 
    array (
      'id' => 3,
      'full_name' => 'Simon Wanimbo',
      'birth_date' => '1988-07-20',
      'birth_place' => 'Wamena',
      'gender' => 'Laki-laki',
      'address' => 'Distrik Kuala Kencana',
      'phone' => '081298765003',
      'email' => 'simon.demo@mail.test',
      'status' => 'submitted',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 4,
      'full_name' => 'Miriam Kaisma',
      'birth_date' => '1999-01-10',
      'birth_place' => 'Timika',
      'gender' => 'Perempuan',
      'address' => 'Jaga IV',
      'phone' => '081298765004',
      'email' => 'miriam.demo@mail.test',
      'status' => 'submitted',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 5,
      'full_name' => 'Yakobus Imbiri',
      'birth_date' => '1992-05-22',
      'birth_place' => 'Merauke',
      'gender' => 'Laki-laki',
      'address' => 'Timika Jaya',
      'phone' => '081298765005',
      'email' => NULL,
      'status' => 'submitted',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 6,
      'full_name' => 'Rahel Wonda',
      'birth_date' => '2003-09-30',
      'birth_place' => 'Timika',
      'gender' => 'Perempuan',
      'address' => 'SP 2',
      'phone' => '081298765006',
      'email' => 'rahel.demo@mail.test',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    6 => 
    array (
      'id' => 7,
      'full_name' => 'Titus Murib',
      'birth_date' => '1985-12-01',
      'birth_place' => 'Nabire',
      'gender' => 'Laki-laki',
      'address' => 'Kwamki',
      'phone' => '081298765007',
      'email' => 'titus.demo@mail.test',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    7 => 
    array (
      'id' => 8,
      'full_name' => 'Salome Kogoya',
      'birth_date' => '1997-04-18',
      'birth_place' => 'Timika',
      'gender' => 'Perempuan',
      'address' => 'Mimika Baru',
      'phone' => '081298765008',
      'email' => NULL,
      'status' => 'archived',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
  ),
  'baptism_registrations' => 
  array (
    0 => 
    array (
      'id' => 1,
      'full_name' => 'Yohana Kogoya',
      'age' => 18,
      'gender' => 'Perempuan',
      'baptism_date' => '2026-04-09',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'full_name' => 'Elia Wonda',
      'age' => 19,
      'gender' => 'Laki-laki',
      'baptism_date' => '2026-03-10',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    2 => 
    array (
      'id' => 3,
      'full_name' => 'Marta Bebari',
      'age' => 20,
      'gender' => 'Perempuan',
      'baptism_date' => '2026-02-11',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 4,
      'full_name' => 'Paulus Amisim',
      'age' => 21,
      'gender' => 'Laki-laki',
      'baptism_date' => '2026-01-12',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 5,
      'full_name' => 'Ruth Imbiri',
      'age' => 22,
      'gender' => 'Perempuan',
      'baptism_date' => '2025-12-13',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 6,
      'full_name' => 'Benjamin Murib',
      'age' => 23,
      'gender' => 'Laki-laki',
      'baptism_date' => '2026-04-14',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    6 => 
    array (
      'id' => 7,
      'full_name' => 'Kezia Kogoya',
      'age' => 24,
      'gender' => 'Perempuan',
      'baptism_date' => '2026-03-15',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    7 => 
    array (
      'id' => 8,
      'full_name' => 'Obedias Wonda',
      'age' => 25,
      'gender' => 'Laki-laki',
      'baptism_date' => '2026-02-16',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    8 => 
    array (
      'id' => 9,
      'full_name' => 'Hanna Bebari',
      'age' => 26,
      'gender' => 'Perempuan',
      'baptism_date' => '2026-01-17',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    9 => 
    array (
      'id' => 10,
      'full_name' => 'Mesakh Paitapi',
      'age' => 27,
      'gender' => 'Laki-laki',
      'baptism_date' => '2025-12-18',
      'status' => 'archived',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    10 => 
    array (
      'id' => 11,
      'full_name' => 'Kornelius Wanimbo',
      'age' => 28,
      'gender' => 'Perempuan',
      'baptism_date' => '2026-04-19',
      'status' => 'rejected',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
  ),
  'marriage_registrations' => 
  array (
    0 => 
    array (
      'id' => 1,
      'groom_name' => 'Jonas Wonda',
      'bride_name' => 'Ruth Kogoya',
      'wedding_date' => '2026-06-17',
      'phone' => '0812111111',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    1 => 
    array (
      'id' => 2,
      'groom_name' => 'Petrus Amisim',
      'bride_name' => 'Salome Imbiri',
      'wedding_date' => '2026-07-17',
      'phone' => '0812222222',
      'status' => 'active',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    2 => 
    array (
      'id' => 3,
      'groom_name' => 'Markus Paitapi',
      'bride_name' => 'Lydia Wanimbo',
      'wedding_date' => '2026-08-17',
      'phone' => '0812333333',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    3 => 
    array (
      'id' => 4,
      'groom_name' => 'Stefanus Kaisma',
      'bride_name' => 'Hanna Murib',
      'wedding_date' => '2026-09-17',
      'phone' => '0812444444',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    4 => 
    array (
      'id' => 5,
      'groom_name' => 'Filipus Imbiri',
      'bride_name' => 'Rebekka Kogoya',
      'wedding_date' => '2026-10-17',
      'phone' => '0812555555',
      'status' => 'archived',
      'notes' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
  ),
  'registration_submissions' => 
  array (
    0 => 
    array (
      'id' => 1,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"email": "lukas.demo@mail.test", "phone": "081298765001", "gender": "Laki-laki", "address": "SP 3 Kwamki", "full_name": "Lukas Paitapi", "birth_date": "1995-03-15", "birth_place": "Timika"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"email": null, "phone": "081298765002", "gender": "Perempuan", "address": "Jalan Trans Timika", "full_name": "Debora Kaisma", "birth_date": "2001-11-02", "birth_place": "Nabire"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    2 => 
    array (
      'id' => 3,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"email": "simon.demo@mail.test", "phone": "081298765003", "gender": "Laki-laki", "address": "Distrik Kuala Kencana", "full_name": "Simon Wanimbo", "birth_date": "1988-07-20", "birth_place": "Wamena"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 4,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"email": "miriam.demo@mail.test", "phone": "081298765004", "gender": "Perempuan", "address": "Jaga IV", "full_name": "Miriam Kaisma", "birth_date": "1999-01-10", "birth_place": "Timika"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 5,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"email": null, "phone": "081298765005", "gender": "Laki-laki", "address": "Timika Jaya", "full_name": "Yakobus Imbiri", "birth_date": "1992-05-22", "birth_place": "Merauke"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 6,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"email": "rahel.demo@mail.test", "phone": "081298765006", "gender": "Perempuan", "address": "SP 2", "full_name": "Rahel Wonda", "birth_date": "2003-09-30", "birth_place": "Timika"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    6 => 
    array (
      'id' => 7,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"email": "titus.demo@mail.test", "phone": "081298765007", "gender": "Laki-laki", "address": "Kwamki", "full_name": "Titus Murib", "birth_date": "1985-12-01", "birth_place": "Nabire"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    7 => 
    array (
      'id' => 8,
      'type_slug' => 'jemaat',
      'card_key' => 'jemaat',
      'status' => 'archived',
      'notes' => NULL,
      'payload' => '{"email": null, "phone": "081298765008", "gender": "Perempuan", "address": "Mimika Baru", "full_name": "Salome Kogoya", "birth_date": "1997-04-18", "birth_place": "Timika"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    8 => 
    array (
      'id' => 9,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 18, "gender": "Perempuan", "full_name": "Yohana Kogoya", "baptism_date": "2026-04-09"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    9 => 
    array (
      'id' => 10,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 19, "gender": "Laki-laki", "full_name": "Elia Wonda", "baptism_date": "2026-03-10"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    10 => 
    array (
      'id' => 11,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 20, "gender": "Perempuan", "full_name": "Marta Bebari", "baptism_date": "2026-02-11"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    11 => 
    array (
      'id' => 12,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 21, "gender": "Laki-laki", "full_name": "Paulus Amisim", "baptism_date": "2026-01-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    12 => 
    array (
      'id' => 13,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 22, "gender": "Perempuan", "full_name": "Ruth Imbiri", "baptism_date": "2025-12-13"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    13 => 
    array (
      'id' => 14,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'submitted',
      'notes' => 'Menunggu konfirmasi jadwal wawancara.',
      'payload' => '{"age": 23, "gender": "Laki-laki", "full_name": "Benjamin Murib", "baptism_date": "2026-04-14"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    14 => 
    array (
      'id' => 15,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"age": 24, "gender": "Perempuan", "full_name": "Kezia Kogoya", "baptism_date": "2026-03-15"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    15 => 
    array (
      'id' => 16,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"age": 25, "gender": "Laki-laki", "full_name": "Obedias Wonda", "baptism_date": "2026-02-16"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    16 => 
    array (
      'id' => 17,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"age": 26, "gender": "Perempuan", "full_name": "Hanna Bebari", "baptism_date": "2026-01-17"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    17 => 
    array (
      'id' => 18,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'archived',
      'notes' => NULL,
      'payload' => '{"age": 27, "gender": "Laki-laki", "full_name": "Mesakh Paitapi", "baptism_date": "2025-12-18"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    18 => 
    array (
      'id' => 19,
      'type_slug' => 'baptisan',
      'card_key' => 'baptis',
      'status' => 'rejected',
      'notes' => NULL,
      'payload' => '{"age": 28, "gender": "Perempuan", "full_name": "Kornelius Wanimbo", "baptism_date": "2026-04-19"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    19 => 
    array (
      'id' => 20,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'payload' => '{"phone": "0812111111", "bride_name": "Ruth Kogoya", "groom_name": "Jonas Wonda", "wedding_date": "2026-06-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    20 => 
    array (
      'id' => 21,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"phone": "0812222222", "bride_name": "Salome Imbiri", "groom_name": "Petrus Amisim", "wedding_date": "2026-07-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    21 => 
    array (
      'id' => 22,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'payload' => '{"phone": "0812333333", "bride_name": "Lydia Wanimbo", "groom_name": "Markus Paitapi", "wedding_date": "2026-08-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    22 => 
    array (
      'id' => 23,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'submitted',
      'notes' => 'Dummy: menunggu jadwal konseling pranikah.',
      'payload' => '{"phone": "0812444444", "bride_name": "Hanna Murib", "groom_name": "Stefanus Kaisma", "wedding_date": "2026-09-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    23 => 
    array (
      'id' => 24,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'archived',
      'notes' => NULL,
      'payload' => '{"phone": "0812555555", "bride_name": "Rebekka Kogoya", "groom_name": "Filipus Imbiri", "wedding_date": "2026-10-12"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
  ),
  'contacts' => 
  array (
    0 => 
    array (
      'id' => 1,
      'name' => 'Tamu situs',
      'email' => 'tamu@example.com',
      'phone' => '0812000111',
      'subject' => 'Salam',
      'message' => 'Shalom, saya ingin bertanya tentang jadwal ibadah minggu pertama.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    1 => 
    array (
      'id' => 2,
      'name' => 'Ibu Santi',
      'email' => 'santi.demo@mail.test',
      'phone' => NULL,
      'subject' => 'Sekolah Minggu',
      'message' => 'Apakah pendaftaran anak baru masih dibuka?',
      'extra' => NULL,
      'read_at' => '2026-05-16 18:53:12',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    2 => 
    array (
      'id' => 3,
      'name' => 'Bapak Yanto',
      'email' => 'yanto.demo@mail.test',
      'phone' => '0812333444',
      'subject' => 'Visitasi',
      'message' => 'Kami keluarga baru di Timika dan ingin bersekutu.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    3 => 
    array (
      'id' => 4,
      'name' => 'Pdt. Tamu',
      'email' => 'pastor.guest@mail.test',
      'phone' => '0812666777',
      'subject' => 'Undangan kerjasama',
      'message' => 'Kami dari gereja mitra ingin mengundang dialog lintas iman.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    4 => 
    array (
      'id' => 5,
      'name' => 'Ani Wonda',
      'email' => 'ani.demo@mail.test',
      'phone' => '0812777888',
      'subject' => 'Permohonan doa',
      'message' => 'Mohon doakan keluarga kami yang sakit.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-12 21:53:15',
    ),
    5 => 
    array (
      'id' => 6,
      'name' => 'Rudi Kogoya',
      'email' => 'rudi.demo@mail.test',
      'phone' => NULL,
      'subject' => 'Donasi',
      'message' => 'Bagaimana cara transfer donasi pembangunan?',
      'extra' => NULL,
      'read_at' => '2026-05-17 12:53:12',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    6 => 
    array (
      'id' => 7,
      'name' => 'Meilani',
      'email' => 'meilani.demo@mail.test',
      'phone' => '0812888999',
      'subject' => 'Pendaftaran baptisan',
      'message' => 'Anak saya ingin mendaftar baptisan, usia 16 tahun.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    7 => 
    array (
      'id' => 8,
      'name' => 'Kantor PU',
      'email' => 'pu.timika@mail.test',
      'phone' => '0812999000',
      'subject' => 'Surat izin keramaian',
      'message' => 'Contoh surat resmi instansi (dummy). Mohon arsipkan.',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-17 18:53:12',
    ),
    8 => 
    array (
      'id' => 9,
      'name' => 'Uji Pesan Panjang',
      'email' => 'uji.pesan.panjang@mail.test',
      'phone' => '0812111222',
      'subject' => 'Pesan sangat panjang (dummy UI)',
      'message' => 'Paragraf panjang untuk menguji tampilan di dashboard kontak: Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. ',
      'extra' => NULL,
      'read_at' => NULL,
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-17 18:53:12',
    ),
  ),
  'gallery_items' => 
  array (
    0 => 
    array (
      'id' => 3,
      'path' => 'gallery/xLKZrrUKTJPlu7TrTrYAyQgL4s63ESpRxCw1n8gM.jpg',
      'original_name' => 'DSC05069.JPG',
      'caption' => 'Foto 1',
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 3,
      'created_at' => '2026-05-17 19:00:23',
      'updated_at' => '2026-05-17 19:11:00',
    ),
    1 => 
    array (
      'id' => 4,
      'path' => 'gallery/DecfI4pgubDFWJxZql4cACvQQVxsFPeabqOCudHV.jpg',
      'original_name' => 'DSC05064.JPG',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 4,
      'created_at' => '2026-05-17 19:00:23',
      'updated_at' => '2026-05-17 19:00:23',
    ),
    2 => 
    array (
      'id' => 5,
      'path' => 'gallery/4pc7PaqujEsRSDEEEfo1xHTigiarLLdB2rgiVxNh.jpg',
      'original_name' => 'DSC05017.JPG',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 5,
      'created_at' => '2026-05-17 19:00:23',
      'updated_at' => '2026-05-17 19:00:23',
    ),
    3 => 
    array (
      'id' => 6,
      'path' => 'gallery/9R4YkMrQAOdpgzwqkDIesKPUXbbLf7bTbuuOOE92.jpg',
      'original_name' => 'DSC05020.JPG',
      'caption' => 'hari senin',
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 6,
      'created_at' => '2026-05-17 19:00:23',
      'updated_at' => '2026-05-17 19:10:36',
    ),
    4 => 
    array (
      'id' => 7,
      'path' => 'gallery/QepIFprQxvadVaFbULWCLOBFCVzJuuIcECYVYMGv.jpg',
      'original_name' => 'DEV05647.JPG',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 7,
      'created_at' => '2026-05-17 19:12:24',
      'updated_at' => '2026-05-17 19:12:24',
    ),
    5 => 
    array (
      'id' => 8,
      'path' => 'gallery/YSm14ioRMbof6lILfzfqMO49bnK7YseDHXdDNIcV.jpg',
      'original_name' => 'DEV05644.JPG',
      'caption' => 'hari selasa',
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 8,
      'created_at' => '2026-05-17 19:12:24',
      'updated_at' => '2026-05-17 19:39:58',
    ),
  ),
);

    /** @var list<string> */
    private const TABLES = [
        'gallery_items',
        'contacts',
        'registration_submissions',
        'marriage_registrations',
        'baptism_registrations',
        'congregation_registrations',
        'announcements',
        'worship_schedules',
        'cms_page_contents',
        'pages',
        'site_settings',
        'users',
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (self::TABLES as $table) {
            DB::table($table)->truncate();
        }

        foreach (self::DATA as $table => $rows) {
            foreach (array_chunk($rows, 100) as $chunk) {
                if ($chunk !== []) {
                    DB::table($table)->insert($chunk);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
