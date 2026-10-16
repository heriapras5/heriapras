<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $keys = ['site_name', 'site_tagline', 'site_description', 'full_name', 'role', 'study_program', 'university', 'location', 'bio', 'cv_url', 'portfolio_url', 'profile_photo', 'logo_image', 'favicon', 'hero_background', 'whatsapp_number', 'footer_address', 'footer_email', 'maps_embed', 'social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube', 'github_url',
            'hero_typed_roles',
            'landing_about_title', 'landing_about_subtitle',
            'landing_skills_title', 'landing_skills_subtitle',
            'landing_resume_title', 'landing_resume_subtitle',
            'landing_portfolio_title', 'landing_portfolio_subtitle',
            'landing_contact_title', 'landing_contact_subtitle',
            'ui_hero_badge', 'ui_hero_headline', 'ui_hero_tech_label',
            'ui_cta_portfolio', 'ui_cta_cv', 'ui_cta_hire', 'ui_cta_contact', 'ui_cta_about', 'ui_cta_see_all',
            'ui_label_view_project',
            'ui_eyebrow_about', 'ui_eyebrow_skills', 'ui_eyebrow_portfolio', 'ui_eyebrow_resume', 'ui_eyebrow_follow'];
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::where('key', $key)->value('value');
        }
        $about = AboutSection::where('is_active', true)->first() ?? new AboutSection;

        return view('admin.settings.index', compact('settings', 'keys', 'about'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'full_name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'cv_url' => 'nullable|string|max:255',
            'cv_file' => 'nullable|file|mimes:pdf|max:5120',
            'remove_cv_file' => 'nullable|boolean',
            'portfolio_url' => 'nullable|url|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,ico,webp|max:5120',
            'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'whatsapp_number' => 'nullable|string|max:20',
            'footer_address' => 'nullable|string|max:500',
            'footer_email' => 'nullable|email|max:255',
            'maps_embed' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value === null || trim($value) === '') {
                    return;
                }
                if (preg_match('#<\s*(script|object|embed|form)\b#i', $value)
                    || preg_match('/\son[a-z]+\s*=/i', $value)
                    || preg_match('/(javascript|vbscript)\s*:/i', $value)) {
                    $fail('Embed peta mengandung konten yang tidak diizinkan.');

                    return;
                }
                if (str_contains($value, '<iframe')
                    && ! preg_match('#<iframe[^>]+src=(["\'])https://(www\.google\.com/maps|maps\.google\.com|google\.com/maps|www\.openstreetmap\.org|openstreetmap\.org)(/[^"\']*)?\1#i', $value)) {
                    $fail('Embed peta hanya mendukung iframe Google Maps atau OpenStreetMap.');
                }
            }],
            'social_facebook' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'github_url' => 'nullable|url|max:255',
            'hero_typed_roles' => 'nullable|string|max:255',
            'landing_about_title' => 'nullable|string|max:255',
            'landing_about_subtitle' => 'nullable|string|max:500',
            'landing_skills_title' => 'nullable|string|max:255',
            'landing_skills_subtitle' => 'nullable|string|max:500',
            'landing_resume_title' => 'nullable|string|max:255',
            'landing_resume_subtitle' => 'nullable|string|max:500',
            'landing_portfolio_title' => 'nullable|string|max:255',
            'landing_portfolio_subtitle' => 'nullable|string|max:500',
            'landing_contact_title' => 'nullable|string|max:255',
            'landing_contact_subtitle' => 'nullable|string|max:500',
            'ui_hero_badge' => 'nullable|string|max:100',
            'ui_hero_headline' => 'nullable|string|max:255',
            'ui_hero_tech_label' => 'nullable|string|max:100',
            'ui_cta_portfolio' => 'nullable|string|max:100',
            'ui_cta_cv' => 'nullable|string|max:100',
            'ui_cta_hire' => 'nullable|string|max:100',
            'ui_cta_contact' => 'nullable|string|max:100',
            'ui_cta_about' => 'nullable|string|max:100',
            'ui_cta_see_all' => 'nullable|string|max:100',
            'ui_label_view_project' => 'nullable|string|max:100',
            'ui_eyebrow_about' => 'nullable|string|max:100',
            'ui_eyebrow_skills' => 'nullable|string|max:100',
            'ui_eyebrow_portfolio' => 'nullable|string|max:100',
            'ui_eyebrow_resume' => 'nullable|string|max:100',
            'ui_eyebrow_follow' => 'nullable|string|max:100',
            'about_title' => 'nullable|string|max:255',
            'about_subtitle' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'about_vision' => 'nullable|string',
            'about_mission' => 'nullable|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_profile_photo' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
            'remove_hero_background' => 'nullable|boolean',
            'remove_about_image' => 'nullable|boolean',
        ]);

        // Editor rich text mengirim '<p><br></p>' saat dikosongkan. Perlakukan
        // sebagai kosong agar kolom NOT NULL / meta description tidak terisi markup hampa.
        foreach (['site_description', 'about_description', 'about_vision'] as $richKey) {
            if (isEmptyRichText($data[$richKey] ?? null)) {
                $data[$richKey] = null;
            }
        }

        foreach (['site_name', 'site_tagline', 'site_description', 'full_name', 'role', 'study_program', 'university', 'location', 'bio', 'cv_url', 'portfolio_url', 'whatsapp_number', 'footer_address', 'footer_email', 'maps_embed', 'social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube', 'github_url',
            'hero_typed_roles',
            'landing_about_title', 'landing_about_subtitle',
            'landing_skills_title', 'landing_skills_subtitle',
            'landing_resume_title', 'landing_resume_subtitle',
            'landing_portfolio_title', 'landing_portfolio_subtitle',
            'landing_contact_title', 'landing_contact_subtitle',
            'ui_hero_badge', 'ui_hero_headline', 'ui_hero_tech_label',
            'ui_cta_portfolio', 'ui_cta_cv', 'ui_cta_hire', 'ui_cta_contact', 'ui_cta_about', 'ui_cta_see_all',
            'ui_label_view_project',
            'ui_eyebrow_about', 'ui_eyebrow_skills', 'ui_eyebrow_portfolio', 'ui_eyebrow_resume', 'ui_eyebrow_follow'] as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? '', 'group' => 'general', 'type' => 'text']
            );
        }

        if ($request->boolean('remove_cv_file')) {
            $this->deleteCvFile();
            Setting::updateOrCreate(['key' => 'cv_url'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('cv_file')) {
            $this->deleteCvFile();
            $path = $request->file('cv_file')->storeAs('cv', 'cv-' . time() . '.pdf', 'public');
            Setting::updateOrCreate(['key' => 'cv_url'], ['value' => url('storage/' . $path), 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_profile_photo')) {
            deleteFile(Setting::where('key', 'profile_photo')->value('value'));
            Setting::updateOrCreate(['key' => 'profile_photo'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('profile_photo')) {
            deleteFile(Setting::where('key', 'profile_photo')->value('value'));
            // Nama file diseragamkan menjadi heri-profile.<ext> agar URL foto
            // hero stabil; cache-buster (?v=filemtime) dipasang di view hero.
            $ext = $request->file('profile_photo')->extension() ?: 'jpg';
            $path = $request->file('profile_photo')->storeAs('settings', 'heri-profile.'.$ext, 'public');
            Setting::updateOrCreate(['key' => 'profile_photo'], ['value' => 'storage/'.$path, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_logo_image')) {
            deleteFile(Setting::where('key', 'logo_image')->value('value'));
            Setting::updateOrCreate(['key' => 'logo_image'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('logo_image')) {
            deleteFile(Setting::where('key', 'logo_image')->value('value'));
            $path = $request->file('logo_image')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'logo_image'], ['value' => 'storage/'.$path, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_favicon')) {
            deleteFile(Setting::where('key', 'favicon')->value('value'));
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('favicon')) {
            deleteFile(Setting::where('key', 'favicon')->value('value'));
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => 'storage/'.$path, 'group' => 'general', 'type' => 'text']);
        }

        if ($request->boolean('remove_hero_background')) {
            deleteFile(Setting::where('key', 'hero_background')->value('value'));
            Setting::updateOrCreate(['key' => 'hero_background'], ['value' => '', 'group' => 'general', 'type' => 'text']);
        } elseif ($request->hasFile('hero_background')) {
            deleteFile(Setting::where('key', 'hero_background')->value('value'));
            $path = $request->file('hero_background')->store('settings', 'public');
            if (function_exists('optimizeImage')) optimizeImage('storage/'.$path, 1920, 85);
            Setting::updateOrCreate(['key' => 'hero_background'], ['value' => 'storage/'.$path, 'group' => 'general', 'type' => 'text']);
        }

        $aboutData = [
            'title' => $data['about_title'] ?? '',
            'subtitle' => $data['about_subtitle'] ?? '',
            'description' => $data['about_description'] ?? '',
            'vision' => $data['about_vision'] ?? '',
            'mission' => ($data['about_mission'] ?? null) ? array_filter(explode("\n", str_replace("\r", '', $data['about_mission']))) : null,
            'is_active' => true,
        ];

        if ($request->boolean('remove_about_image')) {
            $oldImage = AboutSection::where('is_active', true)->value('image');
            deleteFile($oldImage);
            $aboutData['image'] = '';
        } elseif ($request->hasFile('about_image')) {
            deleteFile(AboutSection::where('is_active', true)->value('image'));
            $path = $request->file('about_image')->store('settings', 'public');
            $aboutData['image'] = 'storage/'.$path;
        } else {
            $aboutData['image'] = AboutSection::where('is_active', true)->value('image') ?? '';
        }

        AboutSection::updateOrCreate(['is_active' => true], $aboutData);

        forgetSettingsCache();

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    protected function deleteCvFile(): void
    {
        $url = (string) Setting::where('key', 'cv_url')->value('value');
        if (str_contains($url, 'storage/cv/')) {
            $relative = substr($url, strpos($url, 'storage/cv/'));
            deleteFile($relative);
        }
    }
}
