@php
    $berandaSectionTabs = [
        'Header',
        'Gambar hero',
        'Teks hero',
        'Tombol hero',
        'Visi',
        'Jelajahi',
        'Menu navigasi',
        'Footer',
    ];
@endphp

<div id="beranda-cms-section-tabs" class="space-y-5">
    <div
        role="tablist"
        aria-label="Pilih kotak yang akan disunting"
        class="flex flex-wrap gap-2.5"
    >
        @foreach ($berandaSectionTabs as $i => $tab)
            <button
                type="button"
                role="tab"
                id="beranda-section-tab-{{ $i }}"
                class="public-card-hover beranda-section-tab group inline-flex min-h-[2.75rem] max-w-full min-w-0 shrink-0 items-center gap-2 rounded-xl border border-white/10 bg-church-surface/70 px-3 py-2 text-left text-sm font-medium text-slate-200 outline-none transition focus-visible:ring-2 focus-visible:ring-church-gold/35 focus-visible:ring-offset-2 focus-visible:ring-offset-church-card aria-selected:border-church-gold/45 aria-selected:bg-church-gold/10 aria-selected:text-church-gold aria-selected:shadow-inner aria-selected:ring-1 aria-selected:ring-church-gold/25 sm:max-w-[14rem]"
                aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                aria-controls="beranda-section-panel-{{ $i }}"
                tabindex="{{ $i === 0 ? '0' : '-1' }}"
                data-step="{{ $i }}"
            >
                <span class="min-w-0 break-words leading-snug">{{ $tab }}</span>
            </button>
        @endforeach
    </div>

    <div class="beranda-section-panels space-y-10">
        <div
            class="beranda-section-panel space-y-10"
            id="beranda-section-panel-0"
            data-step="0"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-0"
            aria-hidden="false"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Header</x-admin-field-label>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-admin-field-label class="text-sm text-slate-300">Baris nama 1</x-admin-field-label>
                        <input name="church_name_line1" value="{{ old('church_name_line1', $data['church_name_line1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                    </div>
                    <div>
                        <x-admin-field-label class="text-sm text-slate-300">Baris nama 2</x-admin-field-label>
                        <input name="church_name_line2" value="{{ old('church_name_line2', $data['church_name_line2'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-sm text-slate-300">Tagline situs</x-admin-field-label>
                        <input name="header_tagline" value="{{ old('header_tagline', $data['header_tagline'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                    </div>
                    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'layout_header_tagline', 'data' => $data, 'label' => 'Ikon tagline'])
                </div>

                @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'layout_skip_link', 'data' => $data, 'label' => 'Ikon'])
                <div class="border-t border-white/10 pt-4">
                    @include('admin.partials.site-logo-upload', [
                        'previewUrl' => old('site_logo_url', $data['site_logo_url'] ?? ''),
                        'persistedLogoUrl' => $data['site_logo_url'] ?? '',
                        'showUrlField' => true,
                    ])
                </div>
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-1"
            data-step="1"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-1"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Gambar hero</x-admin-field-label>
                @include('admin.partials.hero-image-upload', [
                    'previewUrl' => old('hero_image_url', $data['hero_image_url'] ?? ''),
                    'persistedHeroUrl' => $data['hero_image_url'] ?? '',
                    'showUrlField' => true,
                ])
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-2"
            data-step="2"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-2"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Teks hero</x-admin-field-label>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><x-admin-field-label class="text-sm text-slate-300">Script atas</x-admin-field-label><input name="hero_script_top" value="{{ old('hero_script_top', $data['hero_script_top'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg"></div>
                    <div><x-admin-field-label class="text-sm text-slate-300">Script bawah</x-admin-field-label><input name="hero_script_bottom" value="{{ old('hero_script_bottom', $data['hero_script_bottom'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg"></div>
                </div>
                <div><x-admin-field-label class="text-sm text-slate-300">Judul emas</x-admin-field-label><input name="hero_title_gold" value="{{ old('hero_title_gold', $data['hero_title_gold'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg"></div>
                <div><x-admin-field-label class="text-sm text-slate-300">Judul putih</x-admin-field-label><input name="hero_title_white" value="{{ old('hero_title_white', $data['hero_title_white'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg"></div>
                @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'hero_ornament', 'data' => $data, 'label' => 'Ikon ornamen'])
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-3"
            data-step="3"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-3"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Tombol hero</x-admin-field-label>
                @php
                    $__heroBtns = $data['hero_buttons'] ?? [];
                    if (! is_array($__heroBtns) || count($__heroBtns) === 0) {
                        $__heroBtns = [
                            ['key' => 'b0', 'label' => '', 'url' => '', 'style' => 'primary', 'icon' => ''],
                        ];
                    }
                @endphp
                <div class="flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        id="cms-hero-add-button"
                        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
                    >
                        Tambah tombol
                    </button>
                </div>
                <div id="cms-hero-buttons-wrap" class="space-y-3 sm:space-y-4">
                    @foreach ($__heroBtns as $i => $btn)
                        <div class="public-card-hover cms-hero-button-row rounded-xl border border-white/10 bg-church-surface/25 p-4 sm:p-5">
                            <div class="mb-3 flex items-start justify-between gap-3 sm:mb-4 sm:items-center">
                                <p class="text-xs font-medium text-slate-500" data-cms-hero-heading>Tombol {{ $i + 1 }}</p>
                                <button type="button" data-cms-hero-remove class="shrink-0 text-xs text-red-400 underline-offset-2  hover:underline">Hapus</button>
                            </div>
                            <input type="hidden" name="hero_buttons[{{ $i }}][key]" value="{{ old('hero_buttons.'.$i.'.key', $btn['key'] ?? 'b'.$i) }}">
                            {{--
                                Mobile: satu kolom (Label → Ikon → Gaya → URL)
                                sm–md: 6 kolom — baris 1 Label|Ikon; baris 2 Gaya|URL
                                lg+: 12 kolom — satu baris penuh
                            --}}
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-6 sm:items-end sm:gap-x-4 sm:gap-y-3 lg:grid-cols-12 lg:gap-3">
                                <div class="min-w-0 sm:col-span-3 lg:col-span-2">
                                    <x-admin-field-label class="text-xs text-slate-400">Label</x-admin-field-label>
                                    <input name="hero_buttons[{{ $i }}][label]" value="{{ old('hero_buttons.'.$i.'.label', $btn['label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                                </div>
                                <div class="min-w-0 sm:col-span-3 lg:col-span-4 xl:col-span-3">
                                    @php
                                        $__hbStyle = old('hero_buttons.'.$i.'.style', $btn['style'] ?? 'primary');
                                        $__hbIconDefault = \App\Support\CmsIcon::heroButtonIconDefault(is_string($__hbStyle) ? $__hbStyle : 'primary');
                                    @endphp
                                    @include('admin.partials.fa-icon-input', [
                                        'name' => 'hero_buttons['.$i.'][icon]',
                                        'value' => old('hero_buttons.'.$i.'.icon', $btn['icon'] ?? ''),
                                        'label' => 'Ikon',
                                        'previewDefault' => $__hbIconDefault,
                                        'variant' => 'adjacent',
                                        'hint' => '',
                                    ])
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-2">
                                    <x-admin-field-label class="text-xs text-slate-400">Gaya</x-admin-field-label>
                                    <select name="hero_buttons[{{ $i }}][style]" class="hero-button-style-select mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                                        @foreach (['primary','secondary','link'] as $st)
                                            <option value="{{ $st }}" @selected(old('hero_buttons.'.$i.'.style', $btn['style'] ?? '') === $st)>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="min-w-0 sm:col-span-4 lg:col-span-4 xl:col-span-5">
                                    <x-admin-field-label class="text-xs text-slate-400">URL jalur atau https</x-admin-field-label>
                                    <input name="hero_buttons[{{ $i }}][url]" value="{{ old('hero_buttons.'.$i.'.url', $btn['url'] ?? '') }}" class="mt-1 w-full min-w-0 rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg" autocomplete="url">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-4"
            data-step="4"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-4"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Visi</x-admin-field-label>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-sm text-slate-300">Judul</x-admin-field-label>
                        <input name="vision_title" value="{{ old('vision_title', $data['vision_title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                    </div>
                    @include('admin.partials.fa-icon-input', [
                        'name' => 'vision_icon',
                        'value' => old('vision_icon', $data['vision_icon'] ?? ''),
                        'label' => 'Ikon judul visi',
                        'previewDefault' => 'fa-solid fa-cross',
                        'variant' => 'adjacent',
                        'hint' => '',
                    ])
                </div>
                @include('admin.cms.partials.content-blocks-editor', [
                    'data' => $data,
                    'blocksKey' => 'vision_blocks',
                    'htmlKey' => 'vision_body',
                    'idPrefix' => 'cms-vision-blocks-',
                    'legend' => 'Isi halaman',
                    'fieldsetClass' => 'space-y-4',
                ])
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-5"
            data-step="5"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-5"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Jelajahi</x-admin-field-label>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-sm text-slate-300">Judul blok</x-admin-field-label>
                        <input name="sidebar_section_title" value="{{ old('sidebar_section_title', $data['sidebar_section_title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                    </div>
                    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'sidebar_section', 'data' => $data, 'label' => 'Ikon judul'])
                </div>
                @php
                    $__sidebarCards = $data['sidebar_cards'] ?? [];
                    if (! is_array($__sidebarCards) || count($__sidebarCards) === 0) {
                        $__sidebarCards = [
                            ['key' => 'c0', 'url' => '', 'title' => '', 'subtitle' => '', 'icon' => ''],
                        ];
                    }
                @endphp
                <div class="flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        id="cms-sidebar-add-button"
                        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
                    >
                        Tambah kartu
                    </button>
                </div>
                <div id="cms-sidebar-cards-wrap" class="space-y-4">
                    @foreach ($__sidebarCards as $i => $c)
                        <div class="cms-sidebar-card-row space-y-4 rounded-lg border border-white/10 p-5 sm:p-6">
                            <div class="flex justify-end">
                                <button type="button" data-cms-sidebar-remove class="text-xs text-red-400 hover:underline">Hapus</button>
                            </div>
                            <input type="hidden" name="sidebar_cards[{{ $i }}][key]" value="{{ old('sidebar_cards.'.$i.'.key', $c['key'] ?? 'c'.$i) }}">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div><x-admin-field-label class="text-xs text-slate-400">URL</x-admin-field-label><input name="sidebar_cards[{{ $i }}][url]" value="{{ old('sidebar_cards.'.$i.'.url', $c['url'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg" data-cms-sidebar-url></div>
                                <div><x-admin-field-label class="text-xs text-slate-400">Judul kartu</x-admin-field-label><input name="sidebar_cards[{{ $i }}][title]" value="{{ old('sidebar_cards.'.$i.'.title', $c['title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg"></div>
                                <div>
                                    @php
                                        $__sidebarIconDefault = \App\Support\PublicNavIcon::forRouteRaw($c['url'] ?? '');
                                    @endphp
                                    @include('admin.partials.fa-icon-input', [
                                        'name' => 'sidebar_cards['.$i.'][icon]',
                                        'value' => old('sidebar_cards.'.$i.'.icon', $c['icon'] ?? ''),
                                        'label' => 'Ikon kartu',
                                        'previewDefault' => $__sidebarIconDefault,
                                        'variant' => 'adjacent',
                                        'hint' => '',
                                    ])
                                </div>
                                <div><x-admin-field-label class="text-xs text-slate-400">Subjudul</x-admin-field-label><input name="sidebar_cards[{{ $i }}][subtitle]" value="{{ old('sidebar_cards.'.$i.'.subtitle', $c['subtitle'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'sidebar_card_arrow', 'data' => $data, 'label' => 'Ikon panah'])

            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-6"
            data-step="6"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-6"
            aria-hidden="true"
        >
            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Menu navigasi</x-admin-field-label>
                @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'nav_mobile_chevron', 'data' => $data, 'label' => 'Ikon'])
                @php
                    $__navItems = $data['nav'] ?? [];
                    if (! is_array($__navItems) || count($__navItems) === 0) {
                        $__navItems = [
                            ['route' => '/', 'label' => '', 'icon' => ''],
                        ];
                    }
                @endphp
                <div class="flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        id="cms-nav-add-button"
                        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
                    >
                        Tambah item menu
                    </button>
                </div>
                <div id="cms-nav-items-wrap" class="space-y-4">
                    @foreach ($__navItems as $i => $n)
                        <div class="cms-nav-row space-y-4 rounded-lg border border-white/10 p-5 sm:p-6">
                            <div class="flex justify-end">
                                <button type="button" data-cms-nav-remove class="text-xs text-red-400 hover:underline">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-6 sm:items-end sm:gap-x-4 sm:gap-y-3 lg:grid-cols-12 lg:gap-3">
                                <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                    <x-admin-field-label class="text-xs text-slate-400">Label</x-admin-field-label>
                                    <input name="nav[{{ $i }}][label]" value="{{ old('nav.'.$i.'.label', $n['label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-4">
                                    @php
                                        $__navIconDefault = \App\Support\PublicNavIcon::forRouteRaw($n['route'] ?? '');
                                    @endphp
                                    @include('admin.partials.fa-icon-input', [
                                        'name' => 'nav['.$i.'][icon]',
                                        'value' => old('nav.'.$i.'.icon', $n['icon'] ?? ''),
                                        'label' => 'Ikon',
                                        'previewDefault' => $__navIconDefault,
                                        'variant' => 'adjacent',
                                        'hint' => '',
                                    ])
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-5">
                                    <x-admin-field-label class="text-xs text-slate-400">URL jalur atau https</x-admin-field-label>
                                    <input
                                        name="nav[{{ $i }}][route]"
                                        value="{{ \App\Support\PublicCmsUrl::formatNavPathForInput(old('nav.'.$i.'.route', $n['route'] ?? '')) }}"
                                        class="mt-1 w-full min-w-0 rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                                        autocomplete="url"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        </div>

        <div
            class="beranda-section-panel hidden space-y-10"
            id="beranda-section-panel-7"
            data-step="7"
            role="tabpanel"
            aria-labelledby="beranda-section-tab-7"
            aria-hidden="true"
        >
            @php
                $__footerLinks = $data['footer_quick_links'] ?? [];
                if (! is_array($__footerLinks) || count($__footerLinks) === 0) {
                    $__footerLinks = [
                        ['route' => '/', 'label' => '', 'icon' => ''],
                    ];
                }
                $__footerSocial = $data['footer_social_links'] ?? [];
                if (! is_array($__footerSocial) || count($__footerSocial) === 0) {
                    $__footerSocial = [
                        ['url' => '', 'label' => '', 'icon' => 'fa-brands fa-link'],
                    ];
                }
            @endphp

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Baris nama gereja</x-admin-field-label>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Baris 1</x-admin-field-label>
                        <p class="mt-1 rounded-md border border-white/10 bg-church-surface/50 px-3 py-2 text-sm text-church-fg">{{ old('church_name_line1', $data['church_name_line1'] ?? '') ?: '—' }}</p>
                    </div>
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Baris 2</x-admin-field-label>
                        <p class="mt-1 rounded-md border border-white/10 bg-church-surface/50 px-3 py-2 text-sm text-church-fg">{{ old('church_name_line2', $data['church_name_line2'] ?? '') ?: '—' }}</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Tautan cepat (opsional)</x-admin-field-label>
                <div class="flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        id="cms-footer-links-add-button"
                        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
                    >
                        Tambah tautan
                    </button>
                </div>
                <div id="cms-footer-links-wrap" class="space-y-4">
                    @foreach ($__footerLinks as $i => $f)
                        <div class="cms-footer-link-row space-y-4 rounded-lg border border-white/10 p-5 sm:p-6">
                            <div class="flex justify-end">
                                <button type="button" data-cms-footer-link-remove class="text-xs text-red-400 hover:underline">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-6 sm:items-end sm:gap-x-4 sm:gap-y-3 lg:grid-cols-12 lg:gap-3">
                                <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                    <x-admin-field-label class="text-xs text-slate-400">Label</x-admin-field-label>
                                    <input name="footer_quick_links[{{ $i }}][label]" value="{{ old('footer_quick_links.'.$i.'.label', $f['label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-4">
                                    @php
                                        $__flIconDefault = \App\Support\PublicNavIcon::forRouteRaw($f['route'] ?? '');
                                    @endphp
                                    @include('admin.partials.fa-icon-input', [
                                        'name' => 'footer_quick_links['.$i.'][icon]',
                                        'value' => old('footer_quick_links.'.$i.'.icon', $f['icon'] ?? ''),
                                        'label' => 'Ikon',
                                        'previewDefault' => $__flIconDefault,
                                        'variant' => 'adjacent',
                                        'hint' => '',
                                    ])
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-5">
                                    <x-admin-field-label class="text-xs text-slate-400">URL jalur atau https</x-admin-field-label>
                                    <input
                                        name="footer_quick_links[{{ $i }}][route]"
                                        value="{{ \App\Support\PublicCmsUrl::formatNavPathForInput(old('footer_quick_links.'.$i.'.route', $f['route'] ?? '')) }}"
                                        class="mt-1 w-full min-w-0 rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                                        autocomplete="url"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Kolom kontak</x-admin-field-label>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Judul kolom</x-admin-field-label>
                        <input name="footer_headings[contact]" value="{{ old('footer_headings.contact', $data['footer_headings']['contact'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg sm:max-w-xs">
                    </div>
                    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_contact_heading', 'data' => $data, 'label' => 'Ikon judul'])
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
                        <div>
                            <x-admin-field-label class="text-xs text-slate-400">Telepon</x-admin-field-label>
                            <input name="church_phone" value="{{ old('church_phone', $churchPhone ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                        </div>
                        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_phone_row', 'data' => $data, 'label' => 'Ikon'])
                    </div>
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
                        <div>
                            <x-admin-field-label class="text-xs text-slate-400">Email</x-admin-field-label>
                            <input name="church_email" type="email" value="{{ old('church_email', $churchEmail ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                        </div>
                        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_email_row', 'data' => $data, 'label' => 'Ikon'])
                    </div>
                </div>
            </fieldset>

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Kolom alamat</x-admin-field-label>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Judul kolom</x-admin-field-label>
                        <input name="footer_headings[address]" value="{{ old('footer_headings.address', $data['footer_headings']['address'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg sm:max-w-xs">
                    </div>
                    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_address_heading', 'data' => $data, 'label' => 'Ikon judul'])
                </div>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-start">
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Alamat lengkap</x-admin-field-label>
                        <textarea name="church_address" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">{{ old('church_address', $churchAddress ?? '') }}</textarea>
                    </div>
                    <div class="sm:pt-[1.375rem]">
                        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_map_pin_row', 'data' => $data, 'label' => 'Ikon pin'])
                    </div>
                </div>
            </fieldset>

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Kolom media sosial</x-admin-field-label>
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Judul kolom</x-admin-field-label>
                        <input name="footer_headings[social]" value="{{ old('footer_headings.social', $data['footer_headings']['social'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg sm:max-w-xs">
                    </div>
                    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'beranda', 'iconKey' => 'footer_social_heading', 'data' => $data, 'label' => 'Ikon judul'])
                </div>
                <div class="flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        id="cms-footer-social-add-button"
                        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
                    >
                        Tambah media sosial
                    </button>
                </div>
                <div id="cms-footer-social-wrap" class="space-y-4">
                    @foreach ($__footerSocial as $i => $s)
                        <div class="cms-footer-social-row space-y-4 rounded-lg border border-white/10 p-5 sm:p-6">
                            <div class="flex justify-end">
                                <button type="button" data-cms-footer-social-remove class="text-xs text-red-400 hover:underline">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-6 sm:items-end sm:gap-x-4 sm:gap-y-3 lg:grid-cols-12 lg:gap-3">
                                <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                    <x-admin-field-label class="text-xs text-slate-400">Label (aksesibilitas)</x-admin-field-label>
                                    <input name="footer_social_links[{{ $i }}][label]" value="{{ old('footer_social_links.'.$i.'.label', $s['label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-4">
                                    @include('admin.partials.fa-icon-input', [
                                        'name' => 'footer_social_links['.$i.'][icon]',
                                        'value' => old('footer_social_links.'.$i.'.icon', $s['icon'] ?? ''),
                                        'label' => 'Ikon',
                                        'previewDefault' => $s['icon'] ?? 'fa-brands fa-link',
                                        'variant' => 'adjacent',
                                        'hint' => '',
                                        'placeholder' => '',
                                    ])
                                </div>
                                <div class="min-w-0 sm:col-span-2 lg:col-span-5">
                                    <x-admin-field-label class="text-xs text-slate-400">URL</x-admin-field-label>
                                    <input
                                        name="footer_social_links[{{ $i }}][url]"
                                        value="{{ old('footer_social_links.'.$i.'.url', $s['url'] ?? '') }}"
                                        class="mt-1 w-full min-w-0 rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                                        autocomplete="url"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="public-card-hover space-y-5 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8">
                <x-admin-field-label as="legend">Baris hak cipta</x-admin-field-label>
                <div>
                    <x-admin-field-label class="text-sm text-slate-300">Teks hak cipta</x-admin-field-label>
                    <input
                        name="footer_copyright_text"
                        value="{{ old('footer_copyright_text', $data['footer_copyright_text'] ?? '© {year} Syalom Timika') }}"
                        class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg sm:max-w-md"
                        placeholder="© {year} Syalom Timika"
                    >
                    <p class="mt-1 text-xs text-slate-500">Gunakan <code class="rounded bg-white/10 px-1">{year}</code> agar tahun mengikuti tahun berjalan (contoh: © 2026 Syalom Timika).</p>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<script>
(function () {
    var root = document.getElementById('beranda-cms-section-tabs');
    if (!root) return;
    var panels = Array.prototype.slice.call(root.querySelectorAll('.beranda-section-panel'));
    var tabs = Array.prototype.slice.call(root.querySelectorAll('.beranda-section-tab'));
    var storageKey = 'berandaCmsSectionKotak';
    var count = panels.length;

    function showStep(index) {
        if (index < 0 || index >= count) return;
        panels.forEach(function (panel, i) {
            var on = i === index;
            panel.classList.toggle('hidden', !on);
            panel.setAttribute('aria-hidden', on ? 'false' : 'true');
        });
        tabs.forEach(function (tab, i) {
            var on = i === index;
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
            tab.setAttribute('tabindex', on ? '0' : '-1');
        });
        try {
            sessionStorage.setItem(storageKey, String(index));
        } catch (e) {}
    }

    function readStoredIndex() {
        try {
            var raw = sessionStorage.getItem(storageKey);
            var n = parseInt(raw, 10);
            if (!isNaN(n) && n >= 0 && n < count) return n;
        } catch (e) {}
        return 0;
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var step = parseInt(tab.getAttribute('data-step'), 10);
            showStep(step);
        });
    });

    root.addEventListener('keydown', function (e) {
        if (e.target.getAttribute('role') !== 'tab') return;
        var current = tabs.indexOf(e.target);
        if (current < 0) return;
        var next = current;
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            next = (current + 1) % count;
            e.preventDefault();
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            next = (current - 1 + count) % count;
            e.preventDefault();
        } else if (e.key === 'Home') {
            next = 0;
            e.preventDefault();
        } else if (e.key === 'End') {
            next = count - 1;
            e.preventDefault();
        } else {
            return;
        }
        showStep(next);
        tabs[next].focus();
    });

    showStep(readStoredIndex());
})();
(function () {
    var wrap = document.getElementById('cms-hero-buttons-wrap');
    var addBtn = document.getElementById('cms-hero-add-button');
    if (!wrap || !addBtn) return;

    var maxRows = 12;

    function rows() {
        return wrap.querySelectorAll('.cms-hero-button-row');
    }

    function reindexHeroButtons() {
        rows().forEach(function (row, i) {
            row.querySelectorAll('[name^="hero_buttons["]').forEach(function (el) {
                el.name = el.name.replace(/hero_buttons\[\d+\]/, 'hero_buttons[' + i + ']');
            });
            row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                inp.id = id;
                var box = inp.closest('[data-cms-fa-icon-field]');
                if (box) {
                    var lab = box.querySelector('label[for]');
                    if (lab) lab.setAttribute('for', id);
                }
            });
            var heading = row.querySelector('[data-cms-hero-heading]');
            if (heading) heading.textContent = 'Tombol ' + (i + 1);
        });
    }

    function updateAddRemoveUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxRows;
        addBtn.classList.toggle('opacity-40', n >= maxRows);
        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-hero-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function clearHeroRow(row) {
        var keyInp = row.querySelector('input[name$="[key]"]');
        if (keyInp) keyInp.value = 'b' + Date.now();
        var labelInp = row.querySelector('input[name$="[label]"]');
        if (labelInp) labelInp.value = '';
        var urlInp = row.querySelector('input[name$="[url]"]');
        if (urlInp) urlInp.value = '';
        var sel = row.querySelector('select[name$="[style]"]');
        if (sel) sel.value = 'primary';
        var iconInp = row.querySelector('input[name$="[icon]"]');
        if (iconInp) {
            iconInp.value = '';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    wrap.addEventListener('click', function (e) {
        var t = e.target.closest('[data-cms-hero-remove]');
        if (!t || !wrap.contains(t) || t.disabled) return;
        if (rows().length <= 1) return;
        var row = t.closest('.cms-hero-button-row');
        if (row) row.remove();
        reindexHeroButtons();
        updateAddRemoveUi();
    });

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var list = rows();
        if (list.length >= maxRows) return;
        var last = list[list.length - 1];
        var clone = last.cloneNode(true);
        wrap.appendChild(clone);
        reindexHeroButtons();
        clearHeroRow(rows()[rows().length - 1]);
        updateAddRemoveUi();
    });

    updateAddRemoveUi();
})();
(function () {
    var wrap = document.getElementById('cms-nav-items-wrap');
    var addBtn = document.getElementById('cms-nav-add-button');
    if (!wrap || !addBtn) return;

    var maxRows = 20;

    function rows() {
        return wrap.querySelectorAll('.cms-nav-row');
    }

    function reindexNavItems() {
        rows().forEach(function (row, i) {
            row.querySelectorAll('[name^="nav["]').forEach(function (el) {
                el.name = el.name.replace(/nav\[\d+\]/, 'nav[' + i + ']');
            });
            row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                inp.id = id;
                var box = inp.closest('[data-cms-fa-icon-field]');
                if (box) {
                    var lab = box.querySelector('label[for]');
                    if (lab) lab.setAttribute('for', id);
                }
            });
        });
    }

    function updateAddRemoveUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxRows;
        addBtn.classList.toggle('opacity-40', n >= maxRows);
        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-nav-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function clearNavRow(row) {
        var labelInp = row.querySelector('input[name$="[label]"]');
        if (labelInp) labelInp.value = '';
        var routeInp = row.querySelector('input[name$="[route]"]');
        if (routeInp) routeInp.value = '/';
        var iconInp = row.querySelector('input[name$="[icon]"]');
        if (iconInp) {
            iconInp.value = '';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    wrap.addEventListener('click', function (e) {
        var t = e.target.closest('[data-cms-nav-remove]');
        if (!t || !wrap.contains(t) || t.disabled) return;
        if (rows().length <= 1) return;
        var row = t.closest('.cms-nav-row');
        if (row) row.remove();
        reindexNavItems();
        updateAddRemoveUi();
    });

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var list = rows();
        if (list.length >= maxRows) return;
        var last = list[list.length - 1];
        var clone = last.cloneNode(true);
        wrap.appendChild(clone);
        reindexNavItems();
        clearNavRow(rows()[rows().length - 1]);
        updateAddRemoveUi();
    });

    updateAddRemoveUi();
})();
(function () {
    var wrap = document.getElementById('cms-sidebar-cards-wrap');
    var addBtn = document.getElementById('cms-sidebar-add-button');
    if (!wrap || !addBtn) return;

    var maxRows = 12;

    function rows() {
        return wrap.querySelectorAll('.cms-sidebar-card-row');
    }

    function reindexSidebarCards() {
        rows().forEach(function (row, i) {
            row.querySelectorAll('[name^="sidebar_cards["]').forEach(function (el) {
                el.name = el.name.replace(/sidebar_cards\[\d+\]/, 'sidebar_cards[' + i + ']');
            });
            row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                inp.id = id;
                var box = inp.closest('[data-cms-fa-icon-field]');
                if (box) {
                    var lab = box.querySelector('label[for]');
                    if (lab) lab.setAttribute('for', id);
                }
            });
        });
    }

    function updateAddRemoveUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxRows;
        addBtn.classList.toggle('opacity-40', n >= maxRows);
        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-sidebar-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function clearSidebarRow(row) {
        var keyInp = row.querySelector('input[name$="[key]"]');
        if (keyInp) keyInp.value = 'c' + Date.now();
        var urlInp = row.querySelector('input[name$="[url]"]');
        if (urlInp) urlInp.value = '';
        var titleInp = row.querySelector('input[name$="[title]"]');
        if (titleInp) titleInp.value = '';
        var subInp = row.querySelector('input[name$="[subtitle]"]');
        if (subInp) subInp.value = '';
        var iconInp = row.querySelector('input[name$="[icon]"]');
        if (iconInp) {
            iconInp.value = '';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    wrap.addEventListener('click', function (e) {
        var t = e.target.closest('[data-cms-sidebar-remove]');
        if (!t || !wrap.contains(t) || t.disabled) return;
        if (rows().length <= 1) return;
        var row = t.closest('.cms-sidebar-card-row');
        if (row) row.remove();
        reindexSidebarCards();
        updateAddRemoveUi();
    });

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var list = rows();
        if (list.length >= maxRows) return;
        var last = list[list.length - 1];
        var clone = last.cloneNode(true);
        wrap.appendChild(clone);
        reindexSidebarCards();
        clearSidebarRow(rows()[rows().length - 1]);
        updateAddRemoveUi();
    });

    updateAddRemoveUi();
})();
(function () {
    var wrap = document.getElementById('cms-footer-links-wrap');
    var addBtn = document.getElementById('cms-footer-links-add-button');
    if (!wrap || !addBtn) return;

    var maxRows = 12;

    function rows() {
        return wrap.querySelectorAll('.cms-footer-link-row');
    }

    function reindexFooterLinks() {
        rows().forEach(function (row, i) {
            row.querySelectorAll('[name^="footer_quick_links["]').forEach(function (el) {
                el.name = el.name.replace(/footer_quick_links\[\d+\]/, 'footer_quick_links[' + i + ']');
            });
            row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                inp.id = id;
                var box = inp.closest('[data-cms-fa-icon-field]');
                if (box) {
                    var lab = box.querySelector('label[for]');
                    if (lab) lab.setAttribute('for', id);
                }
            });
        });
    }

    function updateAddRemoveUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxRows;
        addBtn.classList.toggle('opacity-40', n >= maxRows);
        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-footer-link-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function clearFooterLinkRow(row) {
        var labelInp = row.querySelector('input[name$="[label]"]');
        if (labelInp) labelInp.value = '';
        var routeInp = row.querySelector('input[name$="[route]"]');
        if (routeInp) routeInp.value = '/';
        var iconInp = row.querySelector('input[name$="[icon]"]');
        if (iconInp) {
            iconInp.value = '';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    wrap.addEventListener('click', function (e) {
        var t = e.target.closest('[data-cms-footer-link-remove]');
        if (!t || !wrap.contains(t) || t.disabled) return;
        if (rows().length <= 1) return;
        var row = t.closest('.cms-footer-link-row');
        if (row) row.remove();
        reindexFooterLinks();
        updateAddRemoveUi();
    });

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var list = rows();
        if (list.length >= maxRows) return;
        var last = list[list.length - 1];
        var clone = last.cloneNode(true);
        wrap.appendChild(clone);
        reindexFooterLinks();
        clearFooterLinkRow(rows()[rows().length - 1]);
        updateAddRemoveUi();
    });

    updateAddRemoveUi();
})();
(function () {
    var wrap = document.getElementById('cms-footer-social-wrap');
    var addBtn = document.getElementById('cms-footer-social-add-button');
    if (!wrap || !addBtn) return;

    var maxRows = 12;

    function rows() {
        return wrap.querySelectorAll('.cms-footer-social-row');
    }

    function reindexFooterSocial() {
        rows().forEach(function (row, i) {
            row.querySelectorAll('[name^="footer_social_links["]').forEach(function (el) {
                el.name = el.name.replace(/footer_social_links\[\d+\]/, 'footer_social_links[' + i + ']');
            });
            row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                inp.id = id;
                var box = inp.closest('[data-cms-fa-icon-field]');
                if (box) {
                    var lab = box.querySelector('label[for]');
                    if (lab) lab.setAttribute('for', id);
                }
            });
        });
    }

    function updateAddRemoveUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxRows;
        addBtn.classList.toggle('opacity-40', n >= maxRows);
        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-footer-social-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function clearFooterSocialRow(row) {
        var labelInp = row.querySelector('input[name$="[label]"]');
        if (labelInp) labelInp.value = '';
        var urlInp = row.querySelector('input[name$="[url]"]');
        if (urlInp) urlInp.value = '';
        var iconInp = row.querySelector('input[name$="[icon]"]');
        if (iconInp) {
            iconInp.value = 'fa-brands fa-link';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    wrap.addEventListener('click', function (e) {
        var t = e.target.closest('[data-cms-footer-social-remove]');
        if (!t || !wrap.contains(t) || t.disabled) return;
        if (rows().length <= 1) return;
        var row = t.closest('.cms-footer-social-row');
        if (row) row.remove();
        reindexFooterSocial();
        updateAddRemoveUi();
    });

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var list = rows();
        if (list.length >= maxRows) return;
        var last = list[list.length - 1];
        var clone = last.cloneNode(true);
        wrap.appendChild(clone);
        reindexFooterSocial();
        clearFooterSocialRow(rows()[rows().length - 1]);
        updateAddRemoveUi();
    });

    updateAddRemoveUi();
})();
</script>
