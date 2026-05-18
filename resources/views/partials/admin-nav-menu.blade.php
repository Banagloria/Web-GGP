@php
    $compact = $compact ?? false;
    $canManageSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;
    $navClass = $compact
        ? 'flex flex-col divide-y divide-white/10 py-2 text-[0.8125rem] leading-snug sm:text-sm'
        : 'admin-sidebar__menu flex flex-col gap-1 p-3 text-sm';
    $rowCompact = 'flex min-h-[2.75rem] items-center justify-between gap-2 px-4 py-2.5 font-medium leading-snug transition focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/40 sm:min-h-0 sm:px-4 sm:text-sm';
    $rowFull = 'admin-nav-link flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm transition duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50';
    $rowFullActive = 'border-church-gold/35 bg-church-gold/10 text-church-gold ring-1 ring-church-gold/25 shadow-inner';
    $rowFullIdle = 'text-slate-300 hover:border-church-gold/25 hover:bg-church-surface/60 hover:text-church-fg';
    $iconMain = 'size-5 shrink-0 text-church-gold/85';
    $iconSub = 'size-4 shrink-0 text-church-gold/75';
    $subLink = 'admin-nav-sublink public-btn-hover flex items-center gap-2.5 rounded-lg border border-transparent px-2.5 py-2 text-[0.8125rem] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50 sm:px-3 sm:text-sm';
    $subLinkActive = 'border-church-gold/30 bg-church-gold/10 font-medium text-church-gold ring-1 ring-church-gold/20';
    $subLinkIdle = 'text-slate-400 hover:border-white/10 hover:bg-church-surface/50 hover:text-slate-200';
    $iconChevron = 'size-4 shrink-0 text-white/45 transition group-open:rotate-180';
@endphp
<nav class="{{ $navClass }}" aria-label="{{ $compact ? 'Menu admin (mobile)' : 'Menu admin' }}">
    @if ($compact)
        <a href="{{ route('dashboard.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.index') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'home', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Dashboard</span>
            </span>
        </a>

        <details class="group" @if (request()->routeIs('dashboard.pendaftaran-data.*')) open @endif>
            <summary class="{{ $rowCompact }} cursor-pointer list-none hover:bg-white/5 [&::-webkit-details-marker]:hidden">
                <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                    @include('partials.dashboard-nav-icon', ['which' => 'clipboard', 'class' => $iconMain])
                    <span class="min-w-0 flex-1 break-words">Pendaftaran</span>
                </span>
                @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
            </summary>
            <div class="space-y-0 border-t border-white/10 bg-white/[0.04] px-3 py-1.5 sm:px-4">
                @forelse ($cmsPendaftaranNav ?? [] as $__navPendaftaran)
                    <a href="{{ $__navPendaftaran['url'] }}" class="public-btn-hover flex items-center gap-2.5 rounded-md px-2 py-2 text-[0.8125rem] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50 sm:px-3 sm:text-sm {{ request()->routeIs('dashboard.pendaftaran-data.*') && request()->route('slug') === $__navPendaftaran['slug'] ? 'bg-white/15 font-medium' : '' }}">
                        @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconSub])
                        <span>{{ $__navPendaftaran['label'] }}</span>
                    </a>
                @empty
                    <p class="px-2 py-2 text-xs text-slate-500">Belum ada kartu di CMS.</p>
                @endforelse
            </div>
        </details>

        <a href="{{ route('dashboard.pengumuman.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.pengumuman.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'bell', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Pengumuman</span>
            </span>
        </a>
        <a href="{{ route('dashboard.jadwal-ibadah.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.jadwal-ibadah.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'calendar', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Jadwal</span>
            </span>
        </a>
        <a href="{{ route('dashboard.kontak.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.kontak.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'envelope', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Kontak</span>
            </span>
        </a>
        <a href="{{ route('dashboard.galeri.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.galeri.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'photo', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Galeri</span>
            </span>
        </a>
        @if ($canManageSuperAdmin)
            <a href="{{ route('dashboard.halaman.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.halaman.index', 'dashboard.halaman.cms.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
                <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                    @include('partials.dashboard-nav-icon', ['which' => 'document', 'class' => $iconMain])
                    <span class="min-w-0 flex-1 break-words">Halaman</span>
                </span>
            </a>
        @endif
        <a href="{{ route('dashboard.profil-akun.edit') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.profil-akun.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
            <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                @include('partials.dashboard-nav-icon', ['which' => 'user', 'class' => $iconMain])
                <span class="min-w-0 flex-1 break-words">Profil</span>
            </span>
        </a>
        @if ($canManageSuperAdmin)
            <a href="{{ route('dashboard.akun.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.akun.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
                <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                    @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconMain])
                    <span class="min-w-0 flex-1 break-words">Manajemen akun</span>
                </span>
            </a>
        @endif
    @else
        <a href="{{ route('dashboard.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.index') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'home', 'class' => $iconMain])
            <span>Dashboard</span>
        </a>

        <details class="admin-nav-group group rounded-xl" @if (request()->routeIs('dashboard.pendaftaran-data.*')) open @endif>
            <summary class="admin-nav-link flex cursor-pointer list-none items-center justify-between gap-2 rounded-xl border border-transparent px-3 py-2.5 text-sm text-slate-300 transition duration-300 hover:border-church-gold/25 hover:bg-church-surface/60 hover:text-church-fg focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/50">
                <span class="flex min-w-0 flex-1 items-center gap-2.5">
                    @include('partials.dashboard-nav-icon', ['which' => 'clipboard', 'class' => $iconMain])
                    <span class="font-medium">Pendaftaran</span>
                </span>
                @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
            </summary>
            <div class="admin-nav-submenu ml-1.5 mt-1 space-y-0.5 border-l border-church-gold/20 py-1 pl-2 sm:ml-2 sm:pl-2.5">
                @forelse ($cmsPendaftaranNav ?? [] as $__navPendaftaran)
                    <a href="{{ $__navPendaftaran['url'] }}" class="{{ $subLink }} {{ request()->routeIs('dashboard.pendaftaran-data.*') && request()->route('slug') === $__navPendaftaran['slug'] ? $subLinkActive : $subLinkIdle }}">
                        @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconSub])
                        <span>{{ $__navPendaftaran['label'] }}</span>
                    </a>
                @empty
                    <p class="px-2 py-1 text-xs text-slate-500">Belum ada kartu di CMS.</p>
                @endforelse
            </div>
        </details>

        <a href="{{ route('dashboard.pengumuman.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.pengumuman.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'bell', 'class' => $iconMain])
            <span>Pengumuman</span>
        </a>
        <a href="{{ route('dashboard.jadwal-ibadah.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.jadwal-ibadah.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'calendar', 'class' => $iconMain])
            <span>Jadwal</span>
        </a>
        <a href="{{ route('dashboard.kontak.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.kontak.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'envelope', 'class' => $iconMain])
            <span>Kontak</span>
        </a>
        <a href="{{ route('dashboard.galeri.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.galeri.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'photo', 'class' => $iconMain])
            <span>Galeri</span>
        </a>
        @if ($canManageSuperAdmin)
            <a href="{{ route('dashboard.halaman.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.halaman.index', 'dashboard.halaman.cms.*') ? $rowFullActive : $rowFullIdle }}">
                @include('partials.dashboard-nav-icon', ['which' => 'document', 'class' => $iconMain])
                <span>Halaman</span>
            </a>
        @endif
        <a href="{{ route('dashboard.profil-akun.edit') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.profil-akun.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'user', 'class' => $iconMain])
            <span>Profil</span>
        </a>
        @if ($canManageSuperAdmin)
            <a href="{{ route('dashboard.akun.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.akun.*') ? $rowFullActive : $rowFullIdle }}">
                @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconMain])
                <span>Manajemen akun</span>
            </a>
        @endif
    @endif
</nav>
