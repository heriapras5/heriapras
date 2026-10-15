<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    private array $keys = [
        'seo_title', 'seo_description', 'seo_keywords', 'seo_author', 'seo_robots',
        'seo_og_image', 'seo_google_analytics', 'seo_facebook_pixel', 'seo_canonical',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::where('key', $key)->value('value');
        }
        return view('admin.seo.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'seo_author' => 'nullable|string|max:255',
            'seo_robots' => 'nullable|string|max:255',
            'seo_og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_google_analytics' => 'nullable|string|max:500',
            'seo_facebook_pixel' => 'nullable|string|max:500',
            'seo_canonical' => 'nullable|string|max:255',
            'remove_seo_og_image' => 'nullable|boolean',
        ]);

        foreach (['seo_title', 'seo_description', 'seo_keywords', 'seo_author', 'seo_robots', 'seo_google_analytics', 'seo_facebook_pixel', 'seo_canonical'] as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? '', 'group' => 'seo', 'type' => 'text']
            );
        }

        if ($request->boolean('remove_seo_og_image')) {
            deleteFile(Setting::where('key', 'seo_og_image')->value('value'));
            Setting::updateOrCreate(['key' => 'seo_og_image'], ['value' => '', 'group' => 'seo', 'type' => 'text']);
        } elseif ($request->hasFile('seo_og_image')) {
            deleteFile(Setting::where('key', 'seo_og_image')->value('value'));
            $path = $request->file('seo_og_image')->store('settings', 'public');
            optimizeImage('storage/' . $path);
            Setting::updateOrCreate(['key' => 'seo_og_image'], ['value' => 'storage/' . $path, 'group' => 'seo', 'type' => 'text']);
        }

        forgetSettingsCache();

        return redirect()->route('admin.seo.index')->with('success', 'Pengaturan SEO berhasil disimpan.');
    }
}
