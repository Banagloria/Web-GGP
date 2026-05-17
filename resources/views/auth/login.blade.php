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
            <div class="flex size-14 items-center justify-center rounded-2xl border border-white/20 bg-white/5 p-1 shadow-inner ring-1 ring-church-gold/25 sm:size-16">
                @include('partials.site-logo-or-mark', ['imgClass' => 'max-h-12 max-w-12 object-contain sm:max-h-14 sm:max-w-14'])
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
                <div class="relative mt-1.5">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-lg border border-white/15 bg-church-surface py-2.5 pl-3 pr-12 text-church-fg shadow-inner transition placeholder:text-slate-500 focus:border-church-gold/50 focus:outline-none focus:ring-2 focus:ring-church-gold/25"
                        placeholder="••••••••"
                    >
                    <button
                        type="button"
                        id="toggle-password"
                        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-lg text-slate-400 transition hover:text-church-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/40"
                        aria-label="Tampilkan sandi"
                        aria-pressed="false"
                    >
                        <span class="icon-eye block" aria-hidden="true">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="icon-eye-off hidden" aria-hidden="true">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </span>
                    </button>
                </div>
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

    <script>
        (function () {
            var btn = document.getElementById('toggle-password');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            var eye = btn.querySelector('.icon-eye');
            var eyeOff = btn.querySelector('.icon-eye-off');
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', show ? 'Sembunyikan sandi' : 'Tampilkan sandi');
                if (eye && eyeOff) {
                    eye.classList.toggle('hidden', show);
                    eyeOff.classList.toggle('hidden', !show);
                }
            });
        })();
    </script>
    @include('partials.flash-success-script')
</body>
</html>
