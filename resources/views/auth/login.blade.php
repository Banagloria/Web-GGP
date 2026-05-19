<!DOCTYPE html>
<html lang="id" class="min-w-0 scroll-smooth bg-church-void">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin — {{ config('app.name') }}</title>
    @php
        $__favRaw = isset($siteLogoUrl) ? trim((string) $siteLogoUrl) : '';
        $__favSrc = $__favRaw !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($__favRaw) : null;
        $__favBust = $__favRaw !== '' ? '?v='.substr(md5($__favRaw), 0, 12) : '';
    @endphp
    @if ($__favSrc)
        <link rel="icon" href="{{ $__favSrc }}{{ $__favBust }}" sizes="any">
        <link rel="apple-touch-icon" href="{{ $__favSrc }}{{ $__favBust }}">
    @else
        <link rel="icon" href="data:," sizes="any">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @include('partials.app-styles')
</head>
<body class="flex min-h-dvh min-w-0 flex-col items-center justify-center overflow-x-hidden bg-gradient-to-b from-church-void via-church-bg to-church-surface px-4 py-10 font-sans text-slate-200 antialiased selection:bg-church-gold/30 selection:text-church-navy">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -right-[10%] -top-[15%] size-[min(22rem,55vw)] rounded-full bg-church-gold/12 blur-3xl"></div>
        <div class="absolute -bottom-[20%] -left-[10%] size-[min(20rem,50vw)] rounded-full bg-church-navy-mid/35 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md rounded-2xl border border-white/10 bg-church-card p-6 shadow-2xl shadow-black/30 ring-1 ring-church-gold/10 sm:p-8">
        <div class="mb-6 flex justify-center">
            <div class="flex size-14 items-center justify-center overflow-hidden sm:size-16">
                @include('partials.site-logo-or-mark')
            </div>
        </div>

        <p class="text-center font-script text-2xl text-church-gold/95 sm:text-3xl">Shalom</p>
        <h1 class="mt-1 text-center font-serif text-2xl font-bold tracking-tight text-church-fg sm:text-3xl">Masuk admin</h1>
        <p class="mt-1 text-center text-sm text-slate-400">{{ $churchNameLine2 }}</p>

        <x-flash-success class="mt-5" />

        @if (session('login_debug_hint'))
            <div class="mt-5 rounded-xl border border-amber-500/30 bg-amber-950/40 px-4 py-3 text-sm leading-relaxed text-amber-100 ring-1 ring-amber-500/20" role="note">
                {{ session('login_debug_hint') }}
            </div>
        @endif

        <form method="post" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="mt-1.5 w-full rounded-lg border border-white/15 bg-church-surface px-3 py-2.5 text-church-fg shadow-inner transition placeholder:text-slate-500 focus:border-church-gold/50 focus:outline-none focus:ring-2 focus:ring-church-gold/25"
                    placeholder="nama@email.com"
                >
                @error('email')
                    <p role="alert" class="mt-2 rounded-lg border border-red-500/30 bg-red-950/40 px-3 py-2 text-sm text-red-200">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                <x-password-input
                    name="password"
                    id="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    wrapper-class="mt-1.5"
                    input-class="rounded-lg border border-white/15 bg-church-surface py-2.5 pl-3 text-church-fg shadow-inner transition placeholder:text-slate-500 focus:border-church-gold/50 focus:outline-none focus:ring-2 focus:ring-church-gold/25"
                />
                @error('password')
                    <p role="alert" class="mt-2 rounded-lg border border-red-500/30 bg-red-950/40 px-3 py-2 text-sm text-red-200">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex cursor-pointer items-center gap-2.5">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="size-4 rounded border-white/25 bg-church-surface accent-church-gold focus:ring-2 focus:ring-church-gold/40 focus:ring-offset-0 focus:ring-offset-church-card"
                >
                <span class="text-sm text-slate-300">Ingat saya di perangkat ini</span>
            </label>

            <button
                type="submit"
                class="public-btn-hover inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-church-gold to-church-gold-soft px-4 py-3 text-sm font-semibold text-church-navy focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
            >
                Masuk
            </button>

            <p class="text-center">
                <a
                    href="{{ route('home') }}"
                    class="text-sm font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/60 focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
                >
                    &larr; Kembali ke beranda
                </a>
            </p>
        </form>
    </div>

    <p class="mt-8 max-w-md text-center text-xs text-slate-500">
        Akses pengurus · &copy; {{ date('Y') }} {{ $churchNameLine2 }}
    </p>

    @include('partials.flash-success-script')
</body>
</html>
