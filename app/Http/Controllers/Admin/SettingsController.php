<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Admin hanya mengelola Hero + Portfolio + identitas/SEO minimal.
     * Keys lain (kontak, sosial, CV, timeline) sengaja tidak ditampilkan
     * agar tidak bisa diubah dari panel, tapi tetap terbaca frontend
     * dengan fallback default.
     */
    private array $keys = [
        'site_name', 'site_tagline', 'site_description',
        'logo_image', 'favicon',
        'full_name', 'role', 'bio', 'profile_photo',
        'hero_typed_roles',
        'landing_portfolio_title', 'landing_portfolio_subtitle',
        'ui_hero_tech_label', 'ui_label_view_project',
        'seo_title', 'seo_description',
        'seo_og_image', 'seo_google_analytics',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::where('key', $key)->value('value');
        }

        return view('admin.settings.index', [
            'settings' => $settings,
            'keys' => $this->keys,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'full_name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,ico,webp|max:5120',
            'hero_typed_roles' => 'nullable|string|max:255',
            'landing_portfolio_title' => 'nullable|string|max:255',
            'landing_portfolio_subtitle' => 'nullable|string|max:500',
            'ui_hero_tech_label' => 'nullable|string|max:100',
            'ui_label_view_project' => 'nullable|string|max:100',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_google_analytics' => 'nullable|string|max:500',
            'remove_profile_photo' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
            'remove_seo_og_image' => 'nullable|boolean',
        ]);

        foreach (['site_description'] as $richKey) {
            if (isEmptyRichText($data[$richKey] ?? null)) {
                $data[$richKey] = null;
            }
        }

        foreach (['site_name', 'site_tagline', 'site_description', 'full_name', 'role', 'bio',
            'hero_typed_roles',
            'landing_portfolio_title', 'landing_portfolio_subtitle',
            'ui_hero_tech_label', 'ui_label_view_project',
            'seo_title', 'seo_description', 'seo_google_analytics'] as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? '', 'group' => 'general', 'type' => 'text']
            );
        }

        if ($request->boolean('remove_profile_photo')) {
            deleteFile(Setting::where('key', 'profile_photo')->value('value'));
            Setting::updateOrCreate(['key' => 'profile_photo'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('profile_photo')) {
            deleteFile(Setting::where('key', 'profile_photo')->value('value'));
            // Nama file diseragamkan menjadi heri-profile.* agar URL foto hero
            // stabil; cache-buster (?v=filemtime) dipasang di view hero.
            // JPEG/PNG otomatis dikonversi ke heri-profile.webp (hemat storage).
            $stored = storeImageAsWebp($request->file('profile_photo'), 'settings', 'heri-profile');
            Setting::updateOrCreate(['key' => 'profile_photo'], ['value' => $stored, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_logo_image')) {
            deleteFile(Setting::where('key', 'logo_image')->value('value'));
            Setting::updateOrCreate(['key' => 'logo_image'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('logo_image')) {
            deleteFile(Setting::where('key', 'logo_image')->value('value'));
            $stored = storeImageAsWebp($request->file('logo_image'), 'settings');
            Setting::updateOrCreate(['key' => 'logo_image'], ['value' => $stored, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_favicon')) {
            deleteFile(Setting::where('key', 'favicon')->value('value'));
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('favicon')) {
            deleteFile(Setting::where('key', 'favicon')->value('value'));
            $faviconFile = $request->file('favicon');
            $faviconMime = (string) $faviconFile->getMimeType();
            if (in_array($faviconMime, ['image/x-icon', 'image/vnd.microsoft.icon'], true)
                || strtolower($faviconFile->getClientOriginalExtension()) === 'ico') {
                // .ico tidak bisa dibaca GD: simpan apa adanya.
                $path = $faviconFile->store('settings', 'public');
                $stored = 'storage/'.$path;
            } else {
                $stored = storeImageAsWebp($faviconFile, 'settings');
            }
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $stored, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_seo_og_image')) {
            deleteFile(Setting::where('key', 'seo_og_image')->value('value'));
            Setting::updateOrCreate(['key' => 'seo_og_image'], ['value' => '', 'group' => 'seo', 'type' => 'text']);
        } elseif ($request->hasFile('seo_og_image')) {
            deleteFile(Setting::where('key', 'seo_og_image')->value('value'));
            $stored = storeImageAsWebp($request->file('seo_og_image'), 'settings');
            Setting::updateOrCreate(['key' => 'seo_og_image'], ['value' => $stored, 'group' => 'seo', 'type' => 'text']);
        }

        forgetSettingsCache();

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
