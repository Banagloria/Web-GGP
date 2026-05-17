<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\CmsPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $cms = CmsPageService::merged('kontak');

        return view('public.contact', compact('cms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cms = CmsPageService::merged('kontak');
        $fields = $cms['form_fields'] ?? [];

        $rules = [];
        foreach ($fields as $f) {
            $name = $f['name'] ?? null;
            if (! is_string($name) || $name === '') {
                continue;
            }
            $max = (int) ($f['max'] ?? 255);
            $req = true;
            $type = $f['type'] ?? 'text';
            $base = $req ? 'required' : 'nullable';
            if ($type === 'email') {
                $rules[$name] = [$base, 'email', 'max:'.$max];
            } elseif ($type === 'number') {
                $rules[$name] = [$base, 'numeric', 'max:'.$max];
            } elseif ($type === 'textarea') {
                $rules[$name] = [$base, 'string', 'max:'.$max];
            } else {
                $rules[$name] = [$base, 'string', 'max:'.$max];
            }
        }

        if ($rules === []) {
            return back()->withErrors(['form' => 'Formulir belum dikonfigurasi.']);
        }

        $values = $request->validate($rules);

        $nameVal = $values['name'] ?? '';
        if ($nameVal === '' || ! is_string($nameVal)) {
            $nameVal = collect($values)->first(fn ($v) => is_string($v) && $v !== '') ?: '—';
        }
        $nameVal = mb_substr((string) $nameVal, 0, 255);

        $messageVal = $values['message'] ?? null;
        if (! is_string($messageVal) || $messageVal === '') {
            $messageVal = json_encode($values, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }

        Contact::query()->create([
            'name' => $nameVal,
            'email' => isset($values['email']) && is_string($values['email']) ? $values['email'] : null,
            'phone' => isset($values['phone']) && is_string($values['phone']) ? $values['phone'] : null,
            'subject' => isset($values['subject']) && is_string($values['subject']) ? $values['subject'] : null,
            'message' => $messageVal,
            'extra' => $values,
        ]);

        $msg = $cms['success_message'] ?? 'Pesan Anda telah terkirim. Tuhan memberkati.';

        return back()->with('status', $msg);
    }
}
