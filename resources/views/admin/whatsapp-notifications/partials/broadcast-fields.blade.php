@php
    $broadcastTriggerOptions = $broadcastTriggerOptions ?? [];
    $broadcastAudienceOptions = $broadcastAudienceOptions ?? [];
    $broadcastPlaceholderMap = $broadcastPlaceholderMap ?? [];
    $broadcastRecipientOptions = $broadcastRecipientOptions ?? [];
    $broadcast = $broadcast ?? null;
    $selectedTrigger = old('trigger_key', $broadcast?->trigger_key);
    $selectedAudience = old('audience', $broadcast?->audience);
    $placeholderFields = $selectedTrigger ? ($broadcastPlaceholderMap[$selectedTrigger] ?? []) : [];
    $selectedRecipientKey = old('recipient_key', '');
    if ($selectedRecipientKey === '' && $broadcast && $broadcast->audience === 'one_by_one') {
        $firstRecipient = $broadcast->templateUsers->first();
        if ($firstRecipient !== null) {
            $selectedRecipientKey = \App\Services\WhatsAppBroadcastRecipientOptions::keyFromTemplateUser($firstRecipient) ?? '';
            if ($selectedRecipientKey === '' && $firstRecipient->user_id !== null) {
                $selectedRecipientKey = \App\Services\WhatsAppBroadcastRecipientOptions::userKey((int) $firstRecipient->user_id);
            }
        } elseif ($broadcast->users->isNotEmpty()) {
            $selectedRecipientKey = \App\Services\WhatsAppBroadcastRecipientOptions::userKey((int) $broadcast->users->first()->id);
        }
    }
    $recipientGroups = collect($broadcastRecipientOptions)->groupBy('group');
@endphp

<div>
    <x-admin-field-label for="wa-broadcast-trigger">Trigger</x-admin-field-label>
    <select id="wa-broadcast-trigger" name="trigger_key" required class="admin-list-toolbar__select mt-1 w-full">
        <option value="">— Pilih trigger —</option>
        @foreach ($broadcastTriggerOptions as $option)
            <option value="{{ $option['key'] }}" @selected($selectedTrigger === $option['key'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <x-admin-field-label for="wa-broadcast-audience">Data penerima</x-admin-field-label>
    <select id="wa-broadcast-audience" name="audience" required class="admin-list-toolbar__select mt-1 w-full">
        <option value="">— Pilih data —</option>
        @foreach ($broadcastAudienceOptions as $option)
            <option value="{{ $option['key'] }}" @selected($selectedAudience === $option['key'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>

<div
    id="wa-broadcast-users-wrap"
    class="mt-4 {{ $selectedAudience === 'one_by_one' ? '' : 'hidden' }}"
>
    <x-admin-field-label for="wa-broadcast-recipient">Pilih penerima (one by one)</x-admin-field-label>
    <select id="wa-broadcast-recipient" name="recipient_key" class="admin-list-toolbar__select mt-1 w-full" @if($selectedAudience === 'one_by_one') required @endif>
        <option value="">— Pilih penerima —</option>
        @foreach ($recipientGroups as $group => $items)
            <optgroup label="{{ $group }}">
                @foreach ($items as $option)
                    <option value="{{ $option['key'] }}" @selected($selectedRecipientKey === $option['key'])>{{ $option['label'] }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    <p class="mt-2 text-xs text-slate-400">Semua akun (jemaat &amp; admin) serta data jemaat terdaftar yang memiliki nomor HP.</p>
</div>

<div class="mt-4">
    <x-admin-field-label for="wa-broadcast-message">Pesan WhatsApp</x-admin-field-label>
    <textarea
        id="wa-broadcast-message"
        name="message"
        rows="4"
        required
        placeholder="Contoh: Pengumuman baru: {title}"
        class="admin-list-toolbar__input mt-1 w-full"
    >{{ old('message', $broadcast?->message) }}</textarea>
    <p class="mt-1 text-xs text-slate-500" data-wa-broadcast-placeholder-hint>
        @if ($placeholderFields !== [])
            Variabel dari input form:
            @foreach ($placeholderFields as $fieldName)
                <span class="font-mono text-slate-400">{{ '{'.$fieldName.'}' }}</span>@if (! $loop->last), @endif
            @endforeach
        @else
            Pilih trigger — variabel mengikuti atribut <span class="font-mono text-slate-400">name</span> pada form create.
        @endif
    </p>
</div>
