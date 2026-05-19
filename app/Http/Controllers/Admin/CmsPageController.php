<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\CmsPageService;
use App\Services\WorshipSchedulePartitionService;
use App\Support\AdminRepeatableFields;
use App\Support\CmsContentBlocks;
use App\Support\CmsPageIconDefaults;
use App\Support\CmsPublicPageDefaults;
use App\Support\PendaftaranCardCms;
use App\Support\PublicCmsUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    /** @var list<string> */
    private const NAV_ROUTE_NAMES = [
        'home',
        'profil',
        'struktur',
        'jadwal',
        'pendaftaran.index',
        'informasi-kegiatan',
        'kontak',
        'galeri',
    ];

    public function edit(string $pageKey): View
    {
        $this->assertPageKey($pageKey);
        $data = CmsPageService::merged($pageKey);

        return view('admin.cms.edit', compact('pageKey', 'data'));
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        $this->assertPageKey($pageKey);

        $validated = match ($pageKey) {
            'beranda' => $this->validatedBeranda($request),
            'profil' => $this->validatedProfil($request, 'profil'),
            'struktur' => $this->validatedProfil($request, 'struktur'),
            'jadwal' => $this->validatedJadwal($request),
            'pendaftaran' => $this->validatedPendaftaran($request),
            'informasi_kegiatan' => $request->validate(array_merge([
                'breadcrumb_home' => ['required', 'string', 'max:120'],
                'breadcrumb_current' => ['required', 'string', 'max:120'],
                'h1' => ['required', 'string', 'max:255'],
                'empty_message' => ['nullable', 'string', 'max:500'],
                'read_more_label' => ['required', 'string', 'max:120'],
                'show_page_h1' => ['required', 'string', 'max:255'],
            ], CmsPageIconDefaults::validationRules('informasi_kegiatan'))),
            'kontak' => $this->validatedKontak($request),
            'galeri' => $request->validate(array_merge([
                'breadcrumb_home' => ['required', 'string', 'max:120'],
                'breadcrumb_current' => ['required', 'string', 'max:120'],
                'h1' => ['required', 'string', 'max:255'],
                'intro' => ['nullable', 'string', 'max:2000'],
                'empty_message' => ['nullable', 'string', 'max:500'],
                'lightbox_title' => ['required', 'string', 'max:255'],
                'lightbox_close_label' => ['required', 'string', 'max:80'],
                'lightbox_prev_label' => ['required', 'string', 'max:80'],
                'lightbox_next_label' => ['required', 'string', 'max:80'],
            ], CmsPageIconDefaults::validationRules('galeri'))),
            default => [],
        };

        if ($pageKey === 'beranda') {
            if (! array_key_exists('site_logo_url', $validated)) {
                $validated['site_logo_url'] = '';
            }
        }

        CmsPageService::save($pageKey, $validated);

        if ($pageKey === 'beranda') {
            $this->syncBerandaHeroVisionToSiteSettings($validated);
        }

        return redirect()->route('dashboard.setting.cms.edit', $pageKey)->with('status', 'Perubahan disimpan.');
    }

    public function editPendaftaranCard(string $cardKey): View
    {
        $data = CmsPageService::merged('pendaftaran');
        PendaftaranCardCms::assertCardKey($cardKey, $data);
        $card = PendaftaranCardCms::config($cardKey, $data);
        abort_if($card === null, 404);
        $detail = PendaftaranCardCms::detailFromCms($data, $cardKey);

        return view('admin.cms.pendaftaran-card-edit', [
            'cardKey' => $cardKey,
            'card' => $card,
            'data' => $data,
            'detail' => $detail,
        ]);
    }

    public function updatePendaftaranCard(Request $request, string $cardKey): RedirectResponse
    {
        $existing = CmsPageService::merged('pendaftaran');
        PendaftaranCardCms::assertCardKey($cardKey, $existing);

        $payload = PendaftaranCardCms::pruneDetailPayloadForValidation($request->all());
        $request->replace($payload);

        $validated = $request->validate(
            PendaftaranCardCms::validationRulesForCard($cardKey),
            [],
            PendaftaranCardCms::validationAttributesForCard()
        );

        $cardDetails = is_array($existing['card_details'] ?? null) ? $existing['card_details'] : [];
        $cardDetails[$cardKey] = PendaftaranCardCms::normalizeValidatedDetail($cardKey, $validated);
        $existing['card_details'] = $cardDetails;

        $pageIcons = is_array($existing['page_icons'] ?? null) ? $existing['page_icons'] : [];
        if (isset($validated['page_icons']) && is_array($validated['page_icons'])) {
            $pageIcons = array_merge($pageIcons, $validated['page_icons']);
        }
        $existing['page_icons'] = $pageIcons;

        CmsPageService::save('pendaftaran', $existing);

        return redirect()
            ->route('dashboard.setting.pendaftaran.kartu.edit', $cardKey)
            ->with('status', 'Detail kartu disimpan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedProfil(Request $request, string $pageKey): array
    {
        $data = $request->validate(array_merge([
            'breadcrumb_home' => ['required', 'string', 'max:120'],
            'breadcrumb_current' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'blocks' => ['required', 'array', 'min:1', 'max:80'],
            'blocks.*.type' => ['required', Rule::in(CmsContentBlocks::TYPES)],
            'blocks.*.text' => ['nullable', 'string', 'max:10000'],
            'blocks.*.items' => ['nullable', 'array', 'max:50'],
            'blocks.*.items.*' => ['nullable', 'string', 'max:2000'],
        ], CmsPageIconDefaults::validationRules($pageKey)));

        $blocks = CmsContentBlocks::normalize(array_values($data['blocks']));
        if ($blocks === []) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'blocks' => ['Tambahkan minimal satu blok berisi teks.'],
            ]);
        }

        $data['blocks'] = $blocks;
        $data['body'] = CmsContentBlocks::toHtml($blocks);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedBeranda(Request $request): array
    {
        AdminRepeatableFields::replaceInRequest($request, [
            'hero_buttons' => AdminRepeatableFields::pruneTemplateRows($request->input('hero_buttons', []), ['label', 'url']),
            'sidebar_cards' => AdminRepeatableFields::pruneTemplateRows($request->input('sidebar_cards', []), ['title']),
            'nav' => AdminRepeatableFields::pruneTemplateRows($request->input('nav', []), ['label']),
            'footer_quick_links' => AdminRepeatableFields::pruneTemplateRows($request->input('footer_quick_links', []), ['label']),
            'footer_social_links' => AdminRepeatableFields::pruneTemplateRows($request->input('footer_social_links', []), ['label', 'url']),
        ]);

        $rules = [
            'church_name_line1' => ['required', 'string', 'max:255'],
            'church_name_line2' => ['required', 'string', 'max:255'],
            'site_logo_url' => ['nullable', 'string', 'max:2000'],
            'site_logo_file' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/svg+xml'],
            'site_logo_delete' => ['nullable', 'in:1'],
            'site_logo_url_previous' => ['nullable', 'string', 'max:2000'],
            'header_tagline' => ['required', 'string', 'max:500'],
            'hero_script_top' => ['nullable', 'string', 'max:255'],
            'hero_title_gold' => ['nullable', 'string', 'max:255'],
            'hero_title_white' => ['nullable', 'string', 'max:255'],
            'hero_script_bottom' => ['nullable', 'string', 'max:255'],
            'hero_image_url' => ['nullable', 'string', 'max:2000'],
            'hero_image_file' => ['nullable', 'image', 'max:5120'],
            'hero_image_delete' => ['nullable', 'in:1'],
            'hero_image_url_previous' => ['nullable', 'string', 'max:2000'],
            'hero_buttons' => ['required', 'array', 'min:1', 'max:12'],
            'hero_buttons.*.key' => ['required', 'string', 'max:50'],
            'hero_buttons.*.label' => ['required', 'string', 'max:200'],
            'hero_buttons.*.url' => ['required', 'string', 'max:500'],
            'hero_buttons.*.style' => ['required', Rule::in(['primary', 'secondary', 'link'])],
            'hero_buttons.*.icon' => ['nullable', 'string', 'max:120'],
            'vision_icon' => ['nullable', 'string', 'max:120'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_blocks' => ['required', 'array', 'min:1', 'max:80'],
            'vision_blocks.*.type' => ['required', Rule::in(CmsContentBlocks::TYPES)],
            'vision_blocks.*.text' => ['nullable', 'string', 'max:10000'],
            'vision_blocks.*.items' => ['nullable', 'array', 'max:50'],
            'vision_blocks.*.items.*' => ['nullable', 'string', 'max:2000'],
            'sidebar_section_title' => ['nullable', 'string', 'max:120'],
            'sidebar_cards' => ['required', 'array', 'min:1', 'max:12'],
            'sidebar_cards.*.key' => ['required', 'string', 'max:50'],
            'sidebar_cards.*.icon' => ['nullable', 'string', 'max:120'],
            'sidebar_cards.*.title' => ['required', 'string', 'max:200'],
            'sidebar_cards.*.subtitle' => ['nullable', 'string', 'max:300'],
            'sidebar_cards.*.url' => ['required', 'string', 'max:500'],
            'nav' => ['required', 'array', 'min:1', 'max:20'],
            'nav.*.route' => ['required', 'string', 'max:500'],
            'nav.*.label' => ['required', 'string', 'max:120'],
            'nav.*.icon' => ['nullable', 'string', 'max:120'],
            'footer_quick_links' => ['required', 'array', 'min:1', 'max:12'],
            'footer_quick_links.*.route' => ['required', 'string', 'max:500'],
            'footer_quick_links.*.label' => ['required', 'string', 'max:120'],
            'footer_quick_links.*.icon' => ['nullable', 'string', 'max:120'],
            'footer_social_links' => ['nullable', 'array', 'max:12'],
            'footer_social_links.*.label' => ['nullable', 'string', 'max:120'],
            'footer_social_links.*.url' => ['nullable', 'string', 'max:500'],
            'footer_social_links.*.icon' => ['nullable', 'string', 'max:120'],
            'footer_headings.contact' => ['required', 'string', 'max:120'],
            'footer_headings.address' => ['required', 'string', 'max:120'],
            'footer_headings.social' => ['required', 'string', 'max:120'],
            'footer_copyright_text' => ['required', 'string', 'max:255'],
        ];

        $data = $request->validate(array_merge($rules, CmsPageIconDefaults::validationRules('beranda')));

        $deleteHero = (($data['hero_image_delete'] ?? '') === '1');
        $previousHero = trim((string) ($data['hero_image_url_previous'] ?? ''));
        unset($data['hero_image_delete'], $data['hero_image_url_previous']);

        if ($request->hasFile('hero_image_file')) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            $path = $request->file('hero_image_file')->store('cms/beranda', 'public');
            $data['hero_image_url'] = Storage::url($path);
        } elseif ($deleteHero) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            $data['hero_image_url'] = '';
        } else {
            $newUrl = trim((string) ($data['hero_image_url'] ?? ''));
            if ($newUrl === '' && $previousHero !== '') {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            } elseif (
                $previousHero !== ''
                && $newUrl !== $previousHero
                && PublicCmsUrl::publicStorageRelativePath($previousHero) !== null
            ) {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            }
        }

        unset($data['hero_image_file']);

        $deleteLogo = (($data['site_logo_delete'] ?? '') === '1');
        $previousLogo = trim((string) ($data['site_logo_url_previous'] ?? ''));
        unset($data['site_logo_delete'], $data['site_logo_url_previous']);

        if ($request->hasFile('site_logo_file')) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousLogo);
            $path = $request->file('site_logo_file')->store('cms/site', 'public');
            $data['site_logo_url'] = Storage::url($path);
        } elseif ($deleteLogo) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousLogo);
            $data['site_logo_url'] = '';
        } else {
            $newLogoUrl = trim((string) ($data['site_logo_url'] ?? ''));
            if ($newLogoUrl === '' && $previousLogo !== '') {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousLogo);
            } elseif (
                $previousLogo !== ''
                && $newLogoUrl !== $previousLogo
                && PublicCmsUrl::publicStorageRelativePath($previousLogo) !== null
            ) {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousLogo);
            }
        }

        unset($data['site_logo_file']);

        if (isset($data['nav']) && is_array($data['nav'])) {
            foreach ($data['nav'] as $i => $item) {
                if (is_array($item)) {
                    $data['nav'][$i]['route'] = PublicCmsUrl::normalizeNavPathForStorage($item['route'] ?? '');
                }
            }
        }
        if (isset($data['footer_quick_links']) && is_array($data['footer_quick_links'])) {
            foreach ($data['footer_quick_links'] as $i => $item) {
                if (is_array($item)) {
                    $data['footer_quick_links'][$i]['route'] = PublicCmsUrl::normalizeNavPathForStorage($item['route'] ?? '');
                }
            }
        }
        if (isset($data['footer_social_links']) && is_array($data['footer_social_links'])) {
            $data['footer_social_links'] = array_values(array_filter(
                $data['footer_social_links'],
                static fn ($item) => is_array($item) && trim((string) ($item['url'] ?? '')) !== ''
            ));
        }

        $visionBlocks = CmsContentBlocks::normalize(array_values($data['vision_blocks']));
        if ($visionBlocks === []) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'vision_blocks' => ['Tambahkan minimal satu blok berisi teks.'],
            ]);
        }
        $data['vision_blocks'] = $visionBlocks;
        $data['vision_body'] = CmsContentBlocks::toHtml($visionBlocks);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPendaftaran(Request $request): array
    {
        $cards = $request->input('cards', []);
        if (is_array($cards)) {
            foreach ($cards as $i => $card) {
                if (! is_array($card)) {
                    continue;
                }
                $cards[$i]['url'] = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            }
            $request->merge(['cards' => $cards]);
        }

        AdminRepeatableFields::replaceInRequest($request, [
            'cards' => AdminRepeatableFields::pruneTemplateRows($request->input('cards', []), ['title', 'url']),
        ]);

        $validated = $request->validate(array_merge([
            'breadcrumb_home' => ['required', 'string', 'max:120'],
            'breadcrumb_current' => ['required', 'string', 'max:120'],
            'h1' => ['required', 'string', 'max:255'],
            'cards' => ['required', 'array', 'min:1', 'max:12'],
            'cards.*.key' => ['required', 'string', 'max:50'],
            'cards.*.icon' => ['nullable', 'string', 'max:120'],
            'cards.*.title' => ['required', 'string', 'max:200'],
            'cards.*.description' => ['nullable', 'string', 'max:2000'],
            'cards.*.cta_label' => ['required', 'string', 'max:120'],
            'cards.*.url' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'cards.*.arrow_icon' => ['nullable', 'string', 'max:120'],
        ], CmsPageIconDefaults::validationRules('pendaftaran')));

        $seenSlugs = [];
        foreach ($validated['cards'] as $i => $card) {
            $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            if (! PendaftaranCardCms::isValidSlug($slug)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "cards.{$i}.url" => 'Slug hanya huruf kecil, angka, dan tanda hubung (contoh: sidik-jari).',
                ]);
            }
            if (in_array($slug, $seenSlugs, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "cards.{$i}.url" => 'Slug URL tidak boleh duplikat.',
                ]);
            }
            $seenSlugs[] = $slug;
            $validated['cards'][$i]['url'] = PublicCmsUrl::normalizePendaftaranCardUrlForStorage($slug);
        }

        $validated['intro'] = '';

        $existing = CmsPageService::merged('pendaftaran');
        $existingCards = is_array($existing['cards'] ?? null) ? $existing['cards'] : [];
        $declaredRowCount = (int) $request->input('cards_row_count', 0);

        $validated['cards'] = PendaftaranCardCms::finalizeCardsForSave(
            $validated['cards'],
            $existingCards,
            $declaredRowCount
        );

        $existingDetails = is_array($existing['card_details'] ?? null)
            ? $existing['card_details']
            : PendaftaranCardCms::defaultCardDetails();

        $payload = array_replace_recursive($existing, array_diff_key($validated, ['cards' => true, 'card_details' => true]));
        $payload['cards'] = array_values($validated['cards']);
        $payload['card_details'] = PendaftaranCardCms::syncCardDetails($validated['cards'], $existingDetails);
        $payload['page_icons'] = array_merge(
            $existing['page_icons'] ?? [],
            $validated['page_icons'] ?? []
        );

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedKontak(Request $request): array
    {
        AdminRepeatableFields::replaceInRequest($request, [
            'form_fields' => AdminRepeatableFields::pruneTemplateRows($request->input('form_fields', []), ['name']),
        ]);

        $data = $request->validate(array_merge([
            'breadcrumb_home' => ['required', 'string', 'max:120'],
            'breadcrumb_current' => ['required', 'string', 'max:120'],
            'h1' => ['required', 'string', 'max:255'],
            'form_heading' => ['required', 'string', 'max:120'],
            'form_hint' => ['nullable', 'string', 'max:500'],
            'submit_label' => ['required', 'string', 'max:120'],
            'success_message' => ['required', 'string', 'max:500'],
            'form_fields' => ['required', 'array', 'min:1', 'max:30'],
            'form_fields.*.name' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z0-9_]+$/'],
            'form_fields.*.type' => ['required', Rule::in(['text', 'email', 'number', 'textarea'])],
            'form_fields.*.width' => ['required', Rule::in(['panjang', 'setengah'])],
            'form_fields.*.label' => ['required', 'string', 'max:200'],
            'form_fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'form_fields.*.required' => ['nullable', 'in:0,1'],
        ], CmsPageIconDefaults::validationRules('kontak')));

        $existingFields = collect(CmsPageService::merged('kontak')['form_fields'] ?? [])
            ->keyBy(fn (array $field): string => (string) ($field['name'] ?? ''));

        foreach ($data['form_fields'] as $i => $row) {
            $data['form_fields'][$i]['required'] = (($row['required'] ?? '0') === '1' || $row['required'] === 1 || $row['required'] === true);
            $name = (string) ($row['name'] ?? '');
            $stored = $existingFields->get($name);
            $stored = is_array($stored) ? $stored : [];
            $type = (string) ($row['type'] ?? 'text');
            $width = (string) ($row['width'] ?? '');
            $data['form_fields'][$i]['width'] = in_array($width, ['panjang', 'setengah'], true)
                ? $width
                : ($type === 'textarea' ? 'panjang' : 'setengah');
            $data['form_fields'][$i]['max'] = (int) ($stored['max'] ?? ($type === 'textarea' ? 5000 : 255));
            if ($type === 'textarea') {
                $data['form_fields'][$i]['rows'] = (int) ($stored['rows'] ?? 5);
            } else {
                unset($data['form_fields'][$i]['rows']);
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function syncBerandaHeroVisionToSiteSettings(array $validated): void
    {
        $map = [
            'church_name_line1' => 'church_name_line1',
            'church_name_line2' => 'church_name_line2',
            'site_logo_url' => 'site_logo_url',
            'hero_image_url' => 'hero_image_url',
            'hero_script_top' => 'hero_script_top',
            'hero_title_gold' => 'hero_title_gold',
            'hero_title_white' => 'hero_title_white',
            'hero_script_bottom' => 'hero_script_bottom',
            'vision_title' => 'vision_title',
            'vision_body' => 'vision_body',
        ];
        foreach ($map as $cmsKey => $siteKey) {
            if (! array_key_exists($cmsKey, $validated)) {
                continue;
            }
            $v = $validated[$cmsKey];
            if ($v === null) {
                $v = '';
            }
            SiteSetting::put($siteKey, is_string($v) ? $v : (string) $v);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedJadwal(Request $request): array
    {
        $iconRules = [];
        foreach (['breadcrumb_home', 'breadcrumb_sep', 'breadcrumb_current', 'h1', 'section_upcoming', 'section_completed'] as $iconKey) {
            $iconRules['page_icons.'.$iconKey] = ['nullable', 'string', 'max:120'];
        }

        $validated = $request->validate(array_merge([
            'breadcrumb_home' => ['required', 'string', 'max:120'],
            'breadcrumb_current' => ['required', 'string', 'max:120'],
            'h1' => ['required', 'string', 'max:255'],
            'section_upcoming_title' => ['required', 'string', 'max:120'],
            'section_completed_title' => ['required', 'string', 'max:120'],
            'table_headers_upcoming' => ['required', 'array', 'min:3', 'max:12'],
            'table_headers_upcoming.*' => ['required', 'string', 'max:120'],
            'table_column_icons_upcoming' => ['nullable', 'array', 'max:12'],
            'table_column_icons_upcoming.*' => ['nullable', 'string', 'max:120'],
        ], $iconRules));

        $existing = CmsPageService::merged('jadwal');
        $defaults = CmsPublicPageDefaults::defaultsFor('jadwal');
        $validated['intro'] = $existing['intro'] ?? $defaults['intro'] ?? '';
        $validated['empty_message'] = $existing['empty_message'] ?? $defaults['empty_message'] ?? '';
        $validated['show_next_label'] = $existing['show_next_label'] ?? $defaults['show_next_label'] ?? 'Selanjutnya';
        $validated['page_icons'] = array_merge(
            $existing['page_icons'] ?? [],
            $validated['page_icons'] ?? []
        );

        $validated['table_headers_upcoming'] = WorshipSchedulePartitionService::ensureAksiLast(
            WorshipSchedulePartitionService::ensureWaktuFirst(array_values($validated['table_headers_upcoming']))
        );

        if (count(WorshipSchedulePartitionService::middleHeaders($validated['table_headers_upcoming'])) < 1) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'table_headers_upcoming' => 'Tambahkan minimal satu kolom di antara kolom pertama dan kolom terakhir.',
            ]);
        }

        $normalized = WorshipSchedulePartitionService::normalizeJadwalCms([
            'table_headers_upcoming' => $validated['table_headers_upcoming'],
            'table_column_icons_upcoming' => $validated['table_column_icons_upcoming'] ?? [],
        ]);

        $validated['table_headers_upcoming'] = $normalized['table_headers_upcoming'];
        $validated['table_headers_completed'] = $normalized['table_headers_completed'];
        $validated['table_column_icons_upcoming'] = $normalized['table_column_icons_upcoming'];
        $validated['table_column_icons_completed'] = $normalized['table_column_icons_completed'];

        return $validated;
    }

    private function assertPageKey(string $pageKey): void
    {
        abort_unless(in_array($pageKey, CmsPublicPageDefaults::PAGE_KEYS, true), 404);
    }
}
