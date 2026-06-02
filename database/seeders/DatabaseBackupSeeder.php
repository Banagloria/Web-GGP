<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Backup database otomatis — dihasilkan oleh: php artisan church:export-seed
 * Diekspor: 2026-05-27 13:03:49 JST
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
      'id' => 2,
      'name' => 'Super Admin',
      'email' => 'superadmin@gmail.com',
      'phone' => '085230047347',
      'profile_photo_url' => NULL,
      'email_verified_at' => '2026-05-18 16:00:22',
      'password' => '$2y$12$Xh6pgIr0kSHY5UsJR2jexeN5Y3mY1Zhmk/BcMBYx0NJihdzikm6hq',
      'role' => 'super_admin',
      'remember_token' => NULL,
      'created_at' => '2026-05-18 16:00:22',
      'updated_at' => '2026-05-21 16:48:29',
    ),
    1 => 
    array (
      'id' => 4,
      'name' => 'Bana Gloria Isterina Neslaka',
      'email' => 'rianeslaka@gamil.com',
      'phone' => '6281236871641',
      'profile_photo_url' => NULL,
      'email_verified_at' => NULL,
      'password' => '$2y$12$V9y7aJDnWjSzBY679EpPvuXi7lh.duZAuNj04vTx02nQPdeDayMP6',
      'role' => 'super_admin',
      'remember_token' => NULL,
      'created_at' => '2026-05-19 11:00:18',
      'updated_at' => '2026-05-19 11:00:18',
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
      'value' => 'Shalom Timika',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-18 23:36:22',
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
      'value' => 'Shalom Timika',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-18 23:38:41',
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
      'value' => '<h2>Visi</h2>
<p>ini adalajh paragraf visi</p>',
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-18 23:42:57',
    ),
    17 => 
    array (
      'id' => 18,
      'key' => 'site_logo_url',
      'value' => '/storage/cms/site/7Pf9tgFtOqg5aYN3Kn8aRDLExDb1pCmIXu0j6kfe.png',
      'created_at' => '2026-05-15 02:24:25',
      'updated_at' => '2026-05-19 01:22:14',
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
      'data' => '{"nav": [{"icon": "fa-solid fa-house", "label": "Beranda", "route": "/"}, {"icon": "fa-solid fa-circle-info", "label": "Profil", "route": "/profil"}, {"icon": "fa-solid fa-sitemap", "label": "Struktur", "route": "/struktur"}, {"icon": "fa-solid fa-calendar-days", "label": "Jadwal", "route": "/jadwal"}, {"icon": "fa-solid fa-clipboard-list", "label": "Pendaftaran", "route": "/pendaftaran"}, {"icon": "fa-solid fa-bullhorn", "label": "Informasi kegiatan", "route": "/informasi-kegiatan"}, {"icon": "fa-solid fa-envelope", "label": "Kontak", "route": "/kontak"}, {"icon": "fa-solid fa-images", "label": "Galeri", "route": "/galeri"}], "page_icons": {"hero_ornament": "fa-solid fa-cross", "sidebar_section": "fa-solid fa-compass", "footer_email_row": "fa-solid fa-envelope", "footer_phone_row": "fa-solid fa-phone", "layout_skip_link": "fa-solid fa-arrow-down-long", "footer_map_pin_row": "fa-solid fa-map-pin", "nav_mobile_chevron": "fa-solid fa-chevron-right", "sidebar_card_arrow": "fa-solid fa-arrow-right", "footer_social_heading": "fa-solid fa-share-nodes", "layout_header_tagline": "fa-solid fa-church", "footer_address_heading": "fa-solid fa-location-dot", "footer_contact_heading": "fa-solid fa-phone-volume"}, "vision_body": "<h2>Visi</h2>\\n<p>ini adalajh paragraf visi</p>", "vision_icon": "fa-solid fa-cross", "hero_buttons": [{"key": "btn1", "url": "/profil", "icon": "fa-solid fa-hands-praying", "label": "Kenali gereja kami", "style": "primary"}, {"key": "btn2", "url": "/jadwal", "icon": "fa-solid fa-calendar-days", "label": "Jadwal ibadah", "style": "secondary"}, {"key": "btn3", "url": "/kontak", "icon": "fa-solid fa-envelope-open-text", "label": "Hubungi kami", "style": "link"}], "vision_title": "Visi & panggilan kami", "sidebar_cards": [{"key": "c1", "url": "/informasi-kegiatan", "icon": "fa-solid fa-bullhorn", "title": "Informasi kegiatan", "subtitle": "Pengumuman & agenda"}, {"key": "c2", "url": "/galeri", "icon": "fa-solid fa-images", "title": "Galeri jemaat", "subtitle": "Momen persekutuan"}, {"key": "c3", "url": "/pendaftaran", "icon": "fa-solid fa-clipboard-list", "title": "Pendaftaran", "subtitle": "Jemaat, baptisan & lainnya"}], "site_logo_url": "/storage/cms/site/7Pf9tgFtOqg5aYN3Kn8aRDLExDb1pCmIXu0j6kfe.png", "vision_blocks": [{"text": "Visi", "type": "h2"}, {"text": "ini adalajh paragraf visi", "type": "p"}], "header_tagline": "Situs resmi jemaat — informasi ibadah & pelayanan", "hero_image_url": "https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=1600&q=80", "footer_headings": {"social": "Media sosial", "address": "Alamat", "contact": "Kontak"}, "hero_script_top": "Selamat Datang di", "hero_title_gold": "Gereja Gerakan Pantekosta", "hero_title_white": "Shalom Timika", "church_name_line1": "GEREJA GERAKAN PANTEKOSTA", "church_name_line2": "Shalom Timika", "footer_quick_links": [{"icon": "fa-solid fa-calendar-days", "label": "Jadwal", "route": "/jadwal"}, {"icon": "fa-solid fa-envelope", "label": "Kontak", "route": "/kontak"}, {"icon": "fa-solid fa-clipboard-list", "label": "Daftar", "route": "/pendaftaran"}], "hero_script_bottom": "Tuhan Memberkati", "footer_social_links": [{"url": "https://www.facebook.com/", "icon": "fa-brands fa-facebook-f", "label": "Facebook"}, {"url": "https://twitter.com/", "icon": "fa-brands fa-x-twitter", "label": "X"}, {"url": "https://www.instagram.com/", "icon": "fa-brands fa-instagram", "label": "Instagram"}, {"url": "https://www.youtube.com/", "icon": "fa-brands fa-youtube", "label": "YouTube"}], "footer_copyright_text": "© {year} Syalom Timika", "sidebar_section_title": "Jelajahi"}',
      'created_at' => '2026-05-15 02:24:25',
      'updated_at' => '2026-05-19 23:07:07',
    ),
    1 => 
    array (
      'id' => 2,
      'page_key' => 'profil',
      'data' => '{"body": "<h2>Sejarah singkat</h2>\\n<p>sejarah Gereja Gerakan Pentakosta (Pinkster Beweging) masuk ke Indonesia tidak dapat dipisahkan dengan tokoh pendirinya, yakni Rev Johannes Gerhard Thiessen, yang dilahirkan di Kitchkas, Ukraina, 22 November 1869. Tamatan Seminary Theologia St. Chrischona di Switserland, dan Tamatan Sekolah Kedokteran di Roterdam, yang menikah dengan Anna Maria Vink, mengawali pelayananya, sebagai Utusan Injil di Pulau Sumatera pada Tahun 1901. Rev Johanes Thiessen bersama isterinya meninggalkan negeri Belanda, diutus oleh Doopgzinke Kerk sebagai guru injil ke daerah Sumatera Utara untuk bekerja melayani suku Batak, dan kemudian kembali ke Belanda. Pada 1921 Thiessen bersama keluarganya meninggalkan Belanda dan kembali ke Indonesia</p>\\n<h2>Misi</h2>\\n<h2>Nilai-nilai</h2>\\n<p>Kasih, setia, dan kerendahan hati menjadi landasan setiap pelayanan kami.</p>", "title": "Profil Gereja", "blocks": [{"text": "Sejarah singkat", "type": "h2"}, {"text": "sejarah Gereja Gerakan Pentakosta (Pinkster Beweging) masuk ke Indonesia tidak dapat dipisahkan dengan tokoh pendirinya, yakni Rev Johannes Gerhard Thiessen, yang dilahirkan di Kitchkas, Ukraina, 22 November 1869. Tamatan Seminary Theologia St. Chrischona di Switserland, dan Tamatan Sekolah Kedokteran di Roterdam, yang menikah dengan Anna Maria Vink, mengawali pelayananya, sebagai Utusan Injil di Pulau Sumatera pada Tahun 1901. Rev Johanes Thiessen bersama isterinya meninggalkan negeri Belanda, diutus oleh Doopgzinke Kerk sebagai guru injil ke daerah Sumatera Utara untuk bekerja melayani suku Batak, dan kemudian kembali ke Belanda. Pada 1921 Thiessen bersama keluarganya meninggalkan Belanda dan kembali ke Indonesia", "type": "p"}, {"text": "Misi", "type": "h2"}, {"text": "Nilai-nilai", "type": "h2"}, {"text": "Kasih, setia, dan kerendahan hati menjadi landasan setiap pelayanan kami.", "type": "p"}], "page_icons": {"h1": "fa-solid fa-book-open", "breadcrumb_sep": "fa-solid fa-chevron-right", "breadcrumb_home": "fa-solid fa-house", "breadcrumb_current": "fa-regular fa-file-lines"}, "breadcrumb_home": "Beranda", "breadcrumb_current": "Profil Gereja"}',
      'created_at' => '2026-05-15 23:46:01',
      'updated_at' => '2026-05-21 21:52:49',
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
      'data' => '{"h1": "Pendaftaran", "cards": [{"key": "jemaat", "url": "/pendaftaran/jemaat", "icon": "fa-solid fa-user", "title": "Jemaat", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Formulir bagi warga baru atau transfer kartu jemaat."}, {"key": "baptis", "url": "/pendaftaran/baptisan", "icon": "fa-solid fa-droplet", "title": "Baptisan air", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Pendaftaran calon yang akan dibaptis."}, {"key": "nikah", "url": "/pendaftaran/pernikahan", "icon": "fa-solid fa-ring", "title": "Pernikahan gerejawi", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "Pendaftaran perkawinan untuk dilayani di gereja."}, {"key": "c1779852878907", "url": "/pendaftaran/jemaat-baru", "icon": "fa-solid fa-clipboard-list", "title": "jemaat baru", "cta_label": "Isi formulir", "arrow_icon": "fa-solid fa-arrow-right", "description": "jemaat mengisi formulir"}], "intro": "", "page_icons": {"index_h1": "fa-solid fa-file-signature", "index_intro": "fa-solid fa-circle-info", "index_success": "fa-solid fa-circle-check", "form_jemaat_h1": "fa-solid fa-user", "form_baptism_h1": "fa-solid fa-water", "form_jemaat_leaf": "fa-solid fa-user-plus", "form_marriage_h1": "fa-solid fa-heart", "index_card_arrow": "fa-solid fa-arrow-right", "form_baptism_leaf": "fa-solid fa-droplet", "form_jemaat_intro": "fa-solid fa-circle-info", "form_baptism_intro": "fa-solid fa-circle-info", "form_jemaat_submit": "fa-solid fa-paper-plane", "form_marriage_leaf": "fa-solid fa-ring", "form_baptism_submit": "fa-solid fa-paper-plane", "form_breadcrumb_mid": "fa-solid fa-clipboard-list", "form_breadcrumb_sep": "fa-solid fa-chevron-right", "form_marriage_intro": "fa-solid fa-circle-info", "form_breadcrumb_home": "fa-solid fa-house", "form_marriage_submit": "fa-solid fa-paper-plane", "index_breadcrumb_sep": "fa-solid fa-chevron-right", "index_breadcrumb_home": "fa-solid fa-house", "index_breadcrumb_current": "fa-solid fa-clipboard-list"}, "card_details": {"nikah": {"title": "Pendaftaran pernikahan gerejawi", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "mempelai", "icon": "fa-solid fa-heart", "title": "Data mempelai", "groups": [{"fields": [{"icon": "fa-solid fa-user", "name": "groom_name", "type": "text", "label": "Nama mempelai pria", "width": "full", "required": true, "placeholder": "Nama lengkap"}, {"icon": "fa-solid fa-user", "name": "bride_name", "type": "text", "label": "Nama mempelai wanita", "width": "full", "required": true, "placeholder": "Nama lengkap"}], "layout": "stack"}], "subtitle": "Nama lengkap kedua mempelai"}, {"key": "jadwal", "icon": "fa-solid fa-calendar-days", "title": "Jadwal & kontak", "groups": [{"fields": [{"icon": "fa-solid fa-calendar-day", "name": "wedding_date", "type": "date", "label": "Tanggal rencana", "width": "full", "required": false, "placeholder": ""}, {"icon": "fa-solid fa-phone", "name": "phone", "type": "tel", "label": "Telepon / WhatsApp", "width": "full", "required": false, "placeholder": "08xxxxxxxxxx"}], "layout": "grid"}], "subtitle": "Rencana tanggal dan nomor telepon"}], "subtitle": "Data mempelai akan diverifikasi sebelum penjadwalan konseling pranikah.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-ring", "text": "Nama mempelai sesuai KTP atau akta kelahiran."}, {"icon": "fa-solid fa-phone", "text": "Nomor telepon aktif untuk koordinasi sekretariat."}, {"icon": "fa-solid fa-shield-halved", "text": "Data dijaga untuk keperluan pelayanan pernikahan gerejawi."}], "steps": ["Isi data kedua mempelai dan kontak yang bisa dihubungi.", "Tim gereja memverifikasi data dan menjadwalkan konseling pranikah.", "Konfirmasi jadwal pernikahan gerejawi setelah persyaratan terpenuhi."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Pernikahan", "form_header": {"icon": "fa-solid fa-ring", "title": "Formulir pernikahan gerejawi", "subtitle": ""}}, "baptis": {"title": "Pendaftaran baptisan air", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "identitas", "icon": "fa-solid fa-user", "title": "Data calon baptisan", "groups": [{"fields": [{"icon": "fa-solid fa-signature", "name": "full_name", "type": "text", "label": "Nama lengkap", "width": "full", "required": true, "placeholder": "Nama sesuai dokumen"}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-hashtag", "name": "age", "type": "number", "label": "Usia", "width": "full", "required": false, "placeholder": "Tahun"}, {"icon": "fa-solid fa-venus-mars", "name": "gender", "type": "select", "label": "Jenis kelamin", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "layout": "grid"}], "subtitle": "Identitas calon baptisan"}, {"key": "jadwal", "icon": "fa-solid fa-calendar-day", "title": "Jadwal", "groups": [{"fields": [{"icon": "fa-solid fa-calendar-day", "name": "baptism_date", "type": "date", "label": "Tanggal baptis (rencana)", "width": "full", "required": false, "placeholder": ""}], "layout": "stack"}], "subtitle": "Rencana tanggal baptisan"}], "subtitle": "Isi data calon baptisan. Pendamping rohani akan menghubungi untuk jadwal wawancara.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-user", "text": "Cantumkan nama lengkap calon baptisan seperti di dokumen resmi."}, {"icon": "fa-solid fa-calendar-day", "text": "Tanggal rencana dapat disesuaikan setelah wawancara."}, {"icon": "fa-solid fa-shield-halved", "text": "Data hanya untuk keperluan pelayanan dan pendampingan rohani."}], "steps": ["Isi data calon baptisan dengan lengkap dan jujur.", "Pendamping rohani meninjau dan menjadwalkan wawancara.", "Konfirmasi jadwal baptisan air di gereja."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Baptisan", "form_header": {"icon": "fa-solid fa-water", "title": "Formulir baptisan air", "subtitle": ""}}, "jemaat": {"title": "Pendaftaran Jemaat", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Kirim pendaftaran"}, "sections": [{"key": "identitas", "icon": "fa-solid fa-user", "title": "Identitas", "fields": [{"icon": "fa-solid fa-signature", "name": "full_name", "type": "text", "label": "Nama lengkap", "width": "full", "required": true, "placeholder": "Contoh: Andreas Wanimbo"}], "groups": [{"fields": [{"icon": "fa-solid fa-signature", "name": "full_name", "type": "text", "label": "Nama lengkap", "width": "full", "required": true, "placeholder": "Contoh: Andreas Wanimbo"}], "layout": "stack"}], "subtitle": "Nama seperti tercantum di dokumen resmi"}, {"key": "kelahiran", "icon": "fa-solid fa-cake-candles", "title": "Kelahiran", "fields": [{"icon": "fa-solid fa-location-dot", "name": "birth_place", "type": "text", "label": "Tempat lahir", "width": "full", "required": false, "placeholder": "Kota / kabupaten"}, {"icon": "fa-solid fa-calendar-day", "name": "birth_date", "type": "date", "label": "Tanggal lahir", "width": "full", "required": false, "placeholder": ""}, {"icon": "fa-solid fa-venus-mars", "name": "gender", "type": "select", "label": "Jenis kelamin", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "groups": [{"fields": [{"icon": "fa-solid fa-location-dot", "name": "birth_place", "type": "text", "label": "Tempat lahir", "width": "full", "required": false, "placeholder": "Kota / kabupaten"}, {"icon": "fa-solid fa-calendar-day", "name": "birth_date", "type": "date", "label": "Tanggal lahir", "width": "full", "required": false, "placeholder": ""}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-calendar-day", "name": "birth_date", "type": "date", "label": "Tanggal lahir", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-venus-mars", "name": "gender", "type": "select", "label": "Jenis kelamin", "width": "full", "required": false, "placeholder": "", "select_options": [{"label": "— Pilih —", "value": ""}, {"label": "Laki-laki", "value": "Laki-laki"}, {"label": "Perempuan", "value": "Perempuan"}]}], "layout": "stack"}], "subtitle": "Tempat, tanggal, dan jenis kelamin"}, {"key": "domisili", "icon": "fa-solid fa-house-chimney", "title": "Domisili", "fields": [{"icon": "fa-solid fa-map-location-dot", "name": "address", "rows": 3, "type": "textarea", "label": "Alamat lengkap", "width": "full", "required": false, "placeholder": "Jl., RT/RW, kelurahan, kota"}], "groups": [{"fields": [{"icon": "fa-solid fa-map-location-dot", "name": "address", "rows": 3, "type": "textarea", "label": "Alamat lengkap", "width": "full", "required": false, "placeholder": "Jl., RT/RW, kelurahan, kota"}], "layout": "stack"}], "subtitle": "Alamat tempat tinggal saat ini"}, {"key": "kontak", "icon": "fa-solid fa-address-book", "title": "Kontak", "fields": [{"icon": "fa-solid fa-phone", "name": "phone", "type": "tel", "label": "Telepon / WhatsApp", "width": "full", "required": false, "placeholder": "08xxxxxxxxxx"}, {"icon": "fa-solid fa-envelope", "name": "email", "type": "email", "label": "Email", "width": "full", "required": false, "placeholder": "nama@email.com"}], "groups": [{"fields": [{"icon": "fa-solid fa-phone", "name": "phone", "type": "tel", "label": "Telepon / WhatsApp", "width": "full", "required": false, "placeholder": "08xxxxxxxxxx"}, {"icon": "fa-solid fa-envelope", "name": "email", "type": "email", "label": "Email", "width": "full", "required": false, "placeholder": "nama@email.com"}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-envelope", "name": "email", "type": "email", "label": "Email", "width": "full", "required": false, "placeholder": "nama@email.com"}], "layout": "stack"}], "subtitle": "Agar tim dapat menghubungi Anda"}], "subtitle": "Daftarkan diri Anda sebagai jemaat dengan melengkapi data berikut.", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-id-card", "text": "Siapkan nama lengkap dan tempat lahir seperti di KTP atau akta."}, {"icon": "fa-solid fa-phone", "text": "Nomor telepon aktif agar mudah dihubungi sekretariat."}, {"icon": "fa-solid fa-shield-halved", "text": "Data hanya digunakan untuk keperluan pelayanan jemaat."}], "steps": ["Isi identitas & data kelahiran sesuai dokumen resmi.", "Tim sekretariat meninjau dalam beberapa hari kerja.", "Anda dihubungi untuk konfirmasi atau kelengkapan berkas."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "Jemaat", "page_icons": {"form_jemaat_h1": "fa-solid fa-user", "form_jemaat_leaf": "fa-solid fa-user-plus", "form_jemaat_intro": "fa-solid fa-circle-info", "form_jemaat_submit": "fa-solid fa-paper-plane", "form_breadcrumb_mid": "fa-solid fa-clipboard-list", "form_breadcrumb_sep": "fa-solid fa-chevron-right", "form_breadcrumb_home": "fa-solid fa-house"}, "form_header": {"icon": "fa-solid fa-pen-to-square", "title": "Formulir data jemaat", "subtitle": null}}, "c1779852878907": {"title": "jemaat baru", "consent": {"text": "Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.", "submit_label": "Isi formulir"}, "sections": [{"key": "data", "icon": "fa-solid fa-user", "title": "Data pendaftar", "fields": [{"icon": "fa-solid fa-pen", "name": "nama", "type": "text", "label": "Nama Lengkap", "width": "full", "required": true, "placeholder": "Nama Lengkap"}, {"icon": "fa-solid fa-pen", "name": "nik", "type": "number", "label": "NIK", "width": "full", "required": true, "placeholder": "NIK"}, {"icon": "fa-solid fa-pen", "name": "Jenis_Kelamin", "type": "select", "label": "Jenis Kelamin", "width": "full", "required": true, "placeholder": "Jenis Kelamin", "select_options": [{"label": "Pilih", "value": ""}, {"label": "Perempuan", "value": "Perempuan"}, {"label": "Laki - Laki", "value": "Laki - Laki"}]}], "groups": [{"fields": [{"icon": "fa-solid fa-pen", "name": "nama", "type": "text", "label": "Nama Lengkap", "width": "full", "required": true, "placeholder": "Nama Lengkap"}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-pen", "name": "nik", "type": "number", "label": "NIK", "width": "full", "required": true, "placeholder": "NIK"}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-pen", "name": "Jenis_Kelamin", "type": "select", "label": "Jenis Kelamin", "width": "full", "required": true, "placeholder": "Jenis Kelamin", "select_options": [{"label": "Pilih", "value": ""}, {"label": "Perempuan", "value": "Perempuan"}, {"label": "Laki - Laki", "value": "Laki - Laki"}]}], "layout": "stack"}], "subtitle": null}, {"key": "bagian_1779853148796", "icon": "fa-solid fa-circle", "title": "bagian berkas", "fields": [{"icon": "fa-solid fa-pen", "name": "berkas_1", "type": "file", "label": "surat pernikahan", "width": "full", "required": true}, {"icon": "fa-solid fa-pen", "name": "berkas_2", "type": "file", "label": "surat pernikahan", "width": "full", "required": true}], "groups": [{"fields": [{"icon": "fa-solid fa-pen", "name": "berkas_1", "type": "file", "label": "surat pernikahan", "width": "full", "required": true}], "layout": "stack"}, {"fields": [{"icon": "fa-solid fa-pen", "name": "berkas_2", "type": "file", "label": "surat pernikahan", "width": "full", "required": true}], "layout": "stack"}], "subtitle": "butuh surat formulir"}], "subtitle": "jemaat mengisi formulir", "info_panel": {"icon": "fa-solid fa-route", "tips": [{"icon": "fa-solid fa-circle-info", "text": "Siapkan data dan dokumen pendukung jika diminta."}], "steps": ["Lengkapi formulir dengan data yang benar.", "Tim sekretariat meninjau pendaftaran Anda.", "Anda dihubungi untuk konfirmasi atau kelengkapan berkas."], "title": "Alur pendaftaran", "subtitle": "Ikuti langkah berikut", "tips_heading": "Tips", "tips_heading_icon": "fa-solid fa-lightbulb"}, "leaf_label": "jemaat baru", "page_icons": {"form_breadcrumb_mid": "fa-solid fa-clipboard-list", "form_breadcrumb_sep": "fa-solid fa-chevron-right", "form_breadcrumb_home": "fa-solid fa-house"}, "form_header": {"icon": "fa-solid fa-pen-to-square", "title": "Formulir jemaat baru", "subtitle": null}}}, "breadcrumb_home": "Beranda", "breadcrumb_current": "Pendaftaran"}',
      'created_at' => '2026-05-17 16:39:41',
      'updated_at' => '2026-05-27 13:01:45',
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
      'data' => '{"h1": "Dokumentasi Persiapan Natal Dan Dokumentasi Ibadah Perayaan Natal", "intro": null, "page_icons": {"h1": "fa-solid fa-camera-retro", "intro": "fa-solid fa-circle-info", "empty_message": "fa-regular fa-images", "lightbox_next": "fa-solid fa-chevron-right", "lightbox_prev": "fa-solid fa-chevron-left", "breadcrumb_sep": "fa-solid fa-chevron-right", "lightbox_close": "fa-solid fa-xmark", "breadcrumb_home": "fa-solid fa-house", "breadcrumb_current": "fa-solid fa-images"}, "empty_message": "Belum ada foto di galeri.", "lightbox_title": "Galeri foto — tampilan besar", "breadcrumb_home": "Beranda", "breadcrumb_current": "Galeri", "lightbox_next_label": "Foto berikutnya", "lightbox_prev_label": "Foto sebelumnya", "lightbox_close_label": "Tutup"}',
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-23 12:26:47',
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
      'id' => 14,
      'day_of_week' => 3,
      'schedule_date' => '2026-05-20',
      'starts_at' => '19:00:00',
      'ends_at' => '21:10:00',
      'activity_name' => 'selasa',
      'location' => 'ibadah kaum pria wanita',
      'extra_columns' => '["selasa", "ibadah kaum pria wanita", "Nawaripi"]',
      'is_active' => 1,
      'sort_order' => 0,
      'created_at' => '2026-05-20 18:24:46',
      'updated_at' => '2026-05-20 18:24:46',
    ),
  ),
  'announcements' => 
  array (
    0 => 
    array (
      'id' => 21,
      'title' => 'Kerja Bakti',
      'slug' => 'kerja-bakti',
      'body' => NULL,
      'published_at' => NULL,
      'is_published' => 0,
      'created_at' => '2026-05-23 09:49:43',
      'updated_at' => '2026-05-23 09:49:43',
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
      'status' => 'active',
      'notes' => NULL,
      'payload' => '{"email": "miriam.demo@mail.test", "phone": "081298765004", "gender": "Perempuan", "address": "Jaga IV", "full_name": "Miriam Kaisma", "birth_date": "1999-01-10", "birth_place": "Timika"}',
      'files' => NULL,
      'created_at' => '2026-05-12 21:53:15',
      'updated_at' => '2026-05-23 12:49:05',
    ),
    4 => 
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
    5 => 
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
    6 => 
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
    7 => 
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
    8 => 
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
    9 => 
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
    10 => 
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
    11 => 
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
    12 => 
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
    13 => 
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
    14 => 
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
    15 => 
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
    16 => 
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
    17 => 
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
    18 => 
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
    19 => 
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
    20 => 
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
    21 => 
    array (
      'id' => 37,
      'type_slug' => 'pernikahan',
      'card_key' => 'nikah',
      'status' => 'submitted',
      'notes' => NULL,
      'payload' => '{"phone": "081298765004", "bride_name": "Nikha Sandalembang", "groom_name": "Redi Yanus Neslaka", "wedding_date": "2026-08-25"}',
      'files' => NULL,
      'created_at' => '2026-05-23 20:58:46',
      'updated_at' => '2026-05-23 20:58:46',
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
      'read_at' => '2026-05-19 12:13:39',
      'created_at' => '2026-05-17 18:53:12',
      'updated_at' => '2026-05-19 12:13:39',
    ),
  ),
  'gallery_items' => 
  array (
    0 => 
    array (
      'id' => 9,
      'path' => 'gallery/CS9S1Ugg6S7WSMry7afBzgnmoWcjELi6a7gmN9YJ.png',
      'original_name' => 'logo-removebg-preview.png',
      'caption' => 'Logo Gereja',
      'mime' => 'image/png',
      'is_public' => 1,
      'sort_order' => 9,
      'created_at' => '2026-05-19 12:29:03',
      'updated_at' => '2026-05-19 12:47:21',
    ),
    1 => 
    array (
      'id' => 10,
      'path' => 'gallery/ouPhGbusFfWK2tEfAeenKxyAPEFJuJybXL1hyMoY.jpg',
      'original_name' => 'WhatsApp Image 2026-05-21 at 23.12.26.jpeg',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 10,
      'created_at' => '2026-05-23 10:29:39',
      'updated_at' => '2026-05-23 10:29:39',
    ),
    2 => 
    array (
      'id' => 11,
      'path' => 'gallery/U6gaGaECR9HKYZ9k9UGultpHNIUl1rOZ58R2XGLp.jpg',
      'original_name' => 'WhatsApp Image 2026-05-21 at 23.12.22.jpeg',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 11,
      'created_at' => '2026-05-23 10:30:12',
      'updated_at' => '2026-05-23 10:30:12',
    ),
    3 => 
    array (
      'id' => 12,
      'path' => 'gallery/xKDI4nYVFz6TiI6dTV2q8sfOQQZNgQU7tRnLuo7L.jpg',
      'original_name' => 'WhatsApp Image 2026-05-21 at 23.12.23.jpeg',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 12,
      'created_at' => '2026-05-23 10:30:12',
      'updated_at' => '2026-05-23 10:30:12',
    ),
    4 => 
    array (
      'id' => 13,
      'path' => 'gallery/TJgxDGQy8z0rmIbtnjDlc4TEz3yqxuUulNsMUSAo.jpg',
      'original_name' => 'WhatsApp Image 2026-05-21 at 23.12.24.jpeg',
      'caption' => NULL,
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 13,
      'created_at' => '2026-05-23 10:30:12',
      'updated_at' => '2026-05-23 10:30:12',
    ),
    5 => 
    array (
      'id' => 14,
      'path' => 'gallery/Jg9dcCv8BxbFsyfXwLTabw7Vqrq78CWtL92uGO8n.jpg',
      'original_name' => 'WhatsApp Image 2026-05-21 at 23.12.25.jpeg',
      'caption' => 'Dokumentasi Liturgos',
      'mime' => 'image/jpeg',
      'is_public' => 1,
      'sort_order' => 14,
      'created_at' => '2026-05-23 10:30:12',
      'updated_at' => '2026-05-23 11:03:28',
    ),
  ),
  'whatsapp_waha_configs' => 
  array (
    0 => 
    array (
      'id' => 1,
      'host' => 'https://wa.ggpshalomtimika.my.id',
      'api_key' => 'eyJpdiI6IkJLVS9JbkRZd2JjallyQjB5R2ZDN0E9PSIsInZhbHVlIjoibkJvNEY5d1Z1MHJkcVc1dXNVOGpzK1h3TktrZG84NEtZc1BqRElzd3c3TXdYb24xbS9yNFI4c1ppcmtOdzhKTWF0M0g2VkdsR2lTbWhZL1ZDV2xtU2pGQkxlZk83dU1oZlgyc3cyemhCbjA9IiwibWFjIjoiMmM1YzFmMWY1ZTM1MGRhYmYwYjE1NjBhYjFmMjVhYjkzN2UyM2UwOWUwMDZhZGQyMzI1NjYyZjAzMTg0Y2Q5OSIsInRhZyI6IiJ9',
      'session' => 'default',
      'is_connected' => 1,
      'last_connected_at' => '2026-05-23 12:49:04',
      'created_at' => '2026-05-19 09:30:41',
      'updated_at' => '2026-05-23 12:49:04',
    ),
  ),
  'whatsapp_message_templates' => 
  array (
    0 => 
    array (
      'id' => 3,
      'title' => 'cerai',
      'trigger_key' => 'pendaftaran.cerai.submit',
      'message' => 'nama saya {nama} 
saya umur {umur}
Tempat Tanggal Lahir {ttl}',
      'sort_order' => 1,
      'created_at' => '2026-05-19 11:02:16',
      'updated_at' => '2026-05-19 11:13:30',
    ),
    1 => 
    array (
      'id' => 4,
      'title' => 'kawin1',
      'trigger_key' => 'pendaftaran.perkawinan1.submit',
      'message' => 'halo saya {nama_lengkap}
Tempat tanggal Lahir = {ttl}',
      'sort_order' => 2,
      'created_at' => '2026-05-19 11:24:57',
      'updated_at' => '2026-05-19 11:24:57',
    ),
  ),
  'whatsapp_notification_recipients' => 
  array (
    0 => 
    array (
      'id' => 4,
      'user_id' => 2,
      'chat_id' => '6285230047347@c.us',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    1 => 
    array (
      'id' => 5,
      'user_id' => 4,
      'chat_id' => '6281236871641@c.us',
      'created_at' => '2026-05-19 11:23:05',
      'updated_at' => '2026-05-19 11:23:05',
    ),
  ),
  'whatsapp_notification_recipient_triggers' => 
  array (
    0 => 
    array (
      'id' => 1,
      'recipient_id' => 4,
      'trigger_key' => 'kontak.submit',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    1 => 
    array (
      'id' => 2,
      'recipient_id' => 4,
      'trigger_key' => 'pendaftaran.jemaat.submit',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    2 => 
    array (
      'id' => 3,
      'recipient_id' => 4,
      'trigger_key' => 'pendaftaran.baptisan.submit',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    3 => 
    array (
      'id' => 4,
      'recipient_id' => 4,
      'trigger_key' => 'pendaftaran.pernikahan.submit',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    4 => 
    array (
      'id' => 5,
      'recipient_id' => 4,
      'trigger_key' => 'pendaftaran.perkawinan1.submit',
      'created_at' => '2026-05-19 11:22:54',
      'updated_at' => '2026-05-19 11:22:54',
    ),
    5 => 
    array (
      'id' => 6,
      'recipient_id' => 5,
      'trigger_key' => 'pendaftaran.cerai.submit',
      'created_at' => '2026-05-19 11:23:05',
      'updated_at' => '2026-05-19 11:23:05',
    ),
  ),
  'whatsapp_broadcast_templates' => 
  array (
    0 => 
    array (
      'id' => 3,
      'trigger_key' => 'jadwal.create',
      'audience' => 'all_admins',
      'message' => 'jadwal baru berhasil di tambahkan 
{schedule_date}, {starts_at}, {ends_at}, {activity_name}, {location}, {kolom_1}, {kolom_2}, {kolom_3}',
      'sort_order' => 2,
      'created_at' => '2026-05-19 12:25:41',
      'updated_at' => '2026-05-19 12:25:41',
    ),
    1 => 
    array (
      'id' => 4,
      'trigger_key' => 'galeri.create',
      'audience' => 'one_by_one',
      'message' => 'gambar berhasil {original_name} di uploud',
      'sort_order' => 3,
      'created_at' => '2026-05-19 12:28:08',
      'updated_at' => '2026-05-19 12:28:08',
    ),
  ),
  'whatsapp_broadcast_template_users' => 
  array (
    0 => 
    array (
      'id' => 1,
      'broadcast_template_id' => 4,
      'user_id' => 2,
      'recipient_name' => 'Super Admin',
      'recipient_phone' => '6285230047347',
      'created_at' => '2026-05-19 12:28:08',
      'updated_at' => '2026-05-19 12:28:08',
    ),
  ),
);

    /** @var list<string> */
    private const TABLES = [
        'whatsapp_broadcast_template_users',
        'whatsapp_broadcast_templates',
        'whatsapp_notification_recipient_triggers',
        'whatsapp_notification_recipients',
        'whatsapp_message_templates',
        'whatsapp_waha_configs',
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
