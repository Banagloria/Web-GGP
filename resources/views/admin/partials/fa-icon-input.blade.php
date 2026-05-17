@php
    $variant = $variant ?? 'default';
    $labelClass = $labelClass ?? ($variant === 'adjacent' ? 'text-xs font-medium text-slate-400' : 'text-sm text-slate-300');
    $inputId = 'fa-icon-'.preg_replace('/[^a-z0-9_-]/i', '-', $name);
    $raw = old(str_replace(['[', ']'], ['.', ''], $name), $value ?? '');
    $resolved = \App\Support\CmsIcon::toFontAwesome($raw, $previewDefault ?? 'fa-solid fa-circle');
    $hintText = $hint ?? ($variant === 'adjacent' ? '' : 'Kelas FA 6, mis. fa-solid fa-bullhorn — daftar ikon di fontawesome.com/icons');
@endphp
<div class="cms-fa-icon-field" data-cms-fa-icon-field>
    <label for="{{ $inputId }}" class="block">
        <span class="{{ $labelClass }} inline-flex items-center gap-2">
            <i class="{{ \App\Support\AdminFormLabelIcon::for($label ?? 'Ikon Font Awesome', 'fa-solid fa-icons') }} shrink-0 text-church-gold/80" aria-hidden="true"></i>
            {{ $label ?? 'Ikon Font Awesome' }}
        </span>
    </label>
    <div class="mt-1 flex gap-2">
        <input
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $resolved }}"
            placeholder="{{ $placeholder ?? ($variant === 'adjacent' ? '' : 'fa-solid fa-church') }}"
            autocomplete="off"
            spellcheck="false"
            data-cms-fa-icon-input
            {!! $inputAttrs ?? '' !!}
            class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface px-3 py-2 font-mono text-sm text-church-fg {{ $variant === 'adjacent' ? 'py-2' : '' }}"
        >
        <span
            class="{{ $variant === 'adjacent' ? 'flex size-10 shrink-0' : 'flex size-[42px] shrink-0' }} items-center justify-center rounded-md border border-white/15 bg-church-surface/80 text-lg text-church-gold"
            data-cms-fa-icon-preview
            aria-hidden="true"
        >
            <i class="{{ $resolved }}"></i>
        </span>
    </div>
    @if (trim((string) $hintText) !== '')
        <p class="mt-1.5 text-xs text-slate-500">{{ $hintText }}</p>
    @endif
</div>

@once
    @push('head')
        @include('partials.font-awesome')
    @endpush
    @push('scripts')
        <script>
            (function () {
                function normalizeFa(raw) {
                    return String(raw || '')
                        .trim()
                        .replace(/\bfas\b/gi, 'fa-solid')
                        .replace(/\bfar\b/gi, 'fa-regular')
                        .replace(/\bfab\b/gi, 'fa-brands')
                        .replace(/\s+/g, ' ');
                }
                function looksLikeFa(raw) {
                    var v = normalizeFa(raw);
                    return /\bfa-(solid|regular|brands|light|thin|duotone)\b/i.test(v) ||
                        /\bfa-[a-z0-9-]+\b/i.test(v);
                }
                function updatePreview(input) {
                    var box = input.closest('[data-cms-fa-icon-field]');
                    if (!box) return;
                    var preview = box.querySelector('[data-cms-fa-icon-preview]');
                    if (!preview) return;
                    var v = normalizeFa(input.value);
                    if (!looksLikeFa(v)) {
                        preview.innerHTML = '<i class="fa-solid fa-circle opacity-30" aria-hidden="true"></i>';
                        return;
                    }
                    preview.innerHTML = '<i class="' + v.replace(/"/g, '') + '" aria-hidden="true"></i>';
                }
                document.addEventListener('input', function (e) {
                    if (e.target && e.target.matches('[data-cms-fa-icon-input]')) {
                        updatePreview(e.target);
                    }
                });
                document.querySelectorAll('[data-cms-fa-icon-input]').forEach(updatePreview);
            })();
        </script>
    @endpush
@endonce
