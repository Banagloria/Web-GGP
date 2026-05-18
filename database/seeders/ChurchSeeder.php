<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\Announcement;
use App\Models\BaptismRegistration;
use App\Models\CmsPageContent;
use App\Models\CongregationRegistration;
use App\Models\Contact;
use App\Models\MarriageRegistration;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\WorshipSchedule;
use App\Support\CmsPublicPageDefaults;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Data dummy lengkap: halaman publik + isi dashboard admin.
 * Akun admin: admin@gmail.com / admin123
 * Pastikan: php artisan storage:link (untuk foto galeri di /storage)
 */
class ChurchSeeder extends Seeder
{
    /** @var list<string> */
    private array $demoImagePaths = [];

    public function run(): void
    {
        User::query()->where('email', 'admin@gereja-timika.org')->delete();

        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Gereja',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_SUPER_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        $this->call(SuperAdminSeeder::class);

        $defaults = [
            'church_name_line1' => 'GEREJA GERAKAN PANTEKOSTA',
            'church_name_line2' => 'Syalom Timika',
            'church_phone' => '0812 4031 1377',
            'church_email' => 'sekretariat@syalom-timika.test',
            'church_address' => 'Jl. Kelimutu No. 12, Distrik Mimika Baru, Timika, Papua 99971',
            'footer_whatsapp_note' => '(via WhatsApp / Telepon)',
            'social_facebook' => 'https://www.facebook.com/',
            'social_twitter' => 'https://twitter.com/',
            'social_instagram' => 'https://www.instagram.com/',
            'social_youtube' => 'https://www.youtube.com/',
            'hero_image_url' => 'https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=1600&q=80',
            'hero_script_top' => 'Selamat Datang di',
            'hero_title_gold' => 'Gereja Gerakan Pantekosta',
            'hero_title_white' => 'Syalom Timika',
            'hero_script_bottom' => 'Tuhan Memberkati',
            'vision_title' => 'Visi & panggilan kami',
            'vision_body' => "Melayani Tuhan dengan sukacita.\nMenjadi keluarga iman yang mengasihi sesama.\nMembawa kabar baik di tengah masyarakat Timika dan sekitarnya.",
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::put($key, $value);
        }

        $this->seedDemoImages();
        $this->seedCmsPages();
        $this->seedSchedules();
        $this->seedAnnouncements();
        $this->seedRegistrations();
        $this->seedContacts();
        $this->seedGallery();
    }

    private function seedDemoImages(): void
    {
        $urls = [
            'gallery/demo-1.jpg' => 'https://images.unsplash.com/photo-1507692049790-de58290a4334?w=800&q=80',
            'gallery/demo-2.jpg' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&q=80',
        ];

        foreach ($urls as $path => $url) {
            if (! Storage::disk('public')->exists($path)) {
                try {
                    $response = Http::timeout(25)->withHeaders(['Accept' => 'image/*'])->get($url);
                    if ($response->successful() && strlen($response->body()) > 800) {
                        Storage::disk('public')->put($path, $response->body());
                    }
                } catch (\Throwable) {
                    // jaringan gagal
                }
                if (! Storage::disk('public')->exists($path)) {
                    $gif = base64_decode('R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=');
                    Storage::disk('public')->put($path, $gif);
                }
            }
            $this->demoImagePaths[] = $path;
        }
    }

    private function seedCmsPages(): void
    {
        foreach (CmsPublicPageDefaults::PAGE_KEYS as $key) {
            CmsPageContent::query()->updateOrCreate(
                ['page_key' => $key],
                ['data' => CmsPublicPageDefaults::defaultsFor($key)]
            );
        }
    }

    private function seedSchedules(): void
    {
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $weekStart = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SUNDAY);

        $rows = [
            [0, '09:00:00', '11:30:00', 'Ibadah Minggu utama', 'Gedung utama', 1],
            [0, '17:00:00', '19:00:00', 'Ibadah sore / pemuda', 'Aula gereja', 2],
            [1, '18:30:00', '20:30:00', 'Pelatihan pelayanan multimedia', 'Ruang multimedia', 10],
            [2, '10:00:00', '12:00:00', 'Ibadah doa wanita', 'Ruang doa', 11],
            [3, '19:00:00', '21:00:00', 'Doa tengah minggu', 'Ruang doa', 3],
            [4, '17:00:00', '18:30:00', 'Persiapan paduan suara', 'Aula', 12],
            [5, '19:30:00', '22:00:00', 'Youth night', 'Aula gereja', 4],
            [6, '08:00:00', '10:00:00', 'Sekolah Minggu', 'Ruang anak', 5],
            [6, '10:00:00', '12:00:00', 'Kelas pemuda (diskusi Alkitab)', 'Ruang pemuda', 6],
        ];

        foreach ($rows as [$dow, $start, $end, $name, $loc, $sort]) {
            $scheduleDate = $weekStart->copy()->addDays($dow)->toDateString();
            $hari = $dayNames[$dow] ?? '';

            WorshipSchedule::query()->firstOrCreate(
                [
                    'day_of_week' => $dow,
                    'activity_name' => $name,
                    'starts_at' => $start,
                ],
                [
                    'schedule_date' => $scheduleDate,
                    'ends_at' => $end,
                    'location' => $loc,
                    'extra_columns' => [$hari, $name, $loc],
                    'is_active' => true,
                    'sort_order' => $sort,
                ]
            );
        }
    }

    private function seedAnnouncements(): void
    {
        $published = [
            ['slug' => 'ibadah-natal', 'title' => 'Ibadah Perayaan Natal', 'body' => '<p>Jemaat diundang hadir dalam sukacita perayaan Natal bersama.</p><p><em>— Sekretariat</em></p>', 'days' => 12],
            ['slug' => 'puasa-bersama-bulan-ini', 'title' => 'Puasa bersama &amp; doa syafaat', 'body' => '<p>Setiap Rabu pukul 19.00 WIT: doa puasa bersama.</p>', 'days' => 5],
            ['slug' => 'sekolah-minggu-libur', 'title' => 'Sekolah Minggu — jadwal khusus', 'body' => '<p>Pada minggu tertentu SM dimulai lebih awal. Cek papan di loby.</p>', 'days' => 2],
            ['slug' => 'gotong-royong-pembersihan', 'title' => 'Gotong royong kebersihan halaman gereja', 'body' => '<p>Sabtu pagi — merapikan taman dan parkiran.</p>', 'days' => 1],
            ['slug' => 'undangan-rapat-anggota', 'title' => 'Undangan rapat anggota', 'body' => '<p>Rapat program triwulanan; presensi dibuka 30 menit sebelum mulai.</p>', 'days' => 0],
            ['slug' => 'kolekte-khusus', 'title' => 'Kolekte untuk pembangunan', 'body' => '<p>Informasi rekening dan transparansi lapangan tersedia di sekretariat.</p>', 'days' => 3],
            ['slug' => 'ibadah-keluarga', 'title' => 'Ibadah keluarga bulanan', 'body' => '<p>Setiap minggu pertama jam 16.00 di aula.</p>', 'days' => 4],
            ['slug' => 'pelatihan-diaken', 'title' => 'Pelatihan calon diaken', 'body' => '<p>Pendaftaran melalui pendeta sidang.</p>', 'days' => 6],
            ['slug' => 'retreat-pemuda', 'title' => 'Retreat pemuda regional', 'body' => '<p>Informasi transportasi menghubungi koordinator pemuda.</p>', 'days' => 7],
            ['slug' => 'bakti-sosial', 'title' => 'Bakti sosial lingkungan', 'body' => '<p>Pengumpulan sembako setiap minggu ketiga.</p>', 'days' => 8],
            ['slug' => 'malam-pujian', 'title' => 'Malam pujian bersama', 'body' => '<p>Undangan terbuka untuk jemaat se-Papua bagian selatan.</p>', 'days' => 9],
            ['slug' => 'sosialisasi-kartu-jemaat', 'title' => 'Sosialisasi kartu jemaat digital', 'body' => '<p>Tim sekretariat akan mendampingi pengisian formulir.</p>', 'days' => 10],
            [
                'slug' => 'uji-judul-panjang-tampilan',
                'title' => 'PENGUMUMAN DENGAN JUDUL SANGAT PANJANG UNTUK MENGUJI PECAH BARIS PADA KARTU DAFTAR INFORMASI KEGIATAN DAN HALAMAN DETAIL — bagian akhir judul',
                'body' => '<p>Ini isi pengumuman dummy dengan paragraf panjang untuk menguji tipografi di tema gelap. '.str_repeat('Tuhan memberkati jemaat-Nya. ', 12).'</p><h2>Subjudul contoh</h2><p>Daftar poin:</p><ul><li>Poin pertama dengan teks cukup panjang agar terlihat perilaku wrap pada layar sempit.</li><li>Poin kedua.</li><li>Poin ketiga.</li></ul>',
                'days' => 11,
            ],
        ];

        foreach ($published as $row) {
            Announcement::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'published_at' => now()->subDays($row['days']),
                    'is_published' => true,
                ]
            );
        }

        $drafts = [
            ['slug' => 'draft-kebaktian-tahun-baru', 'title' => '[Draft] Kebaktian tahun baru', 'body' => '<p>Rencana jadwal — belum dipublikasikan.</p>'],
            ['slug' => 'draft-misi-urban', 'title' => '[Draft] Misi urban Timika', 'body' => '<p>Koordinasi dengan pemerintah distrik.</p>'],
            ['slug' => 'draft-survey-kepuasan', 'title' => '[Draft] Survey kepuasan jemaat', 'body' => '<p>Form online akan diumumkan.</p>'],
            ['slug' => 'draft-renovasi-toilet', 'title' => '[Draft] Renovasi toilet jemaat', 'body' => '<p>RAB sedang disusun.</p>'],
        ];

        foreach ($drafts as $row) {
            Announcement::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'published_at' => null,
                    'is_published' => false,
                ]
            );
        }
    }

    private function seedRegistrations(): void
    {
        $baptismNames = [
            ['Yohana Kogoya', 'submitted'],
            ['Elia Wonda', 'submitted'],
            ['Marta Bebari', 'submitted'],
            ['Paulus Amisim', 'submitted'],
            ['Ruth Imbiri', 'submitted'],
            ['Benjamin Murib', 'submitted'],
            ['Kezia Kogoya', 'active'],
            ['Obedias Wonda', 'active'],
            ['Hanna Bebari', 'active'],
            ['Mesakh Paitapi', 'archived'],
            ['Kornelius Wanimbo', 'rejected'],
        ];
        foreach ($baptismNames as $i => [$name, $status]) {
            BaptismRegistration::query()->updateOrCreate(
                ['full_name' => $name],
                [
                    'age' => 18 + ($i % 12),
                    'gender' => $i % 2 === 0 ? 'Perempuan' : 'Laki-laki',
                    'baptism_date' => now()->subMonths(($i % 5) + 1)->startOfMonth()->addDays(8 + $i),
                    'status' => $status,
                    'notes' => $status === 'submitted' ? 'Menunggu konfirmasi jadwal wawancara.' : null,
                ]
            );
        }

        $congregations = [
            ['full_name' => 'Lukas Paitapi', 'birth_date' => '1995-03-15', 'birth_place' => 'Timika', 'gender' => 'Laki-laki', 'address' => 'SP 3 Kwamki', 'phone' => '081298765001', 'email' => 'lukas.demo@mail.test', 'status' => 'submitted'],
            ['full_name' => 'Debora Kaisma', 'birth_date' => '2001-11-02', 'birth_place' => 'Nabire', 'gender' => 'Perempuan', 'address' => 'Jalan Trans Timika', 'phone' => '081298765002', 'email' => null, 'status' => 'submitted'],
            ['full_name' => 'Simon Wanimbo', 'birth_date' => '1988-07-20', 'birth_place' => 'Wamena', 'gender' => 'Laki-laki', 'address' => 'Distrik Kuala Kencana', 'phone' => '081298765003', 'email' => 'simon.demo@mail.test', 'status' => 'submitted'],
            ['full_name' => 'Miriam Kaisma', 'birth_date' => '1999-01-10', 'birth_place' => 'Timika', 'gender' => 'Perempuan', 'address' => 'Jaga IV', 'phone' => '081298765004', 'email' => 'miriam.demo@mail.test', 'status' => 'submitted'],
            ['full_name' => 'Yakobus Imbiri', 'birth_date' => '1992-05-22', 'birth_place' => 'Merauke', 'gender' => 'Laki-laki', 'address' => 'Timika Jaya', 'phone' => '081298765005', 'email' => null, 'status' => 'submitted'],
            ['full_name' => 'Rahel Wonda', 'birth_date' => '2003-09-30', 'birth_place' => 'Timika', 'gender' => 'Perempuan', 'address' => 'SP 2', 'phone' => '081298765006', 'email' => 'rahel.demo@mail.test', 'status' => 'active'],
            ['full_name' => 'Titus Murib', 'birth_date' => '1985-12-01', 'birth_place' => 'Nabire', 'gender' => 'Laki-laki', 'address' => 'Kwamki', 'phone' => '081298765007', 'email' => 'titus.demo@mail.test', 'status' => 'active'],
            ['full_name' => 'Salome Kogoya', 'birth_date' => '1997-04-18', 'birth_place' => 'Timika', 'gender' => 'Perempuan', 'address' => 'Mimika Baru', 'phone' => '081298765008', 'email' => null, 'status' => 'archived'],
        ];
        foreach ($congregations as $row) {
            CongregationRegistration::query()->updateOrCreate(
                ['full_name' => $row['full_name'], 'phone' => $row['phone']],
                $row
            );
        }

        $marriages = [
            ['Jonas Wonda', 'Ruth Kogoya', 'submitted', now()->addMonth(), '0812111111'],
            ['Petrus Amisim', 'Salome Imbiri', 'active', now()->addMonths(2), '0812222222'],
            ['Markus Paitapi', 'Lydia Wanimbo', 'submitted', now()->addMonths(3), '0812333333'],
            ['Stefanus Kaisma', 'Hanna Murib', 'submitted', now()->addMonths(4), '0812444444'],
            ['Filipus Imbiri', 'Rebekka Kogoya', 'archived', now()->addMonths(5), '0812555555'],
        ];
        foreach ($marriages as [$groom, $bride, $status, $date, $phone]) {
            MarriageRegistration::query()->updateOrCreate(
                ['groom_name' => $groom, 'bride_name' => $bride],
                [
                    'wedding_date' => $date,
                    'phone' => $phone,
                    'status' => $status,
                    'notes' => $status === 'submitted' ? 'Dummy: menunggu jadwal konseling pranikah.' : null,
                ]
            );
        }
    }

    private function seedContacts(): void
    {
        $rows = [
            ['name' => 'Tamu situs', 'email' => 'tamu@example.com', 'phone' => '0812000111', 'subject' => 'Salam', 'message' => 'Shalom, saya ingin bertanya tentang jadwal ibadah minggu pertama.', 'read_at' => null],
            ['name' => 'Ibu Santi', 'email' => 'santi.demo@mail.test', 'phone' => null, 'subject' => 'Sekolah Minggu', 'message' => 'Apakah pendaftaran anak baru masih dibuka?', 'read_at' => now()->subDay()],
            ['name' => 'Bapak Yanto', 'email' => 'yanto.demo@mail.test', 'phone' => '0812333444', 'subject' => 'Visitasi', 'message' => 'Kami keluarga baru di Timika dan ingin bersekutu.', 'read_at' => null],
            ['name' => 'Pdt. Tamu', 'email' => 'pastor.guest@mail.test', 'phone' => '0812666777', 'subject' => 'Undangan kerjasama', 'message' => 'Kami dari gereja mitra ingin mengundang dialog lintas iman.', 'read_at' => null],
            ['name' => 'Ani Wonda', 'email' => 'ani.demo@mail.test', 'phone' => '0812777888', 'subject' => 'Permohonan doa', 'message' => 'Mohon doakan keluarga kami yang sakit.', 'read_at' => null],
            ['name' => 'Rudi Kogoya', 'email' => 'rudi.demo@mail.test', 'phone' => null, 'subject' => 'Donasi', 'message' => 'Bagaimana cara transfer donasi pembangunan?', 'read_at' => now()->subHours(6)],
            ['name' => 'Meilani', 'email' => 'meilani.demo@mail.test', 'phone' => '0812888999', 'subject' => 'Pendaftaran baptisan', 'message' => 'Anak saya ingin mendaftar baptisan, usia 16 tahun.', 'read_at' => null],
            ['name' => 'Kantor PU', 'email' => 'pu.timika@mail.test', 'phone' => '0812999000', 'subject' => 'Surat izin keramaian', 'message' => 'Contoh surat resmi instansi (dummy). Mohon arsipkan.', 'read_at' => null],
            ['name' => 'Uji Pesan Panjang', 'email' => 'uji.pesan.panjang@mail.test', 'phone' => '0812111222', 'subject' => 'Pesan sangat panjang (dummy UI)', 'message' => 'Paragraf panjang untuk menguji tampilan di dashboard kontak: '.str_repeat('Shalom, kami ingin menyampaikan detail pertanyaan dan konteks. ', 25), 'read_at' => null],
        ];

        foreach ($rows as $row) {
            Contact::query()->updateOrCreate(
                ['email' => $row['email'], 'subject' => $row['subject']],
                [
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'message' => $row['message'],
                    'read_at' => $row['read_at'],
                ]
            );
        }
    }

    private function seedGallery(): void
    {
        foreach (GalleryItem::query()->get() as $item) {
            $item->deleteStoredFile();
        }
        GalleryItem::query()->delete();

        $items = [
            ['path' => 'gallery/demo-1.jpg', 'caption' => 'Ibadah dan persekutuan jemaat (dummy)'],
            ['path' => 'gallery/demo-2.jpg', 'caption' => 'Pemuda dan pujian (dummy)'],
        ];

        foreach ($items as $i => $def) {
            if (! Storage::disk('public')->exists($def['path'])) {
                continue;
            }
            GalleryItem::query()->create([
                'path' => $def['path'],
                'original_name' => basename($def['path']),
                'caption' => $def['caption'],
                'mime' => 'image/jpeg',
                'is_public' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
