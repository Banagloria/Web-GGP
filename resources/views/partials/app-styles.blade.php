{{--
  Urutan: (1) Vite dev (hot). (2) CSS statis public/css/app.css.
  (3) Vite hanya jika manifest.json valid (bukan berkas kosong/sisa deploy) — hindari ViteManifestNotFoundException → HTTP 500.
  (4) Fallback link tetap dikeluarkan agar tidak "tanpa stylesheet".
--}}
@if (\App\Support\ViteAssets::hotFileActive())
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@elseif (file_exists(public_path('css/app.css')))
    <link rel="stylesheet" href="/css/app.css?v={{ filemtime(public_path('css/app.css')) }}">
@elseif (\App\Support\ViteAssets::manifestIsUsable())
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <link rel="stylesheet" href="/css/app.css?v=1">
@endif
