@props([
    'icon' => 'fa-solid fa-pen-to-square',
    'title',
    'subtitle' => null,
])

<div class="reg-form-card-header">
    <p class="relative flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-church-gold/90">
        <i class="{{ $icon }} text-[0.7rem]" aria-hidden="true"></i>
        {{ $title }}
    </p>
    @if ($subtitle)
        <p class="relative mt-1.5 text-sm text-slate-400">{{ $subtitle }}</p>
    @endif
</div>
