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

        <details class="group" @if (request()->routeIs('dashboard.pendaftaran.*')) open @endif>
            <summary class="{{ $rowCompact }} cursor-pointer list-none hover:bg-white/5 [&::-webkit-details-marker]:hidden">
                <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                    @include('partials.dashboard-nav-icon', ['which' => 'clipboard', 'class' => $iconMain])
                    <span class="min-w-0 flex-1 break-words">Pendaftaran</span>
                </span>
                @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
            </summary>
            <div class="space-y-0 border-t border-white/10 bg-white/[0.04] px-3 py-1.5 sm:px-4">
                @forelse ($cmsPendaftaranNav ?? [] as $__navPendaftaran)
                    <a href="{{ $__navPendaftaran['url'] }}" class="public-btn-hover flex items-center gap-2.5 rounded-md px-2 py-2 text-[0.8125rem] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50 sm:px-3 sm:text-sm {{ request()->routeIs('dashboard.pendaftaran.*') && request()->route('slug') === $__navPendaftaran['slug'] ? 'bg-white/15 font-medium text-church-gold' : '' }}">
                        @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconSub])
                        <span>{{ $__navPendaftaran['label'] }}</span>
                    </a>
                @empty
                    <p class="px-2 py-2 text-xs text-slate-500">Belum ada kartu di CMS.</p>
                @endforelse
            </div>
        </details>

        @include('partials.admin-nav-pendaftaran-diterima', compact('compact', 'rowCompact', 'iconMain', 'iconSub', 'iconChevron', 'subLink', 'subLinkActive', 'subLinkIdle'))

        @include('partials.admin-nav-konten', compact('compact', 'rowCompact', 'iconMain', 'iconSub', 'iconChevron', 'subLink', 'subLinkActive', 'subLinkIdle'))
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
            <a href="{{ route('dashboard.setting.index') }}" class="{{ $rowCompact }} {{ request()->routeIs('dashboard.setting.*') ? 'border-l-4 border-l-church-gold bg-white/10 text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-white/5' }}">
                <span class="flex min-w-0 flex-1 items-center gap-2.5 pr-1">
                    @include('partials.dashboard-nav-icon', ['which' => 'cog', 'class' => $iconMain])
                    <span class="min-w-0 flex-1 break-words">Setting</span>
                </span>
            </a>
        @endif
    @else
        <a href="{{ route('dashboard.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.index') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'home', 'class' => $iconMain])
            <span>Dashboard</span>
        </a>

        <details class="admin-nav-group group rounded-xl" @if (request()->routeIs('dashboard.pendaftaran.*')) open @endif>
            <summary class="admin-nav-link flex cursor-pointer list-none items-center justify-between gap-2 rounded-xl border border-transparent px-3 py-2.5 text-sm text-slate-300 transition duration-300 hover:border-church-gold/25 hover:bg-church-surface/60 hover:text-church-fg focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/50">
                <span class="flex min-w-0 flex-1 items-center gap-2.5">
                    @include('partials.dashboard-nav-icon', ['which' => 'clipboard', 'class' => $iconMain])
                    <span class="font-medium">Pendaftaran</span>
                </span>
                @include('partials.dashboard-nav-icon', ['which' => 'chevron-down', 'class' => $iconChevron])
            </summary>
            <div class="admin-nav-submenu ml-1.5 mt-1 space-y-0.5 border-l border-church-gold/20 py-1 pl-2 sm:ml-2 sm:pl-2.5">
                @forelse ($cmsPendaftaranNav ?? [] as $__navPendaftaran)
                    <a href="{{ $__navPendaftaran['url'] }}" class="{{ $subLink }} {{ request()->routeIs('dashboard.pendaftaran.*') && request()->route('slug') === $__navPendaftaran['slug'] ? $subLinkActive : $subLinkIdle }}">
                        @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconSub])
                        <span>{{ $__navPendaftaran['label'] }}</span>
                    </a>
                @empty
                    <p class="px-2 py-1 text-xs text-slate-500">Belum ada kartu di CMS.</p>
                @endforelse
            </div>
        </details>

        @include('partials.admin-nav-pendaftaran-diterima', compact('compact', 'rowFull', 'iconMain', 'iconSub', 'iconChevron', 'subLink', 'subLinkActive', 'subLinkIdle'))

        @include('partials.admin-nav-konten', compact('compact', 'rowFull', 'iconMain', 'iconSub', 'iconChevron', 'subLink', 'subLinkActive', 'subLinkIdle'))
        <a href="{{ route('dashboard.profil-akun.edit') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.profil-akun.*') ? $rowFullActive : $rowFullIdle }}">
            @include('partials.dashboard-nav-icon', ['which' => 'user', 'class' => $iconMain])
            <span>Profil</span>
        </a>
        @if ($canManageSuperAdmin)
            <a href="{{ route('dashboard.akun.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.akun.*') ? $rowFullActive : $rowFullIdle }}">
                @include('partials.dashboard-nav-icon', ['which' => 'users', 'class' => $iconMain])
                <span>Manajemen akun</span>
            </a>
            <a href="{{ route('dashboard.setting.index') }}" class="{{ $rowFull }} {{ request()->routeIs('dashboard.setting.*') ? $rowFullActive : $rowFullIdle }}">
                @include('partials.dashboard-nav-icon', ['which' => 'cog', 'class' => $iconMain])
                <span>Setting</span>
            </a>
        @endif
    @endif
</nav>
