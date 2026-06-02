<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WhatsappBroadcastTemplate;
use App\Models\WhatsappMessageTemplate;
use App\Models\WhatsappNotificationRecipient;
use App\Models\WhatsappWahaConfig;
use App\Services\WahaApiService;
use App\Models\WhatsappBroadcastTemplateUser;
use App\Services\WhatsAppBroadcastCatalog;
use App\Services\WhatsAppBroadcastRecipientOptions;
use App\Services\WhatsAppNotificationDispatcher;
use App\Services\WhatsAppTriggerCatalog;
use App\Support\WhatsAppChatId;
use App\Support\WhatsAppNotificationSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class WhatsAppNotificationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $config = WhatsappWahaConfig::current();
        $templates = WhatsappMessageTemplate::query()->orderBy('sort_order')->orderBy('id')->get();
        $triggerOptions = WhatsAppTriggerCatalog::options();
        $triggerPlaceholders = WhatsAppTriggerCatalog::placeholderMap();
        $recipients = WhatsappNotificationRecipient::query()->with(['user', 'triggers'])->orderBy('id')->get();

        $accountOptions = User::query()
            ->when(User::phoneColumnReady(), fn ($q) => $q->whereNotNull('phone')->where('phone', '!=', ''))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'role']);

        $broadcastRecipientOptions = WhatsAppBroadcastRecipientOptions::options();

        $broadcasts = WhatsAppNotificationSupport::broadcastReady()
            ? WhatsappBroadcastTemplate::query()->with(['users', 'templateUsers.user'])->orderBy('sort_order')->orderBy('id')->get()
            : collect();
        $broadcastTriggerOptions = WhatsAppBroadcastCatalog::triggerOptions();
        $broadcastAudienceOptions = WhatsAppBroadcastCatalog::audienceOptions();
        $broadcastPlaceholderMap = WhatsAppBroadcastCatalog::placeholderMap();

        $activeTab = in_array($request->query('tab'), ['config', 'pesan', 'kontak', 'broadcast'], true)
            ? $request->query('tab')
            : 'config';

        if (old('_wa_test_template_id')) {
            $activeTab = 'pesan';
        }

        $waConnection = null;
        if ($activeTab === 'config') {
            $waConnection = WahaApiService::make()->refreshConnectionStatus();
            $config->refresh();
        }

        $contactFormUserId = old('user_id');
        $contactFormTriggerKeys = old('trigger_keys', []);
        if ($contactFormUserId && $contactFormTriggerKeys === []) {
            $prefillRecipient = WhatsappNotificationRecipient::query()
                ->with('triggers')
                ->where('user_id', $contactFormUserId)
                ->first();
            if ($prefillRecipient !== null) {
                $contactFormTriggerKeys = $prefillRecipient->triggers->pluck('trigger_key')->all();
            }
        }

        $recipientTriggerMap = $recipients
            ->mapWithKeys(fn (WhatsappNotificationRecipient $recipient) => [
                (string) $recipient->user_id => $recipient->triggers->pluck('trigger_key')->values()->all(),
            ])
            ->all();

        $validationErrors = $request->session()->get('errors');
        $editingBroadcastId = old('_wa_broadcast_form') === 'edit' ? (int) old('_wa_broadcast_id') : null;
        $showAddBroadcastPanel = old('_wa_broadcast_form') === 'add'
            || ($activeTab === 'broadcast'
                && $editingBroadcastId === null
                && $validationErrors !== null
                && $validationErrors->hasAny(['trigger_key', 'audience', 'message', 'recipient_key', 'user_ids', 'whatsapp']));

        $testMessageContacts = collect(WhatsAppBroadcastRecipientOptions::panelAccountEntries());

        return view('admin.whatsapp-notifications.index', compact(
            'config',
            'waConnection',
            'templates',
            'triggerOptions',
            'triggerPlaceholders',
            'recipients',
            'accountOptions',
            'activeTab',
            'contactFormUserId',
            'contactFormTriggerKeys',
            'recipientTriggerMap',
            'broadcasts',
            'broadcastTriggerOptions',
            'broadcastAudienceOptions',
            'broadcastPlaceholderMap',
            'broadcastRecipientOptions',
            'showAddBroadcastPanel',
            'editingBroadcastId',
            'testMessageContacts',
        ));
    }

    public function updateConfig(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $request->validate([
            'host' => ['required', 'string', 'max:500'],
            'api_key' => ['nullable', 'string', 'max:500'],
            'session' => ['required', 'string', 'max:120'],
        ]);

        $config = WhatsappWahaConfig::current();
        $config->fill([
            'host' => rtrim($validated['host'], '/'),
            'session' => $validated['session'],
        ]);

        if (array_key_exists('api_key', $validated) && filled($validated['api_key'])) {
            $config->api_key = $validated['api_key'];
        }

        $config->save();

        $waha = WahaApiService::make();
        $waha->ensureSessionRunning();
        $connection = $waha->refreshConnectionStatus();

        $connected = (bool) $connection['connected'];
        $apiOk = (bool) ($connection['api_ok'] ?? false);

        if (! $apiOk) {
            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'config'])
                ->with('status', $connection['message'])
                ->with('status_variant', 'error');
        }

        if ($connected) {
            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'config'])
                ->with('status', 'Berhasil terhubung ke WAHA.')
                ->with('status_variant', 'success');
        }

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'config'])
            ->with('status', 'Konfigurasi disimpan. '.$connection['message'])
            ->with('status_variant', 'success');
    }

    public function storeMessage(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $this->validatedMessage($request);
        $maxSort = (int) WhatsappMessageTemplate::query()->max('sort_order');

        WhatsappMessageTemplate::query()->create([
            ...$validated,
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
            ->with('status', 'Kotak pesan ditambahkan.');
    }

    public function updateMessage(Request $request, WhatsappMessageTemplate $template): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $this->validatedMessage($request, $template->id);
        $template->update($validated);

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
            ->with('status', 'Kotak pesan disimpan.');
    }

    public function destroyMessage(WhatsappMessageTemplate $template): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $template->delete();

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
            ->with('status', 'Kotak pesan dihapus.');
    }

    public function testMessage(Request $request, WhatsappMessageTemplate $template): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            '_wa_test_template_id' => ['nullable', 'integer'],
        ], [], [
            'user_id' => 'kontak',
        ]);

        $user = User::query()->panelUsers()->find($validated['user_id']);
        if ($user === null) {
            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
                ->withInput()
                ->withInput($request->only(['user_id', '_wa_test_template_id']))
                ->withErrors(['user_id' => 'Akun tidak ditemukan di Manajemen akun.']);
        }

        $chatId = WhatsAppChatId::fromPhone($user->phone);
        if ($chatId === null) {
            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
                ->withInput()
                ->withInput($request->only(['user_id', '_wa_test_template_id']))
                ->withErrors(['whatsapp' => 'Nomor HP akun tidak valid untuk WhatsApp (gunakan format 08xxx atau 628xxx).']);
        }

        try {
            $waha = WahaApiService::make();
            $status = $waha->refreshConnectionStatus();
            if (! $status['connected']) {
                return redirect()
                    ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
                    ->withInput()
                    ->withInput($request->only(['user_id', '_wa_test_template_id']))
                    ->withErrors(['whatsapp' => 'WAHA belum terhubung. Periksa tab Config.']);
            }

            $text = '[TEST] '.WhatsAppNotificationDispatcher::renderMessage(
                $template->message,
                WhatsAppTriggerCatalog::sampleReplacementsForTrigger($template->trigger_key),
            );

            $waha->sendText($chatId, $text);

            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
                ->with('status', 'Pesan uji terkirim ke '.$user->name.'.');
        } catch (Throwable $e) {
            return redirect()
                ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'pesan'])
                ->withInput()
                ->withInput($request->only(['user_id', '_wa_test_template_id']))
                ->withErrors(['whatsapp' => 'Gagal mengirim uji: '.$e->getMessage()]);
        }
    }

    public function updateContacts(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'trigger_keys' => ['nullable', 'array'],
            'trigger_keys.*' => ['string', 'max:120'],
        ]);

        $user = User::query()->find($validated['user_id']);
        if ($user === null) {
            return back()->withErrors(['user_id' => 'Akun tidak ditemukan.']);
        }

        $chatId = WhatsAppChatId::fromPhone($user->phone);
        if ($chatId === null) {
            return back()
                ->withInput()
                ->withErrors(['whatsapp' => 'Nomor HP akun tidak valid untuk WhatsApp (gunakan format 08xxx atau 628xxx).']);
        }

        $recipient = WhatsappNotificationRecipient::query()->updateOrCreate(
            ['user_id' => $user->id],
            ['chat_id' => $chatId],
        );

        $recipient->triggers()->delete();

        $triggerKeys = collect($validated['trigger_keys'] ?? [])
            ->filter(fn ($key) => is_string($key) && WhatsAppTriggerCatalog::isValidKey($key))
            ->unique()
            ->values();

        foreach ($triggerKeys as $triggerKey) {
            $recipient->triggers()->create(['trigger_key' => $triggerKey]);
        }

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'kontak'])
            ->with('status', 'Kontak penerima disimpan.');
    }

    public function destroyContact(WhatsappNotificationRecipient $recipient): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $recipient->delete();

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'kontak'])
            ->with('status', 'Kontak penerima dihapus.');
    }

    public function storeBroadcast(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $this->validatedBroadcast($request);
        $maxSort = (int) WhatsappBroadcastTemplate::query()->max('sort_order');

        $broadcast = WhatsappBroadcastTemplate::query()->create([
            'trigger_key' => $validated['trigger_key'],
            'audience' => $validated['audience'],
            'message' => $validated['message'],
            'sort_order' => $maxSort + 1,
        ]);

        $this->syncBroadcastRecipient($broadcast, $validated['audience'], $validated['recipient_key'] ?? null);

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'broadcast'])
            ->with('status', 'Broadcast disimpan.');
    }

    public function updateBroadcast(Request $request, WhatsappBroadcastTemplate $broadcast): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $validated = $this->validatedBroadcast($request);

        $broadcast->update([
            'trigger_key' => $validated['trigger_key'],
            'audience' => $validated['audience'],
            'message' => $validated['message'],
        ]);

        $this->syncBroadcastRecipient($broadcast, $validated['audience'], $validated['recipient_key'] ?? null);

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'broadcast'])
            ->with('status', 'Broadcast diperbarui.');
    }

    public function destroyBroadcast(WhatsappBroadcastTemplate $broadcast): RedirectResponse
    {
        if ($redirect = $this->redirectIfTablesMissing()) {
            return $redirect;
        }

        $broadcast->delete();

        return redirect()
            ->route('dashboard.setting.notifikasi-whatsapp.index', ['tab' => 'broadcast'])
            ->with('status', 'Broadcast dihapus.');
    }

    private function redirectIfTablesMissing(): ?RedirectResponse
    {
        if (WhatsAppNotificationSupport::isReady()) {
            return null;
        }

        return redirect()
            ->route('dashboard.setting.index')
            ->withErrors([
                'setting' => 'Tabel notifikasi WhatsApp belum ada. Jalankan di server: php artisan migrate --force atau php artisan church:ensure-whatsapp-notification-tables',
            ]);
    }

    /**
     * @return array{title: string, trigger_key: string, message: string}
     */
    private function validatedMessage(Request $request, ?int $ignoreId = null): array
    {
        $triggerKeys = array_column(WhatsAppTriggerCatalog::options(), 'key');

        $triggerRules = ['required', 'string', 'max:120'];
        if ($ignoreId !== null) {
            $triggerRules[] = Rule::unique('whatsapp_message_templates', 'trigger_key')->ignore($ignoreId);
        } else {
            $triggerRules[] = Rule::in($triggerKeys);
            $triggerRules[] = Rule::unique('whatsapp_message_templates', 'trigger_key');
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'trigger_key' => $triggerRules,
            'message' => ['required', 'string', 'max:5000'],
        ]);
    }

    /**
     * @return array{trigger_key: string, audience: string, message: string, recipient_key?: string}
     */
    private function validatedBroadcast(Request $request): array
    {
        $validated = $request->validate([
            'trigger_key' => ['required', 'string', 'max:120', Rule::in(array_column(WhatsAppBroadcastCatalog::triggerOptions(), 'key'))],
            'audience' => ['required', 'string', 'max:120', Rule::in(WhatsAppBroadcastCatalog::audienceOptionKeys())],
            'message' => ['required', 'string', 'max:5000'],
            'recipient_key' => ['nullable', 'string', 'max:80'],
        ], [], [
            'trigger_key' => 'trigger',
            'audience' => 'data penerima',
            'message' => 'pesan',
            'recipient_key' => 'penerima',
        ]);

        if ($validated['audience'] === WhatsAppBroadcastCatalog::AUDIENCE_ONE_BY_ONE) {
            $recipientKey = trim((string) ($validated['recipient_key'] ?? ''));
            if ($recipientKey === '' || ! in_array($recipientKey, WhatsAppBroadcastRecipientOptions::validKeys(), true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'recipient_key' => 'Pilih penerima untuk mode one by one.',
                ]);
            }
            if (WhatsAppBroadcastRecipientOptions::resolve($recipientKey) === null) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'recipient_key' => 'Data penerima tidak ditemukan atau nomor HP tidak valid.',
                ]);
            }
            $validated['recipient_key'] = $recipientKey;
        } else {
            unset($validated['recipient_key']);
        }

        return $validated;
    }

    private function syncBroadcastRecipient(WhatsappBroadcastTemplate $broadcast, string $audience, ?string $recipientKey): void
    {
        $broadcast->users()->detach();
        WhatsappBroadcastTemplateUser::query()->where('broadcast_template_id', $broadcast->id)->delete();

        if ($audience !== WhatsAppBroadcastCatalog::AUDIENCE_ONE_BY_ONE || $recipientKey === null) {
            return;
        }

        $resolved = WhatsAppBroadcastRecipientOptions::resolve($recipientKey);
        if ($resolved === null) {
            return;
        }

        $chatId = $resolved['chat_id'] ?? null;
        if ($chatId === null || $chatId === '') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'recipient_key' => 'Nomor HP tidak valid untuk WhatsApp (gunakan format 08xxx atau 628xxx).',
            ]);
        }

        if ($resolved['user_id'] === null && ! WhatsAppNotificationSupport::broadcastRecipientColumnsReady()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'recipient_key' => 'Penerima dari data jemaat memerlukan pembaruan database. Jalankan: php artisan migrate --force atau php artisan church:ensure-whatsapp-notification-tables',
            ]);
        }

        $payload = ['broadcast_template_id' => $broadcast->id];
        if (WhatsAppNotificationSupport::broadcastRecipientColumnsReady()) {
            $payload['user_id'] = $resolved['user_id'];
            $payload['recipient_name'] = $resolved['recipient_name'];
            $payload['recipient_phone'] = $resolved['recipient_phone'];
        } elseif ($resolved['user_id'] !== null) {
            $payload['user_id'] = $resolved['user_id'];
        }

        if (WhatsAppNotificationSupport::broadcastChatIdColumnReady()) {
            $payload['chat_id'] = $chatId;
        }

        WhatsappBroadcastTemplateUser::query()->create($payload);

        if ($resolved['user_id'] !== null) {
            $broadcast->users()->sync([$resolved['user_id']]);
        }
    }
}
