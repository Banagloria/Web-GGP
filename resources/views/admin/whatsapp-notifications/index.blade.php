@extends('layouts.admin')

@section('title', 'Notifikasi WhatsApp')

@section('content')

    <div class="w-full min-w-0 max-w-full">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Notifikasi WhatsApp</h1>
                <p class="mt-1 text-sm text-slate-400">/notifikasi-whatsapp · integrasi WAHA</p>
            </div>
            @include('admin.partials.btn', [
                'href' => route('dashboard.setting.index'),
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-arrow-left',
                'label' => 'Kembali',
                'size' => 'sm',
                'extraClass' => 'w-full sm:w-auto',
            ])
        </div>

        @if ($errors->has('whatsapp'))
            <p class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first('whatsapp') }}</p>
        @endif

        <div class="wa-notif-tabs public-card-hover rounded-xl border border-white/10 bg-church-card/80 p-4 sm:p-5" data-wa-notif-tabs>
            <div class="mb-5 flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Tab notifikasi WhatsApp">
                @foreach (['config' => 'Config', 'pesan' => 'Pesan', 'kontak' => 'Kontak', 'broadcast' => 'Broadcast'] as $tabKey => $tabLabel)
                    <button
                        type="button"
                        class="wa-notif-tab beranda-section-tab shrink-0 rounded-xl border border-white/10 bg-church-surface/70 px-4 py-2.5 text-sm font-medium text-slate-200 transition {{ $activeTab === $tabKey ? 'border-church-gold/45 bg-church-gold/10 text-church-gold ring-1 ring-church-gold/25' : '' }}"
                        data-wa-tab="{{ $tabKey }}"
                        role="tab"
                        aria-selected="{{ $activeTab === $tabKey ? 'true' : 'false' }}"
                    >{{ $tabLabel }}</button>
                @endforeach
            </div>

            {{-- Tab Config --}}
            <section class="wa-notif-panel {{ $activeTab === 'config' ? '' : 'hidden' }}" data-wa-panel="config" role="tabpanel">
                <div class="mb-6 grid gap-4 rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</p>
                        <p class="wa-waha-status {{ $config->is_connected ? 'wa-waha-status--connected' : 'wa-waha-status--disconnected' }}">
                            <i class="fa-solid fa-circle text-[0.5rem]" aria-hidden="true"></i>
                            {{ $config->is_connected ? 'Terhubung' : 'Tidak terhubung' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Terakhir terhubung</p>
                        <p class="mt-2 text-sm text-church-fg">
                            {{ $config->last_connected_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '—' }}
                        </p>
                    </div>
                </div>

                <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.config') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-admin-field-label>Host WAHA</x-admin-field-label>
                        <input name="host" value="{{ old('host', $config->host) }}" required placeholder="https://waha.example.com" class="admin-list-toolbar__input mt-1 w-full">
                    </div>
                    <div>
                        <x-admin-field-label>API Key</x-admin-field-label>
                        <input
                            name="api_key"
                            type="password"
                            value="{{ old('api_key', $config->api_key) }}"
                            autocomplete="new-password"
                            spellcheck="false"
                            placeholder="{{ filled(old('api_key', $config->api_key)) ? '' : 'API key WAHA' }}"
                            class="admin-list-toolbar__input mt-1 w-full font-mono text-sm"
                        >
                    </div>
                    <div>
                        <x-admin-field-label>Session</x-admin-field-label>
                        <input name="session" value="{{ old('session', $config->session ?: 'default') }}" required class="admin-list-toolbar__input mt-1 w-full">
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        @include('admin.partials.btn', [
                            'type' => 'submit',
                            'variant' => 'primary',
                            'icon' => 'fa-solid fa-floppy-disk',
                            'label' => 'Simpan',
                            'size' => 'sm',
                            'extraClass' => 'w-full sm:w-auto',
                        ])
                    </div>
                </form>
            </section>

            {{-- Tab Pesan --}}
            <section class="wa-notif-panel {{ $activeTab === 'pesan' ? '' : 'hidden' }}" data-wa-panel="pesan" role="tabpanel">
                @php
                    $showAddMessagePanel = old('_wa_message_form') === 'add';
                @endphp

                <div class="mb-6" data-wa-add-message>
                    <div class="mb-4">
                        @include('admin.partials.btn', [
                            'type' => 'button',
                            'variant' => 'primary',
                            'icon' => 'fa-solid fa-plus',
                            'label' => 'Tambah pesan',
                            'size' => 'sm',
                            'extraClass' => 'w-full sm:w-auto wa-add-message-open',
                        ])
                    </div>

                    <div
                        data-wa-add-message-panel
                        class="rounded-xl border border-white/10 bg-church-surface/25 p-4 sm:p-5 {{ $showAddMessagePanel ? '' : 'hidden' }}"
                    >
                        <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.messages.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="_wa_message_form" value="add">
                            @include('admin.whatsapp-notifications.partials.message-fields', [
                                'triggerOptions' => $triggerOptions,
                                'triggerPlaceholders' => $triggerPlaceholders,
                                'prefix' => 'new',
                            ])
                            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                                @include('admin.partials.btn', [
                                    'type' => 'submit',
                                    'variant' => 'primary',
                                    'icon' => 'fa-solid fa-plus',
                                    'label' => 'Tambah',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto',
                                ])
                                @include('admin.partials.btn', [
                                    'type' => 'button',
                                    'variant' => 'secondary',
                                    'icon' => 'fa-solid fa-xmark',
                                    'label' => 'Batal',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto wa-add-message-close',
                                ])
                            </div>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    @forelse ($templates as $template)
                        <article class="rounded-xl border border-white/10 bg-church-surface/25 p-4 sm:p-5">
                            <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.messages.update', $template) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                @include('admin.whatsapp-notifications.partials.message-fields', [
                                    'triggerOptions' => $triggerOptions,
                                    'triggerPlaceholders' => $triggerPlaceholders,
                                    'prefix' => 'tpl_'.$template->id,
                                    'template' => $template,
                                ])
                                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                                    @include('admin.partials.btn', [
                                        'type' => 'submit',
                                        'variant' => 'primary',
                                        'icon' => 'fa-solid fa-floppy-disk',
                                        'label' => 'Simpan',
                                        'size' => 'sm',
                                        'extraClass' => 'w-full sm:w-auto',
                                    ])
                                </div>
                            </form>
                            <div class="mt-3 flex flex-col gap-2 border-t border-white/10 pt-3 sm:flex-row sm:flex-wrap">
                                <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.messages.test', $template) }}" class="inline w-full sm:w-auto">
                                    @csrf
                                    @include('admin.partials.btn', [
                                        'type' => 'submit',
                                        'variant' => 'secondary',
                                        'icon' => 'fa-brands fa-whatsapp',
                                        'label' => 'Test chat',
                                        'size' => 'sm',
                                        'extraClass' => 'w-full sm:w-auto',
                                    ])
                                </form>
                                <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.messages.destroy', $template) }}" class="inline w-full sm:w-auto">
                                    @csrf
                                    @method('DELETE')
                                    @include('admin.partials.btn', [
                                        'type' => 'submit',
                                        'variant' => 'danger',
                                        'icon' => 'fa-solid fa-trash',
                                        'label' => 'Hapus',
                                        'size' => 'sm',
                                        'extraClass' => 'w-full sm:w-auto',
                                        'confirmSubmit' => true,
                                        'confirmVariant' => 'delete',
                                        'confirmTitle' => 'Hapus kotak pesan?',
                                        'confirmMessage' => 'Kotak pesan ini akan dihapus permanen.',
                                        'confirmLabel' => 'Ya, hapus',
                                    ])
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-lg border border-dashed border-white/15 px-4 py-8 text-center text-sm text-slate-400">Belum ada kotak pesan.</p>
                    @endforelse
                </div>
            </section>

            {{-- Tab Kontak --}}
            <section class="wa-notif-panel {{ $activeTab === 'kontak' ? '' : 'hidden' }}" data-wa-panel="kontak" role="tabpanel">
                @php
                    $showAddContactPanel = old('_wa_contact_form') === 'add'
                        || ($activeTab === 'kontak' && $errors->hasAny(['user_id', 'whatsapp']));
                @endphp

                <div class="mb-6" data-wa-add-contact>
                    <div class="mb-4">
                        @include('admin.partials.btn', [
                            'type' => 'button',
                            'variant' => 'primary',
                            'icon' => 'fa-solid fa-plus',
                            'label' => 'Tambah Kontak',
                            'size' => 'sm',
                            'extraClass' => 'w-full sm:w-auto wa-add-contact-open',
                        ])
                    </div>

                    <div
                        data-wa-add-contact-panel
                        class="rounded-xl border border-white/10 bg-church-surface/25 p-4 sm:p-5 {{ $showAddContactPanel ? '' : 'hidden' }}"
                    >
                        <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.contacts') }}" class="space-y-5">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="_wa_contact_form" value="add">
                            <div>
                                <x-admin-field-label for="wa-contact-user">Pilih akun penerima</x-admin-field-label>
                        <select id="wa-contact-user" name="user_id" required class="admin-list-toolbar__select mt-1 w-full">
                            <option value="">— Pilih akun —</option>
                            @foreach ($accountOptions as $user)
                                <option value="{{ $user->id }}" @selected((string) old('user_id', $contactFormUserId ?? '') === (string) $user->id)>
                                    {{ $user->name }} - {{ $user->phone }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-400">Data akun dari menu Kelola akun. Nomor 08xxx otomatis menjadi 628xxx@c.us.</p>
                    </div>

                    <fieldset class="space-y-3 rounded-xl border border-white/10 bg-church-surface/25 p-4">
                        <legend class="px-1 text-sm font-semibold text-church-fg">Form yang diterima notifikasi</legend>
                        <p class="text-xs text-slate-400">Centang tombol kirim form publik yang akan mengirim WhatsApp ke akun terpilih.</p>
                        <div class="space-y-2">
                            @foreach ($triggerOptions as $option)
                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-white/10 bg-church-surface/40 px-3 py-2.5 text-sm text-slate-200 transition hover:border-church-gold/25">
                                    <input
                                        type="checkbox"
                                        name="trigger_keys[]"
                                        value="{{ $option['key'] }}"
                                        class="mt-0.5 size-4 shrink-0 rounded border-white/25 bg-church-surface accent-church-gold"
                                        @checked(in_array($option['key'], $contactFormTriggerKeys ?? [], true))
                                    >
                                    <span>{{ $option['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                            </fieldset>
                            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                                @include('admin.partials.btn', [
                                    'type' => 'submit',
                                    'variant' => 'primary',
                                    'icon' => 'fa-solid fa-floppy-disk',
                                    'label' => 'Simpan kontak',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto',
                                ])
                                @include('admin.partials.btn', [
                                    'type' => 'button',
                                    'variant' => 'secondary',
                                    'icon' => 'fa-solid fa-xmark',
                                    'label' => 'Batal',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto wa-add-contact-close',
                                ])
                            </div>
                        </form>
                    </div>
                </div>

                <div class="admin-data-table-wrap mt-6 overflow-x-auto rounded-xl border border-white/10 bg-church-card">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-church-navy-mid text-left text-white">
                                <th class="px-4 py-3 font-semibold">Nama</th>
                                <th class="px-4 py-3 font-semibold">HP</th>
                                <th class="px-4 py-3 font-semibold">Form diterima</th>
                                <th class="px-4 py-3 font-semibold">Chat ID</th>
                                <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recipients as $recipient)
                                <tr class="border-t border-white/10 {{ $loop->even ? 'bg-admin-surface-zebra' : '' }}">
                                    <td class="px-4 py-3 font-medium">{{ $recipient->user?->name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $recipient->user?->phone ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($recipient->triggers->isEmpty())
                                            <span class="text-slate-500">—</span>
                                        @else
                                            <ul class="space-y-1 text-xs text-slate-300">
                                                @foreach ($recipient->triggers as $trigger)
                                                    <li>{{ \App\Services\WhatsAppTriggerCatalog::labelForKey($trigger->trigger_key) }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-church-gold">{{ $recipient->chat_id }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.contacts.destroy', $recipient) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            @include('admin.partials.btn', [
                                                'type' => 'submit',
                                                'variant' => 'danger',
                                                'icon' => 'fa-solid fa-trash',
                                                'label' => 'Hapus',
                                                'size' => 'sm',
                                                'extraClass' => 'w-full sm:w-auto',
                                                'confirmSubmit' => true,
                                                'confirmVariant' => 'delete',
                                                'confirmTitle' => 'Hapus kontak penerima?',
                                                'confirmMessage' => 'Akun ini tidak lagi menerima notifikasi WhatsApp.',
                                                'confirmLabel' => 'Ya, hapus',
                                            ])
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada kontak penerima.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Tab Broadcast --}}
            <section class="wa-notif-panel {{ $activeTab === 'broadcast' ? '' : 'hidden' }}" data-wa-panel="broadcast" role="tabpanel">
                <div class="mb-6" data-wa-add-broadcast>
                    <div class="mb-4">
                        @include('admin.partials.btn', [
                            'type' => 'button',
                            'variant' => 'primary',
                            'icon' => 'fa-solid fa-plus',
                            'label' => 'Tambah broadcast',
                            'size' => 'sm',
                            'extraClass' => 'w-full sm:w-auto wa-add-broadcast-open',
                        ])
                    </div>

                    <div
                        data-wa-add-broadcast-panel
                        class="rounded-xl border border-white/10 bg-church-surface/25 p-4 sm:p-5 {{ ($showAddBroadcastPanel ?? false) ? '' : 'hidden' }}"
                    >
                        <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.broadcasts.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="_wa_broadcast_form" value="add">
                            @include('admin.whatsapp-notifications.partials.broadcast-fields', [
                                'broadcastTriggerOptions' => $broadcastTriggerOptions,
                                'broadcastAudienceOptions' => $broadcastAudienceOptions,
                                'broadcastPlaceholderMap' => $broadcastPlaceholderMap,
                                'broadcastRecipientOptions' => $broadcastRecipientOptions,
                            ])
                            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                                @include('admin.partials.btn', [
                                    'type' => 'submit',
                                    'variant' => 'primary',
                                    'icon' => 'fa-solid fa-floppy-disk',
                                    'label' => 'Simpan',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto',
                                ])
                                @include('admin.partials.btn', [
                                    'type' => 'button',
                                    'variant' => 'secondary',
                                    'icon' => 'fa-solid fa-xmark',
                                    'label' => 'Batal',
                                    'size' => 'sm',
                                    'extraClass' => 'w-full sm:w-auto wa-add-broadcast-close',
                                ])
                            </div>
                        </form>
                    </div>
                </div>

                <div class="admin-data-table-wrap mt-6 overflow-x-auto rounded-xl border border-white/10 bg-church-card">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-church-navy-mid text-left text-white">
                                <th class="px-4 py-3 font-semibold">Trigger</th>
                                <th class="px-4 py-3 font-semibold">Data penerima</th>
                                <th class="px-4 py-3 font-semibold">Pesan</th>
                                <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($broadcasts as $broadcast)
                                <tr class="border-t border-white/10 {{ $loop->even ? 'bg-admin-surface-zebra' : '' }}">
                                    <td class="px-4 py-3">{{ \App\Services\WhatsAppBroadcastCatalog::triggerLabel($broadcast->trigger_key) }}</td>
                                    <td class="px-4 py-3">
                                        {{ \App\Services\WhatsAppBroadcastCatalog::audienceLabel($broadcast->audience) }}
                                        @if ($broadcast->audience === 'one_by_one')
                                            @php
                                                $oneByOneRows = $broadcast->templateUsers->isNotEmpty()
                                                    ? $broadcast->templateUsers
                                                    : collect();
                                            @endphp
                                            @if ($oneByOneRows->isNotEmpty())
                                                <ul class="mt-1 space-y-0.5 text-xs text-slate-400">
                                                    @foreach ($oneByOneRows as $row)
                                                        <li>{{ \App\Services\WhatsAppBroadcastRecipientOptions::displayLabel($row) }}</li>
                                                    @endforeach
                                                </ul>
                                            @elseif ($broadcast->users->isNotEmpty())
                                                <ul class="mt-1 space-y-0.5 text-xs text-slate-400">
                                                    @foreach ($broadcast->users as $user)
                                                        <li>{{ $user->name }} - {{ $user->phone }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="max-w-xs px-4 py-3 text-slate-300">{{ \Illuminate\Support\Str::limit($broadcast->message, 120) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="post" action="{{ route('dashboard.setting.notifikasi-whatsapp.broadcasts.destroy', $broadcast) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            @include('admin.partials.btn', [
                                                'type' => 'submit',
                                                'variant' => 'danger',
                                                'icon' => 'fa-solid fa-trash',
                                                'label' => 'Hapus',
                                                'size' => 'sm',
                                                'extraClass' => 'w-full sm:w-auto',
                                                'confirmSubmit' => true,
                                                'confirmVariant' => 'delete',
                                                'confirmTitle' => 'Hapus broadcast?',
                                                'confirmMessage' => 'Konfigurasi broadcast ini akan dihapus.',
                                                'confirmLabel' => 'Ya, hapus',
                                            ])
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada broadcast.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                var triggerPlaceholders = @json($triggerPlaceholders);
                var broadcastPlaceholderMap = @json($broadcastPlaceholderMap ?? []);
                var recipientTriggerMap = @json($recipientTriggerMap ?? []);

                var root = document.querySelector('[data-wa-notif-tabs]');
                if (!root) return;

                function formatPlaceholderHint(fields) {
                    if (!fields || !fields.length) {
                        return 'Pilih tombol pemicu — variabel mengikuti atribut <span class="font-mono text-slate-400">name</span> pada input form (contoh: {nama_lengkap}).';
                    }
                    var parts = fields.map(function (name) {
                        return '<span class="font-mono text-slate-400">{' + name + '}</span>';
                    });
                    return 'Variabel dari input form: ' + parts.join(', ');
                }

                function updatePlaceholderHint(select) {
                    if (!select) {
                        return;
                    }
                    var form = select.closest('form');
                    if (!form) {
                        return;
                    }
                    var hint = form.querySelector('[data-wa-placeholder-hint]');
                    if (!hint) {
                        return;
                    }
                    var fields = triggerPlaceholders[select.value] || [];
                    hint.innerHTML = formatPlaceholderHint(fields);
                }

                root.querySelectorAll('select[name="trigger_key"]').forEach(function (select) {
                    select.addEventListener('change', function () {
                        updatePlaceholderHint(select);
                    });
                    updatePlaceholderHint(select);
                });

                var contactUserSelect = document.getElementById('wa-contact-user');
                if (contactUserSelect) {
                    contactUserSelect.addEventListener('change', function () {
                        var keys = recipientTriggerMap[contactUserSelect.value] || [];
                        document.querySelectorAll('input[name="trigger_keys[]"]').forEach(function (checkbox) {
                            checkbox.checked = keys.indexOf(checkbox.value) !== -1;
                        });
                    });
                }

                var tabs = root.querySelectorAll('[data-wa-tab]');
                var panels = root.querySelectorAll('[data-wa-panel]');

                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function () {
                        var key = tab.getAttribute('data-wa-tab');
                        tabs.forEach(function (t) {
                            var active = t === tab;
                            t.setAttribute('aria-selected', active ? 'true' : 'false');
                            t.classList.toggle('border-church-gold/45', active);
                            t.classList.toggle('bg-church-gold/10', active);
                            t.classList.toggle('text-church-gold', active);
                            t.classList.toggle('ring-1', active);
                            t.classList.toggle('ring-church-gold/25', active);
                        });
                        panels.forEach(function (panel) {
                            panel.classList.toggle('hidden', panel.getAttribute('data-wa-panel') !== key);
                        });
                        var url = new URL(window.location.href);
                        url.searchParams.set('tab', key);
                        window.history.replaceState({}, '', url);
                    });
                });

                var addMessageRoot = root.querySelector('[data-wa-add-message]');
                if (addMessageRoot) {
                    var addPanel = addMessageRoot.querySelector('[data-wa-add-message-panel]');
                    var openBtn = addMessageRoot.querySelector('.wa-add-message-open');
                    var closeBtn = addMessageRoot.querySelector('.wa-add-message-close');

                    function setAddMessageOpen(open) {
                        if (!addPanel) {
                            return;
                        }
                        addPanel.classList.toggle('hidden', !open);
                        if (open) {
                            var firstInput = addPanel.querySelector('input:not([type="hidden"]), textarea, select');
                            if (firstInput) {
                                firstInput.focus();
                            }
                        }
                    }

                    if (openBtn) {
                        openBtn.addEventListener('click', function () {
                            setAddMessageOpen(true);
                        });
                    }
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function () {
                            setAddMessageOpen(false);
                        });
                    }
                }

                var addContactRoot = root.querySelector('[data-wa-add-contact]');
                if (addContactRoot) {
                    var addContactPanel = addContactRoot.querySelector('[data-wa-add-contact-panel]');
                    var openContactBtn = addContactRoot.querySelector('.wa-add-contact-open');
                    var closeContactBtn = addContactRoot.querySelector('.wa-add-contact-close');

                    function setAddContactOpen(open) {
                        if (!addContactPanel) {
                            return;
                        }
                        addContactPanel.classList.toggle('hidden', !open);
                        if (open) {
                            var firstInput = addContactPanel.querySelector('select, input:not([type="hidden"])');
                            if (firstInput) {
                                firstInput.focus();
                            }
                        }
                    }

                    if (openContactBtn) {
                        openContactBtn.addEventListener('click', function () {
                            setAddContactOpen(true);
                        });
                    }
                    if (closeContactBtn) {
                        closeContactBtn.addEventListener('click', function () {
                            setAddContactOpen(false);
                        });
                    }
                }

                function formatBroadcastPlaceholderHint(fields) {
                    if (!fields || !fields.length) {
                        return 'Pilih trigger — variabel mengikuti field form saat create.';
                    }
                    var parts = fields.map(function (name) {
                        return '<span class="font-mono text-slate-400">{' + name + '}</span>';
                    });
                    return 'Variabel dari input form: ' + parts.join(', ');
                }

                function updateBroadcastPlaceholderHint(select) {
                    if (!select) {
                        return;
                    }
                    var form = select.closest('form');
                    if (!form) {
                        return;
                    }
                    var hint = form.querySelector('[data-wa-broadcast-placeholder-hint]');
                    if (!hint) {
                        return;
                    }
                    hint.innerHTML = formatBroadcastPlaceholderHint(broadcastPlaceholderMap[select.value] || []);
                }

                var broadcastTriggerSelect = document.getElementById('wa-broadcast-trigger');
                if (broadcastTriggerSelect) {
                    broadcastTriggerSelect.addEventListener('change', function () {
                        updateBroadcastPlaceholderHint(broadcastTriggerSelect);
                    });
                    updateBroadcastPlaceholderHint(broadcastTriggerSelect);
                }

                var broadcastAudienceSelect = document.getElementById('wa-broadcast-audience');
                var broadcastUsersWrap = document.getElementById('wa-broadcast-users-wrap');
                var broadcastRecipientSelect = document.getElementById('wa-broadcast-recipient');
                if (broadcastAudienceSelect && broadcastUsersWrap) {
                    var syncBroadcastRecipientField = function () {
                        var isOneByOne = broadcastAudienceSelect.value === 'one_by_one';
                        broadcastUsersWrap.classList.toggle('hidden', !isOneByOne);
                        if (broadcastRecipientSelect) {
                            broadcastRecipientSelect.required = isOneByOne;
                        }
                    };
                    broadcastAudienceSelect.addEventListener('change', syncBroadcastRecipientField);
                    syncBroadcastRecipientField();
                }

                var addBroadcastRoot = root.querySelector('[data-wa-add-broadcast]');
                if (addBroadcastRoot) {
                    var addBroadcastPanel = addBroadcastRoot.querySelector('[data-wa-add-broadcast-panel]');
                    var openBroadcastBtn = addBroadcastRoot.querySelector('.wa-add-broadcast-open');
                    var closeBroadcastBtn = addBroadcastRoot.querySelector('.wa-add-broadcast-close');

                    function setAddBroadcastOpen(open) {
                        if (!addBroadcastPanel) {
                            return;
                        }
                        addBroadcastPanel.classList.toggle('hidden', !open);
                        if (open) {
                            var firstInput = addBroadcastPanel.querySelector('select, textarea, input:not([type="hidden"])');
                            if (firstInput) {
                                firstInput.focus();
                            }
                        }
                    }

                    if (openBroadcastBtn) {
                        openBroadcastBtn.addEventListener('click', function () {
                            setAddBroadcastOpen(true);
                        });
                    }
                    if (closeBroadcastBtn) {
                        closeBroadcastBtn.addEventListener('click', function () {
                            setAddBroadcastOpen(false);
                        });
                    }
                }
            })();
        </script>
    @endpush
@endsection
