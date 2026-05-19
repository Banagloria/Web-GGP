@php
    $compact = $compact ?? false;
    $rowCompact = $rowCompact ?? '';
    $rowFull = $rowFull ?? '';
    $iconMain = $iconMain ?? 'size-5 shrink-0 text-church-gold/85';
    $iconSub = $iconSub ?? 'size-4 shrink-0 text-church-gold/75';
    $iconChevron = $iconChevron ?? 'size-4 shrink-0 text-white/45 transition group-open:rotate-180';
    $subLink = $subLink ?? '';
    $subLinkActive = $subLinkActive ?? '';
    $subLinkIdle = $subLinkIdle ?? '';
    $kontenOpen = request()->routeIs(
        'dashboard.pengumuman.*',
        'dashboard.jadwal-ibadah.*',
        'dashboard.kontak.*',
        'dashboard.galeri.*',
    );
    $kontenNavItems = [
        [
            'label' => 'Pengumuman',
            'url' => route('dashboard.pengumuman.index'),
            'icon' => 'bell',
            'active' => request()->routeIs('dashboard.pengumuman.*'),
        ],
        [
            'label' => 'Jadwal',
            'url' => route('dashboard.jadwal-ibadah.index'),
            'icon' => 'calendar',
            'active' => request()->routeIs('dashboard.jadwal-ibadah.*'),
        ],
        [
            'label' => 'Kontak',
            'url' => route('dashboard.kontak.index'),
            'icon' => 'envelope',
            'active' => request()->routeIs('dashboard.kontak.*'),
        ],
        [
            'label' => 'Galeri',
            'url' => route('dashboard.galeri.index'),
            'icon' => 'photo',
            'active' => request()->routeIs('dashboard.galeri.*'),
        ],
    ];
@endphp

@if ($compact)
    <details class="group" @if ($kontenOpen) open @endif>
        <summary class="{{ $rowCompact }} cursor-pointer list-none hover:bg-white/5 [&::-webkit-details-marker]:hidden">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'document', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Konten</span>
            </span>
            @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
        </summary>
        <div class="space-y-0 border-t border-white/10 bg-white/[0.04] px-3 py-1.5 sm:px-4">
            @foreach ($kontenNavItems as $__navKonten)
                <a href="{{ $__navKonten['url'] }}" class="public-btn-hover flex items-center gap-2.5 rounded-md px-2 py-2 text-[0.8125rem] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50 sm:px-3 sm:text-sm {{ $__navKonten['active'] ? 'bg-white/15 font-medium text-church-gold' : '' }}">
                    @include('partials.dashboard-nav-icon', ['which' => $__navKonten['icon'], 'class' => $iconSub])
                    <span>{{ $__navKonten['label'] }}</span>
                </a>
            @endforeach
        </div>
    </details>
@else
    <details class="admin-nav-group group rounded-xl" @if ($kontenOpen) open @endif>
        <summary class="admin-nav-link flex cursor-pointer list-none items-center justify-between gap-2 rounded-xl border border-transparent px-3 py-2.5 text-sm text-slate-300 transition duration-300 hover:border-church-gold/25 hover:bg-church-surface/60 hover:text-church-fg focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/50">
            <span class="flex min-w-0 flex-1 items-center gap-2.5">
                @include('partials.dashboard-nav-icon', ['which' => 'document', 'class' => $iconMain])
                <span class="font-medium">Konten</span>
            </span>
            @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
        </summary>
        <div class="admin-nav-submenu ml-1.5 mt-1 space-y-0.5 border-l border-church-gold/20 py-1 pl-2 sm:ml-2 sm:pl-2.5">
            @foreach ($kontenNavItems as $__navKonten)
                <a href="{{ $__navKonten['url'] }}" class="{{ $subLink }} {{ $__navKonten['active'] ? $subLinkActive : $subLinkIdle }}">
                    @include('partials.dashboard-nav-icon', ['which' => $__navKonten['icon'], 'class' => $iconSub])
                    <span>{{ $__navKonten['label'] }}</span>
                </a>
            @endforeach
        </div>
    </details>
@endif
