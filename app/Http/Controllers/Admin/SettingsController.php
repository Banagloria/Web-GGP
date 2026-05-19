<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminModal;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\PublicCmsUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    use RespondsToAdminModal;

    public function edit(): View
    {
        $settings = SiteSetting::valuesForAdminForm();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse|Response
    {
        $data = $request->validate([
            'church_name_line1' => ['nullable', 'string', 'max:255'],
            'church_name_line2' => ['nullable', 'string', 'max:255'],
            'church_phone' => ['nullable', 'string', 'max:100'],
            'church_email' => ['nullable', 'string', 'max:255'],
            'church_address' => ['nullable', 'string', 'max:500'],
            'footer_whatsapp_note' => ['nullable', 'string', 'max:500'],
            'social_facebook' => ['nullable', 'string', 'max:500'],
            'social_twitter' => ['nullable', 'string', 'max:500'],
            'social_instagram' => ['nullable', 'string', 'max:500'],
            'social_youtube' => ['nullable', 'string', 'max:500'],
            'hero_image_url' => ['nullable', 'string', 'max:2000'],
            'hero_image_file' => ['nullable', 'image', 'max:5120'],
            'hero_image_delete' => ['nullable', 'in:1'],
            'hero_image_url_previous' => ['nullable', 'string', 'max:2000'],
            'hero_script_top' => ['nullable', 'string', 'max:255'],
            'hero_title_gold' => ['nullable', 'string', 'max:255'],
            'hero_title_white' => ['nullable', 'string', 'max:255'],
            'hero_script_bottom' => ['nullable', 'string', 'max:255'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_body' => ['nullable', 'string', 'max:10000'],
        ]);

        $deleteHero = (($data['hero_image_delete'] ?? '') === '1');
        $previousHero = trim((string) ($data['hero_image_url_previous'] ?? ''));
        unset($data['hero_image_delete'], $data['hero_image_url_previous']);

        if ($request->hasFile('hero_image_file')) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            $path = $request->file('hero_image_file')->store('cms/hero-settings', 'public');
            $data['hero_image_url'] = Storage::url($path);
        } elseif ($deleteHero) {
            PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            $data['hero_image_url'] = '';
        } else {
            $newUrl = trim((string) ($data['hero_image_url'] ?? ''));
            if ($newUrl === '' && $previousHero !== '') {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            } elseif (
                $previousHero !== ''
                && $newUrl !== $previousHero
                && PublicCmsUrl::publicStorageRelativePath($previousHero) !== null
            ) {
                PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousHero);
            }
        }

        unset($data['hero_image_file']);

        foreach ($data as $key => $value) {
            SiteSetting::put($key, $value);
        }

        return $this->adminModalFinished(
            $request,
            'Pengaturan disimpan.',
            redirect()->route('dashboard.setting.index')->with('status', 'Pengaturan disimpan.')
        );
    }
}
