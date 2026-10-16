@extends('admin.layouts.app')
@section('title', 'Pengaturan')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pengaturan']]" />

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    @if($errors->any())
    <div class="max-w-4xl mx-auto mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-xs font-semibold text-red-700 mb-1">Terdapat {{ $errors->count() }} masalah pada form:</p>
        <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-5">
        <x-admin.form-card title="Hero Section" description="Teks utama, foto, dan role berputar pada halaman utama. Ikon teknologi dikelola lewat menu Teknologi Hero.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="full_name" label="Nama Lengkap" :value="$settings['full_name'] ?? 'Heri Agus Prasetyo'" />
                    <x-admin.input name="role" label="Role" :value="$settings['role'] ?? 'Web Developer & Software Engineer'" />
                </div>
                <x-admin.input name="hero_typed_roles" label="Role Berputar (Hero)" :value="$settings['hero_typed_roles'] ?? ''" placeholder="Web Developer, Software Engineer" help="Pisahkan dengan koma. Teks ini beranimasi mengetik pada hero." />
                <x-admin.textarea name="bio" label="Bio singkat" :value="$settings['bio'] ?? ''" :rows="4" />
                <x-admin.input name="ui_hero_tech_label" label="Label Teknologi (Hero)" :value="$settings['ui_hero_tech_label'] ?? ''" placeholder="Teknologi yang saya gunakan" />
                <div x-data="{ preview: null, remove: false }">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="profile_photo" accept="image/png,image/jpeg,image/jpg,image/webp" @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; remove = false" class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Gunakan foto dengan latar bersih. Maksimal 2MB.</p>
                    <div x-show="preview" class="mt-3 inline-block"><img :src="preview" class="h-28 w-24 object-cover rounded-xl border border-gray-100"></div>
                    @php $profilePhoto = $settings['profile_photo'] ?? ''; @endphp
                    @if($profilePhoto)
                    <div x-show="!preview && !remove" class="mt-3 flex items-center gap-3">
                        <img src="{{ asset($profilePhoto) }}" class="h-20 w-16 object-cover rounded-xl border border-gray-100">
                        <label class="text-xs text-red-500"><input type="checkbox" name="remove_profile_photo" value="1" @change="remove = $checked" class="mr-1 rounded border-gray-300"> Hapus foto</label>
                    </div>
                    @endif
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Portfolio" description="Judul section portfolio dan label kartu projek di halaman utama.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="landing_portfolio_title" label="Judul: Portfolio" :value="$settings['landing_portfolio_title'] ?? ''" />
                    <x-admin.input name="landing_portfolio_subtitle" label="Subjudul: Portfolio" :value="$settings['landing_portfolio_subtitle'] ?? ''" />
                </div>
                <x-admin.input name="ui_label_view_project" label="Label: Lihat Detail (kartu projek)" :value="$settings['ui_label_view_project'] ?? ''" />
                <p class="form-hint">Kosongkan untuk memakai teks default berbahasa Indonesia.</p>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Website & SEO Minimal" description="Identitas dasar, logo, favicon, dan SEO yang dipakai layout publik.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="site_name" label="Nama Website" :value="$settings['site_name'] ?? 'Heri Agus Prasetyo'" />
                    <x-admin.input name="site_tagline" label="Tagline" :value="$settings['site_tagline'] ?? ''" />
                </div>
                <x-admin.summernote name="site_description" label="Deskripsi Website (meta)" :value="$settings['site_description'] ?? ''" :height="140" />
                <x-admin.input name="seo_title" label="SEO Title" :value="$settings['seo_title'] ?? ''" placeholder="Heri Agus Prasetyo - Web Developer & Software Engineer" />
                <x-admin.textarea name="seo_description" label="Meta Description" :value="$settings['seo_description'] ?? ''" :rows="3" />
                <x-admin.input name="seo_google_analytics" label="Google Analytics ID" :value="$settings['seo_google_analytics'] ?? ''" placeholder="G-XXXXXXXXXX" />
                <div x-data="{ preview: null, removeImage: false }">
                    <label class="form-label">Logo Gambar</label>
                    <input type="file" name="logo_image" accept="image/png,image/jpeg,image/jpg,image/webp"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeImage = false"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @php $logoImage = $settings['logo_image'] ?? ''; @endphp
                    @if($logoImage)
                    <div x-show="!preview && !removeImage" class="mt-3 flex items-center gap-4">
                        <img src="{{ asset($logoImage) }}" class="h-14 rounded-xl border border-gray-100 shadow-sm" onerror="this.style.display='none'">
                        <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                            <input type="checkbox" name="remove_logo_image" value="1" @change="removeImage = $event.target.checked" class="rounded border-gray-300 text-red-500 focus:ring-red-500 w-3.5 h-3.5">
                            Hapus logo
                        </label>
                    </div>
                    @endif
                </div>
                <div x-data="{ preview: null, removeFavicon: false }">
                    <label class="form-label">Favicon</label>
                    <input type="file" name="favicon" accept="image/png,image/jpeg,image/jpg,image/x-icon,image/webp"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeFavicon = false"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @php $favicon = $settings['favicon'] ?? ''; @endphp
                    @if($favicon)
                    <div x-show="!preview && !removeFavicon" class="mt-3 flex items-center gap-4">
                        <img src="{{ asset($favicon) }}" class="h-10 rounded-lg border border-gray-100 shadow-sm" onerror="this.style.display='none'">
                        <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                            <input type="checkbox" name="remove_favicon" value="1" @change="removeFavicon = $event.target.checked" class="rounded border-gray-300 text-red-500 focus:ring-red-500 w-3.5 h-3.5">
                            Hapus favicon
                        </label>
                    </div>
                    @endif
                </div>
                <div x-data="{ preview: null, removeImage: false }">
                    <label class="form-label">OG Image</label>
                    <input type="file" name="seo_og_image" accept="image/png,image/jpeg,image/jpg,image/webp"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeImage = false"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Rekomendasi 1200x630px. Jika kosong, dipakai logo atau foto profil.</p>
                    @php $ogImage = $settings['seo_og_image'] ?? ''; @endphp
                    @if($ogImage)
                    <div x-show="!preview && !removeImage" class="mt-3 flex items-center gap-4">
                        <img src="{{ asset($ogImage) }}" class="h-24 rounded-xl border border-gray-100 shadow-sm" onerror="this.style.display='none'">
                        <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                            <input type="checkbox" name="remove_seo_og_image" value="1" @change="removeImage = $event.target.checked" class="rounded border-gray-300 text-red-500 focus:ring-red-500 w-3.5 h-3.5">
                            Hapus gambar
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Judul Tab Browser" description="Teks pada tab browser tiap halaman. Kosongkan untuk memakai otomatis. Pola mendukung {project}, {site}, dan {nama}.">
            <div class="space-y-4">
                <x-admin.input name="tab_home" label="Tab: Homepage" :value="$settings['tab_home'] ?? ''" placeholder="Heri A Pras - Web Developer & Software Engineer" help="Terpisah dari Nama Lengkap pada Profil — mengubah nama profil tidak mengubah judul tab." />
                <x-admin.input name="tab_portfolio" label="Tab: Semua Portfolio" :value="$settings['tab_portfolio'] ?? ''" placeholder="Portfolio - Heri A Pras" />
                <x-admin.input name="tab_project" label="Tab: Detail Portfolio (pola)" :value="$settings['tab_project'] ?? ''" placeholder="{project} - Heri A Pras" help="Contoh hasil: Website DUYUFUNA - Heri A Pras." />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="tab_cv" label="Tab: CV Indonesia (pola)" :value="$settings['tab_cv'] ?? ''" placeholder="CV - Heri A Pras" />
                    <x-admin.input name="tab_cv_en" label="Tab: CV Inggris (pola)" :value="$settings['tab_cv_en'] ?? ''" placeholder="Resume - Heri A Pras" />
                </div>
            </div>
        </x-admin.form-card>

        <div class="flex justify-end">
            <x-admin.form-actions submitLabel="Simpan" />
        </div>
    </div>
</form>
@endsection
