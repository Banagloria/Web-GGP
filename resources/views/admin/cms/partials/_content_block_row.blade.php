@php
    use App\Support\CmsContentBlocks;

    $blocksKey = $blocksKey ?? 'blocks';
    $blockType = $block['type'] ?? 'p';
    $blockItems = $block['items'] ?? [];
    if (! is_array($blockItems) || count($blockItems) === 0) {
        $blockItems = [''];
    }
@endphp
<div class="cms-content-block-row space-y-3 rounded-lg border border-white/10 bg-church-surface/40 p-5 sm:p-6" data-block-type="{{ $blockType }}">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <span class="text-xs font-semibold uppercase tracking-wide text-church-gold">{{ CmsContentBlocks::typeLabel($blockType) }}</span>
        <button type="button" data-cms-block-remove class="text-xs text-red-400 hover:underline">Hapus</button>
    </div>
    <input type="hidden" name="{{ $blocksKey }}[{{ $index }}][type]" value="{{ $blockType }}" data-block-type-input>

    @if (CmsContentBlocks::isTextBlock($blockType))
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Teks</x-admin-field-label>
            @if ($blockType === 'p')
                <textarea
                    name="{{ $blocksKey }}[{{ $index }}][text]"
                    rows="4"
                    class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                >{{ old($blocksKey.'.'.$index.'.text', $block['text'] ?? '') }}</textarea>
            @else
                <input
                    type="text"
                    name="{{ $blocksKey }}[{{ $index }}][text]"
                    value="{{ old($blocksKey.'.'.$index.'.text', $block['text'] ?? '') }}"
                    class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                >
            @endif
        </div>
    @else
        <div class="cms-block-list-items space-y-2" data-block-list-items>
            @foreach ($blockItems as $j => $item)
                <div class="cms-block-list-item flex gap-2">
                    <input
                        type="text"
                        name="{{ $blocksKey }}[{{ $index }}][items][{{ $j }}]"
                        value="{{ old($blocksKey.'.'.$index.'.items.'.$j, $item) }}"
                        class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg"
                    >
                    <button type="button" data-cms-block-list-item-remove class="shrink-0 text-xs text-red-400 hover:underline">Hapus</button>
                </div>
            @endforeach
        </div>
        <button
            type="button"
            data-cms-block-list-item-add
            class="public-btn-hover rounded-md border border-white/15 bg-church-surface/50 px-2 py-1 text-xs text-slate-300"
        >
            + Item daftar
        </button>
    @endif
</div>
