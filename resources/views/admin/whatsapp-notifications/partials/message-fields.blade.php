@php
    $prefix = $prefix ?? 'msg';
    $template = $template ?? null;
    $triggerPlaceholders = $triggerPlaceholders ?? [];
    $titleId = $prefix.'_title';
    $triggerId = $prefix.'_trigger';
    $messageId = $prefix.'_message';
    $selectedTrigger = old('trigger_key', $template?->trigger_key);
    $placeholderFields = $selectedTrigger ? ($triggerPlaceholders[$selectedTrigger] ?? []) : [];
@endphp

<div>
    <x-admin-field-label for="{{ $titleId }}">Judul kotak pesan</x-admin-field-label>
    <input
        id="{{ $titleId }}"
        name="title"
        value="{{ old('title', $template?->title) }}"
        required
        placeholder="Pesan Notifikasi Pendaftaran Baptis"
        class="admin-list-toolbar__input mt-1 w-full"
    >
</div>

<div class="mt-4">
    <x-admin-field-label for="{{ $triggerId }}">Tombol pemicu (form publik)</x-admin-field-label>
    <select id="{{ $triggerId }}" name="trigger_key" required class="admin-list-toolbar__select mt-1 w-full">
        <option value="">— Pilih tombol —</option>
        @foreach ($triggerOptions as $option)
            <option
                value="{{ $option['key'] }}"
                @selected(old('trigger_key', $template?->trigger_key) === $option['key'])
            >{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <x-admin-field-label for="{{ $messageId }}">Pesan WhatsApp</x-admin-field-label>
    <textarea
        id="{{ $messageId }}"
        name="message"
        rows="4"
        required
        placeholder="Halo Admin, ada jemaat yang mendaftar baptis. Silakan masuk ke dashboard dan memeriksa datanya."
        class="admin-list-toolbar__input mt-1 w-full"
    >{{ old('message', $template?->message) }}</textarea>
    <p class="mt-1 text-xs text-slate-500" data-wa-placeholder-hint>
        @if ($placeholderFields !== [])
            Variabel dari input form:
            @foreach ($placeholderFields as $fieldName)
                <span class="font-mono text-slate-400">{{ '{'.$fieldName.'}' }}</span>@if (! $loop->last), @endif
            @endforeach
        @else
            Pilih tombol pemicu — variabel mengikuti atribut <span class="font-mono text-slate-400">name</span> pada input form (contoh: {nama_lengkap}).
        @endif
    </p>
</div>
