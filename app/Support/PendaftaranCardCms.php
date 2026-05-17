<?php

namespace App\Support;

/**
 * Kartu halaman pendaftaran dinamis dan CMS halaman detail formulir.
 */
final class PendaftaranCardCms
{
    /** @var list<string> */
    public const LEGACY_CARD_KEYS = ['jemaat', 'baptis', 'nikah'];

    /** @var list<string> */
    private const RESERVED_SLUGS = ['index', 'create', 'edit', 'export', 'data'];

    /**
     * @param  array<string, mixed>|null  $cms
     * @return array{key: string, admin_label: string, slug: string, icon_prefix: string}|null
     */
    public static function config(string $cardKey, ?array $cms = null): ?array
    {
        if ($cms !== null) {
            foreach ($cms['cards'] ?? [] as $card) {
                if (! is_array($card) || ($card['key'] ?? '') !== $cardKey) {
                    continue;
                }
                $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');

                return [
                    'key' => $cardKey,
                    'admin_label' => (string) ($card['title'] ?? $cardKey),
                    'slug' => $slug,
                    'icon_prefix' => self::iconPrefixForCardKey($cardKey),
                ];
            }
        }

        return match ($cardKey) {
            'jemaat' => [
                'key' => 'jemaat',
                'admin_label' => 'Pendaftaran jemaat',
                'slug' => 'jemaat',
                'icon_prefix' => 'form_jemaat',
            ],
            'baptis' => [
                'key' => 'baptis',
                'admin_label' => 'Baptisan air',
                'slug' => 'baptisan',
                'icon_prefix' => 'form_baptism',
            ],
            'nikah' => [
                'key' => 'nikah',
                'admin_label' => 'Pernikahan gerejawi',
                'slug' => 'pernikahan',
                'icon_prefix' => 'form_marriage',
            ],
            default => null,
        };
    }

    public static function iconPrefixForCardKey(string $cardKey): string
    {
        return match ($cardKey) {
            'jemaat' => 'form_jemaat',
            'baptis' => 'form_baptism',
            'nikah' => 'form_marriage',
            default => 'form_'.$cardKey,
        };
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return array{card: array<string, mixed>, cardKey: string, detail: array<string, mixed>, slug: string}|null
     */
    public static function resolveBySlug(array $cms, string $slug): ?array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }

        foreach ($cms['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }
            $cardSlug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            if ($cardSlug !== $slug) {
                continue;
            }
            $cardKey = (string) ($card['key'] ?? '');
            if ($cardKey === '') {
                return null;
            }

            return [
                'card' => $card,
                'cardKey' => $cardKey,
                'detail' => self::detailFromCms($cms, $cardKey),
                'slug' => $slug,
            ];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return list<string>
     */
    public static function cardKeysFromCms(array $cms): array
    {
        $keys = [];
        foreach ($cms['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }
            $key = trim((string) ($card['key'] ?? ''));
            if ($key !== '') {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * @param  list<array<string, mixed>>  $cards
     * @param  array<string, mixed>  $existingDetails
     * @return array<string, mixed>
     */
    public static function syncCardDetails(array $cards, array $existingDetails): array
    {
        $activeKeys = [];
        foreach ($cards as $card) {
            if (! is_array($card)) {
                continue;
            }
            $key = trim((string) ($card['key'] ?? ''));
            if ($key === '') {
                continue;
            }
            $activeKeys[] = $key;
            if (! isset($existingDetails[$key]) || ! is_array($existingDetails[$key])) {
                $existingDetails[$key] = self::scaffoldDetail($card);
            }
        }

        foreach (array_keys($existingDetails) as $storedKey) {
            if (! in_array($storedKey, $activeKeys, true)) {
                unset($existingDetails[$storedKey]);
            }
        }

        return $existingDetails;
    }

    /**
     * @param  array<string, mixed>  $card
     * @return array<string, mixed>
     */
    public static function scaffoldDetail(array $card): array
    {
        $title = trim((string) ($card['title'] ?? 'Pendaftaran'));
        if ($title === '') {
            $title = 'Pendaftaran';
        }

        return self::normalizeDetail(
            (string) ($card['key'] ?? 'custom'),
            [
                'title' => $title,
                'subtitle' => trim((string) ($card['description'] ?? '')),
                'leaf_label' => mb_strlen($title) > 24 ? mb_substr($title, 0, 24).'…' : $title,
                'form_header' => [
                    'icon' => trim((string) ($card['icon'] ?? 'fa-solid fa-pen-to-square')),
                    'title' => 'Formulir '.$title,
                    'subtitle' => '',
                ],
                'consent' => [
                    'text' => 'Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.',
                    'submit_label' => (string) ($card['cta_label'] ?? 'Kirim pendaftaran'),
                ],
                'info_panel' => [
                    'icon' => 'fa-solid fa-route',
                    'title' => 'Alur pendaftaran',
                    'subtitle' => 'Ikuti langkah berikut',
                    'tips_heading' => 'Tips',
                    'tips_heading_icon' => 'fa-solid fa-lightbulb',
                    'steps' => [
                        'Lengkapi formulir dengan data yang benar.',
                        'Tim sekretariat meninjau pendaftaran Anda.',
                        'Anda dihubungi untuk konfirmasi atau kelengkapan berkas.',
                    ],
                    'tips' => [
                        ['icon' => 'fa-solid fa-circle-info', 'text' => 'Siapkan data dan dokumen pendukung jika diminta.'],
                    ],
                ],
                'sections' => [
                    [
                        'key' => 'data',
                        'icon' => 'fa-solid fa-user',
                        'title' => 'Data pendaftar',
                        'subtitle' => '',
                        'groups' => [
                            [
                                'layout' => 'stack',
                                'fields' => [
                                    self::field('nama_lengkap', 'Nama lengkap', 'fa-solid fa-signature', required: true),
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return list<array<string, mixed>>
     */
    public static function fieldsFromDetail(array $detail): array
    {
        $fields = [];
        foreach ($detail['sections'] ?? [] as $section) {
            if (! is_array($section)) {
                continue;
            }
            foreach (self::sectionFieldsForAdmin($section) as $field) {
                if (! is_array($field)) {
                    continue;
                }
                $name = trim((string) ($field['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $fields[] = self::normalizeField($field);
            }
        }

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return array<string, list<string>>
     */
    public static function validationRulesFromDetail(array $detail): array
    {
        $rules = [];
        foreach (self::fieldsFromDetail($detail) as $field) {
            $name = $field['name'];
            $type = $field['type'];
            $required = ! empty($field['required']);
            $fieldRules = [];

            if ($type === 'file') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'file';
                $fieldRules[] = 'max:10240';
            } else {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules = match ($type) {
                    'email' => [...$fieldRules, 'email', 'max:255'],
                    'tel' => [...$fieldRules, 'string', 'max:50'],
                    'date' => [...$fieldRules, 'date'],
                    'number' => [...$fieldRules, 'numeric'],
                    'textarea' => [...$fieldRules, 'string', 'max:5000'],
                    'select' => [...$fieldRules, 'string', 'max:255'],
                    default => [...$fieldRules, 'string', 'max:255'],
                };
            }

            $rules[$name] = $fieldRules;
        }

        return $rules;
    }

    public static function isValidSlug(string $slug): bool
    {
        $slug = trim($slug);
        if ($slug === '' || in_array($slug, self::RESERVED_SLUGS, true)) {
            return false;
        }

        return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultFormDetail(string $cardKey): array
    {
        return match ($cardKey) {
            'jemaat' => self::jemaatDefaults(),
            'baptis' => self::baptisDefaults(),
            'nikah' => self::nikahDefaults(),
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultCardDetails(): array
    {
        $out = [];
        foreach (self::LEGACY_CARD_KEYS as $key) {
            $out[$key] = self::defaultFormDetail($key);
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return array<string, mixed>
     */
    public static function detailFromCms(array $cms, string $cardKey): array
    {
        $defaults = self::defaultFormDetail($cardKey);
        $stored = $cms['card_details'][$cardKey] ?? [];
        if (! is_array($stored)) {
            $stored = [];
        }

        return self::normalizeDetail($cardKey, array_replace_recursive($defaults, $stored));
    }

    /**
     * @return list<string>
     */
    public static function iconKeysForCard(string $cardKey): array
    {
        $prefix = self::iconPrefixForCardKey($cardKey);

        return [
            'form_breadcrumb_home',
            'form_breadcrumb_sep',
            'form_breadcrumb_mid',
            $prefix.'_leaf',
            $prefix.'_h1',
            $prefix.'_intro',
            $prefix.'_submit',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function validationRulesForCard(string $cardKey): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:2000'],
            'leaf_label' => ['required', 'string', 'max:120'],
            'form_header.icon' => ['nullable', 'string', 'max:120'],
            'form_header.title' => ['required', 'string', 'max:200'],
            'form_header.subtitle' => ['nullable', 'string', 'max:500'],
            'consent.text' => ['required', 'string', 'max:1000'],
            'consent.submit_label' => ['required', 'string', 'max:120'],
            'info_panel.title' => ['required', 'string', 'max:120'],
            'info_panel.subtitle' => ['nullable', 'string', 'max:200'],
            'info_panel.tips_heading' => ['required', 'string', 'max:80'],
            'info_panel.icon' => ['nullable', 'string', 'max:120'],
            'info_panel.tips_heading_icon' => ['nullable', 'string', 'max:120'],
            'info_panel.steps' => ['required', 'array', 'min:1', 'max:12'],
            'info_panel.steps.*' => ['required', 'string', 'max:500'],
            'info_panel.tips' => ['required', 'array', 'min:1', 'max:12'],
            'info_panel.tips.*.icon' => ['nullable', 'string', 'max:120'],
            'info_panel.tips.*.text' => ['required', 'string', 'max:500'],
            'sections' => ['required', 'array', 'min:1', 'max:20'],
            'sections.*.key' => ['required', 'string', 'max:50'],
            'sections.*.icon' => ['nullable', 'string', 'max:120'],
            'sections.*.title' => ['required', 'string', 'max:200'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:500'],
            'sections.*.fields' => ['required', 'array', 'min:1', 'max:40'],
            'sections.*.fields.*.name' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z0-9_]+$/'],
            'sections.*.fields.*.label' => ['required', 'string', 'max:200'],
            'sections.*.fields.*.icon' => ['nullable', 'string', 'max:120'],
            'sections.*.fields.*.type' => ['required', 'in:text,email,tel,date,number,textarea,select,file'],
            'sections.*.fields.*.width' => ['required', 'in:full,half'],
            'sections.*.fields.*.required' => ['nullable', 'in:0,1'],
            'sections.*.fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'sections.*.fields.*.rows' => ['nullable', 'integer', 'min:2', 'max:20'],
            'sections.*.fields.*.select_options' => ['nullable', 'array', 'max:20'],
            'sections.*.fields.*.select_options.*.value' => ['nullable', 'string', 'max:120'],
            'sections.*.fields.*.select_options.*.label' => ['nullable', 'string', 'max:200'],
        ];

        foreach (self::iconKeysForCard($cardKey) as $iconKey) {
            $rules['page_icons.'.$iconKey] = ['nullable', 'string', 'max:120'];
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return array<string, mixed>
     */
    public static function normalizeValidatedDetail(string $cardKey, array $detail): array
    {
        return self::normalizeDetail($cardKey, $detail);
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return array<string, mixed>
     */
    private static function normalizeDetail(string $cardKey, array $detail): array
    {
        $defaults = self::defaultFormDetail($cardKey);
        $detail = array_replace_recursive($defaults, $detail);

        if (isset($detail['sections']) && is_array($detail['sections'])) {
            foreach ($detail['sections'] as $si => $section) {
                if (! is_array($section)) {
                    continue;
                }

                $fields = $section['fields'] ?? [];
                if ($fields === [] && isset($section['groups']) && is_array($section['groups'])) {
                    $fields = self::flattenGroupsToFields($section['groups']);
                }

                $normalizedFields = [];
                foreach ($fields as $field) {
                    if (! is_array($field)) {
                        continue;
                    }
                    $name = trim((string) ($field['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }
                    $normalizedFields[] = self::normalizeField($field);
                }

                $detail['sections'][$si]['fields'] = $normalizedFields;
                $detail['sections'][$si]['groups'] = self::fieldsToGroups($normalizedFields);
            }
        }

        if (isset($detail['info_panel']) && is_array($detail['info_panel'])) {
            $steps = array_values(array_filter(
                array_map(static fn ($s) => trim((string) $s), $detail['info_panel']['steps'] ?? []),
                static fn ($s) => $s !== ''
            ));
            $detail['info_panel']['steps'] = $steps !== []
                ? $steps
                : ($defaults['info_panel']['steps'] ?? ['']);

            $tips = [];
            foreach ($detail['info_panel']['tips'] ?? [] as $tip) {
                if (! is_array($tip)) {
                    continue;
                }
                $text = trim((string) ($tip['text'] ?? ''));
                if ($text === '') {
                    continue;
                }
                $tips[] = [
                    'icon' => trim((string) ($tip['icon'] ?? 'fa-solid fa-circle-info')),
                    'text' => $text,
                ];
            }
            $detail['info_panel']['tips'] = $tips !== []
                ? $tips
                : ($defaults['info_panel']['tips'] ?? []);
        }

        return $detail;
    }

    /**
     * @param  array<string, mixed>  $field
     * @return array<string, mixed>
     */
    private static function normalizeField(array $field): array
    {
        $field['name'] = trim((string) ($field['name'] ?? ''));
        $field['label'] = trim((string) ($field['label'] ?? ''));
        $field['icon'] = trim((string) ($field['icon'] ?? 'fa-solid fa-pen'));
        $field['type'] = (string) ($field['type'] ?? 'text');
        $field['width'] = in_array(($field['width'] ?? ''), ['full', 'half'], true)
            ? $field['width']
            : 'full';
        $field['placeholder'] = trim((string) ($field['placeholder'] ?? ''));
        $field['required'] = (($field['required'] ?? '0') === '1' || $field['required'] === 1 || $field['required'] === true);

        if ($field['type'] === 'select') {
            $field['select_options'] = self::normalizeSelectOptions($field['select_options'] ?? []);
        } else {
            unset($field['select_options']);
        }

        if ($field['type'] === 'textarea') {
            $field['rows'] = (int) ($field['rows'] ?? 3);
            if ($field['rows'] < 2) {
                $field['rows'] = 2;
            }
        } else {
            unset($field['rows']);
        }

        if ($field['type'] === 'file') {
            unset($field['placeholder'], $field['select_options'], $field['rows']);
        }

        return $field;
    }

    /**
     * @param  list<array<string, mixed>>  $groups
     * @return list<array<string, mixed>>
     */
    public static function flattenGroupsToFields(array $groups): array
    {
        $fields = [];
        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }
            $layout = ($group['layout'] ?? 'stack') === 'grid' ? 'half' : 'full';
            foreach ($group['fields'] ?? [] as $field) {
                if (! is_array($field)) {
                    continue;
                }
                if (! isset($field['width'])) {
                    $field['width'] = $layout;
                }
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return list<array{layout: string, fields: list<array<string, mixed>>}>
     */
    public static function fieldsToGroups(array $fields): array
    {
        $groups = [];
        $gridBuffer = [];

        $flushGrid = static function () use (&$groups, &$gridBuffer): void {
            if ($gridBuffer === []) {
                return;
            }
            $groups[] = ['layout' => 'grid', 'fields' => $gridBuffer];
            $gridBuffer = [];
        };

        foreach ($fields as $field) {
            if (! is_array($field)) {
                continue;
            }
            $width = ($field['width'] ?? 'full') === 'half' ? 'half' : 'full';
            if ($width === 'half') {
                $gridBuffer[] = $field;
                if (count($gridBuffer) >= 2) {
                    $flushGrid();
                }
            } else {
                $flushGrid();
                $groups[] = ['layout' => 'stack', 'fields' => [$field]];
            }
        }

        $flushGrid();

        return $groups;
    }

    /**
     * @param  array<string, mixed>  $section
     * @return list<array<string, mixed>>
     */
    public static function sectionFieldsForAdmin(array $section): array
    {
        $fields = $section['fields'] ?? [];
        if ($fields !== []) {
            return $fields;
        }

        if (isset($section['groups']) && is_array($section['groups'])) {
            return self::flattenGroupsToFields($section['groups']);
        }

        return [];
    }

    /**
     * @param  mixed  $options
     * @return list<array{value: string, label: string}>
     */
    private static function normalizeSelectOptions(mixed $options): array
    {
        if (! is_array($options)) {
            return [];
        }

        $out = [];
        foreach ($options as $row) {
            if (! is_array($row)) {
                continue;
            }
            $out[] = [
                'value' => (string) ($row['value'] ?? ''),
                'label' => (string) ($row['label'] ?? ''),
            ];
        }

        return $out;
    }

    public static function assertCardKey(string $cardKey, ?array $cms = null): void
    {
        if ($cms === null) {
            try {
                $cms = \App\Services\CmsPageService::merged('pendaftaran');
            } catch (\Throwable) {
                $cms = [];
            }
        }

        $keys = self::cardKeysFromCms($cms);
        if ($keys === []) {
            $keys = self::LEGACY_CARD_KEYS;
        }

        abort_unless(in_array($cardKey, $keys, true), 404);
    }

    /**
     * @return array<string, mixed>
     */
    private static function jemaatDefaults(): array
    {
        return [
            'title' => 'Pendaftaran Jemaat',
            'subtitle' => 'Daftarkan diri Anda sebagai jemaat dengan melengkapi data berikut.',
            'leaf_label' => 'Jemaat',
            'form_header' => [
                'icon' => 'fa-solid fa-pen-to-square',
                'title' => 'Formulir data jemaat',
                'subtitle' => '',
            ],
            'consent' => [
                'text' => 'Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.',
                'submit_label' => 'Kirim pendaftaran',
            ],
            'info_panel' => [
                'icon' => 'fa-solid fa-route',
                'title' => 'Alur pendaftaran',
                'subtitle' => 'Ikuti langkah berikut',
                'tips_heading' => 'Tips',
                'tips_heading_icon' => 'fa-solid fa-lightbulb',
                'steps' => [
                    'Isi identitas & data kelahiran sesuai dokumen resmi.',
                    'Tim sekretariat meninjau dalam beberapa hari kerja.',
                    'Anda dihubungi untuk konfirmasi atau kelengkapan berkas.',
                ],
                'tips' => [
                    ['icon' => 'fa-solid fa-id-card', 'text' => 'Siapkan nama lengkap dan tempat lahir seperti di KTP atau akta.'],
                    ['icon' => 'fa-solid fa-phone', 'text' => 'Nomor telepon aktif agar mudah dihubungi sekretariat.'],
                    ['icon' => 'fa-solid fa-shield-halved', 'text' => 'Data hanya digunakan untuk keperluan pelayanan jemaat.'],
                ],
            ],
            'sections' => [
                [
                    'key' => 'identitas',
                    'icon' => 'fa-solid fa-user',
                    'title' => 'Identitas',
                    'subtitle' => 'Nama seperti tercantum di dokumen resmi',
                    'groups' => [
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('full_name', 'Nama lengkap', 'fa-solid fa-signature', required: true, placeholder: 'Contoh: Andreas Wanimbo'),
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'kelahiran',
                    'icon' => 'fa-solid fa-cake-candles',
                    'title' => 'Kelahiran',
                    'subtitle' => 'Tempat, tanggal, dan jenis kelamin',
                    'groups' => [
                        [
                            'layout' => 'grid',
                            'fields' => [
                                self::field('birth_place', 'Tempat lahir', 'fa-solid fa-location-dot', placeholder: 'Kota / kabupaten'),
                                self::field('birth_date', 'Tanggal lahir', 'fa-solid fa-calendar-day', type: 'date'),
                            ],
                        ],
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('gender', 'Jenis kelamin', 'fa-solid fa-venus-mars', type: 'select', selectOptions: self::genderOptions()),
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'domisili',
                    'icon' => 'fa-solid fa-house-chimney',
                    'title' => 'Domisili',
                    'subtitle' => 'Alamat tempat tinggal saat ini',
                    'groups' => [
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('address', 'Alamat lengkap', 'fa-solid fa-map-location-dot', type: 'textarea', rows: 3, placeholder: 'Jl., RT/RW, kelurahan, kota'),
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'kontak',
                    'icon' => 'fa-solid fa-address-book',
                    'title' => 'Kontak',
                    'subtitle' => 'Agar tim dapat menghubungi Anda',
                    'groups' => [
                        [
                            'layout' => 'grid',
                            'fields' => [
                                self::field('phone', 'Telepon / WhatsApp', 'fa-solid fa-phone', type: 'tel', placeholder: '08xxxxxxxxxx'),
                                self::field('email', 'Email', 'fa-solid fa-envelope', type: 'email', placeholder: 'nama@email.com'),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function baptisDefaults(): array
    {
        return [
            'title' => 'Pendaftaran baptisan air',
            'subtitle' => 'Isi data calon baptisan. Pendamping rohani akan menghubungi untuk jadwal wawancara.',
            'leaf_label' => 'Baptisan',
            'form_header' => [
                'icon' => 'fa-solid fa-water',
                'title' => 'Formulir baptisan air',
                'subtitle' => '',
            ],
            'consent' => [
                'text' => 'Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.',
                'submit_label' => 'Kirim pendaftaran',
            ],
            'info_panel' => [
                'icon' => 'fa-solid fa-route',
                'title' => 'Alur pendaftaran',
                'subtitle' => 'Ikuti langkah berikut',
                'tips_heading' => 'Tips',
                'tips_heading_icon' => 'fa-solid fa-lightbulb',
                'steps' => [
                    'Isi data calon baptisan dengan lengkap dan jujur.',
                    'Pendamping rohani meninjau dan menjadwalkan wawancara.',
                    'Konfirmasi jadwal baptisan air di gereja.',
                ],
                'tips' => [
                    ['icon' => 'fa-solid fa-user', 'text' => 'Cantumkan nama lengkap calon baptisan seperti di dokumen resmi.'],
                    ['icon' => 'fa-solid fa-calendar-day', 'text' => 'Tanggal rencana dapat disesuaikan setelah wawancara.'],
                    ['icon' => 'fa-solid fa-shield-halved', 'text' => 'Data hanya untuk keperluan pelayanan dan pendampingan rohani.'],
                ],
            ],
            'sections' => [
                [
                    'key' => 'identitas',
                    'icon' => 'fa-solid fa-user',
                    'title' => 'Data calon baptisan',
                    'subtitle' => 'Identitas calon baptisan',
                    'groups' => [
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('full_name', 'Nama lengkap', 'fa-solid fa-signature', required: true, placeholder: 'Nama sesuai dokumen'),
                            ],
                        ],
                        [
                            'layout' => 'grid',
                            'fields' => [
                                self::field('age', 'Usia', 'fa-solid fa-hashtag', type: 'number', placeholder: 'Tahun'),
                                self::field('gender', 'Jenis kelamin', 'fa-solid fa-venus-mars', type: 'select', selectOptions: self::genderOptions()),
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'jadwal',
                    'icon' => 'fa-solid fa-calendar-day',
                    'title' => 'Jadwal',
                    'subtitle' => 'Rencana tanggal baptisan',
                    'groups' => [
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('baptism_date', 'Tanggal baptis (rencana)', 'fa-solid fa-calendar-day', type: 'date'),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function nikahDefaults(): array
    {
        return [
            'title' => 'Pendaftaran pernikahan gerejawi',
            'subtitle' => 'Data mempelai akan diverifikasi sebelum penjadwalan konseling pranikah.',
            'leaf_label' => 'Pernikahan',
            'form_header' => [
                'icon' => 'fa-solid fa-ring',
                'title' => 'Formulir pernikahan gerejawi',
                'subtitle' => '',
            ],
            'consent' => [
                'text' => 'Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.',
                'submit_label' => 'Kirim pendaftaran',
            ],
            'info_panel' => [
                'icon' => 'fa-solid fa-route',
                'title' => 'Alur pendaftaran',
                'subtitle' => 'Ikuti langkah berikut',
                'tips_heading' => 'Tips',
                'tips_heading_icon' => 'fa-solid fa-lightbulb',
                'steps' => [
                    'Isi data kedua mempelai dan kontak yang bisa dihubungi.',
                    'Tim gereja memverifikasi data dan menjadwalkan konseling pranikah.',
                    'Konfirmasi jadwal pernikahan gerejawi setelah persyaratan terpenuhi.',
                ],
                'tips' => [
                    ['icon' => 'fa-solid fa-ring', 'text' => 'Nama mempelai sesuai KTP atau akta kelahiran.'],
                    ['icon' => 'fa-solid fa-phone', 'text' => 'Nomor telepon aktif untuk koordinasi sekretariat.'],
                    ['icon' => 'fa-solid fa-shield-halved', 'text' => 'Data dijaga untuk keperluan pelayanan pernikahan gerejawi.'],
                ],
            ],
            'sections' => [
                [
                    'key' => 'mempelai',
                    'icon' => 'fa-solid fa-heart',
                    'title' => 'Data mempelai',
                    'subtitle' => 'Nama lengkap kedua mempelai',
                    'groups' => [
                        [
                            'layout' => 'stack',
                            'fields' => [
                                self::field('groom_name', 'Nama mempelai pria', 'fa-solid fa-user', required: true, placeholder: 'Nama lengkap'),
                                self::field('bride_name', 'Nama mempelai wanita', 'fa-solid fa-user', required: true, placeholder: 'Nama lengkap'),
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'jadwal',
                    'icon' => 'fa-solid fa-calendar-days',
                    'title' => 'Jadwal & kontak',
                    'subtitle' => 'Rencana tanggal dan nomor telepon',
                    'groups' => [
                        [
                            'layout' => 'grid',
                            'fields' => [
                                self::field('wedding_date', 'Tanggal rencana', 'fa-solid fa-calendar-day', type: 'date'),
                                self::field('phone', 'Telepon / WhatsApp', 'fa-solid fa-phone', type: 'tel', placeholder: '08xxxxxxxxxx'),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  list<array{value: string, label: string}>|null  $selectOptions
     * @return array<string, mixed>
     */
    private static function field(
        string $name,
        string $label,
        string $icon,
        string $type = 'text',
        bool $required = false,
        string $placeholder = '',
        int $rows = 3,
        ?array $selectOptions = null,
    ): array {
        $field = [
            'name' => $name,
            'label' => $label,
            'icon' => $icon,
            'type' => $type,
            'width' => 'full',
            'required' => $required,
            'placeholder' => $placeholder,
        ];

        if ($type === 'textarea') {
            $field['rows'] = $rows;
        }

        if ($type === 'select' && $selectOptions !== null) {
            $field['select_options'] = $selectOptions;
        }

        return $field;
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private static function genderOptions(): array
    {
        return [
            ['value' => '', 'label' => '— Pilih —'],
            ['value' => 'Laki-laki', 'label' => 'Laki-laki'],
            ['value' => 'Perempuan', 'label' => 'Perempuan'],
        ];
    }
}
