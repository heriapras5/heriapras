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
        <x-admin.form-card title="Informasi Website" description="Identitas dasar website Anda.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="site_name" label="Nama Portofolio" :value="$settings['site_name'] ?? 'Heri Agus Prasetyo'" />
                    <x-admin.input name="site_tagline" label="Tagline" :value="$settings['site_tagline'] ?? 'INOVASI WEBSITE'" />
                </div>
                <x-admin.summernote name="site_description" label="Deskripsi Website (meta)" :value="$settings['site_description'] ?? ''" :height="160" />
                <x-admin.input name="whatsapp_number" label="Nomor WhatsApp" :value="$settings['whatsapp_number'] ?? ''" placeholder="6285736186308" help="Format: kode negara + nomor, tanpa + atau spasi." />
                <x-admin.textarea name="footer_address" label="Alamat Footer" :value="$settings['footer_address'] ?? ''" :rows="2" placeholder="Jl. Contoh No. 123, Kota" />
                <x-admin.input name="footer_email" label="Email Footer" type="email" :value="$settings['footer_email'] ?? ''" placeholder="email@example.com" />
                <x-admin.textarea name="maps_embed" label="Google Maps Embed" :value="$settings['maps_embed'] ?? ''" :rows="3" placeholder="Kode embed iframe Google Maps" help="Kosongkan untuk tidak menampilkan peta." wrapperClass="[&_textarea]:font-mono [&_textarea]:text-xs" />
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Profil Publik" description="Informasi utama yang tampil pada hero dan halaman tentang.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="full_name" label="Nama Lengkap" :value="$settings['full_name'] ?? 'Heri Agus Prasetyo'" />
                    <x-admin.input name="role" label="Role" :value="$settings['role'] ?? 'Web Developer & Software Engineer'" />
                    <x-admin.input name="study_program" label="Program Studi" :value="$settings['study_program'] ?? 'Sistem Informasi'" />
                    <x-admin.input name="university" label="Universitas" :value="$settings['university'] ?? 'Universitas Mercu Buana Yogyakarta'" />
                    <x-admin.input name="location" label="Lokasi" :value="$settings['location'] ?? 'Yogyakarta, Indonesia'" />
                    <x-admin.input name="cv_url" label="URL CV Eksternal (opsional)" type="text" :value="$settings['cv_url'] ?? ''" placeholder="https://... atau kosongkan" help="Kosongkan untuk memakai CV dinamis dari tombol Unduh CV (/cv). Isi URL lengkap jika CV dihosting di luar." />
                    <x-admin.input name="portfolio_url" label="URL Portfolio Publik" type="url" :value="$settings['portfolio_url'] ?? ''" placeholder="https://domain-anda.com" help="Dipakai sebagai tautan portfolio di dalam file CV. Kosongkan untuk memakai domain aktif." />
                    <div>
                        <label class="form-label">File CV Pengganti (PDF, maks 5MB)</label>
                        <input type="file" name="cv_file" accept="application/pdf" class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="form-hint mt-1">Jika diunggah, tombol Unduh CV dan alamat /cv akan mengarah ke file ini. Kosongkan untuk tetap memakai CV dinamis.</p>
                        @if(!empty($settings['cv_url']) && str_contains((string) $settings['cv_url'], 'storage/cv/'))
                        <label class="text-xs text-red-500 mt-1 inline-block"><input type="checkbox" name="remove_cv_file" value="1" class="mr-1 rounded border-gray-300"> Hapus file dan kembali ke CV dinamis</label>
                        @endif
                        <p class="form-hint mt-1">CV dinamis: <a href="{{ route('cv') }}" target="_blank" class="text-blue-600 underline">Indonesia (PDF)</a> | <a href="{{ route('cv.en') }}" target="_blank" class="text-blue-600 underline">Inggris (PDF)</a> | <a href="{{ route('cv.preview') }}" target="_blank" class="text-blue-600 underline">Pratinjau ID</a> | <a href="{{ route('cv.en.preview') }}" target="_blank" class="text-blue-600 underline">Pratinjau EN</a></p>
                    </div>
                </div>
                <x-admin.textarea name="bio" label="Bio singkat" :value="$settings['bio'] ?? ''" :rows="4" />
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
                <div x-data="{ bgPreview: null, removeBg: false }">
                    <label class="form-label">Gambar Latar Beranda (Hero)</label>
                    <input type="file" name="hero_background" accept="image/png,image/jpeg,image/jpg,image/webp" @change="bgPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeBg = false" class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Latar foto besar di section beranda. Maksimal 10MB. Kosongkan untuk memakai default.</p>
                    <div x-show="bgPreview" class="mt-3 inline-block"><img :src="bgPreview" class="h-28 w-44 object-cover rounded-xl border border-gray-100"></div>
                    @php $heroBg = $settings['hero_background'] ?? ''; @endphp
                    @if($heroBg)
                    <div x-show="!bgPreview && !removeBg" class="mt-3 flex items-center gap-3">
                        <img src="{{ asset($heroBg) }}" class="h-20 w-36 object-cover rounded-xl border border-gray-100">
                        <label class="text-xs text-red-500"><input type="checkbox" name="remove_hero_background" value="1" @change="removeBg = $checked" class="mr-1 rounded border-gray-300"> Hapus latar</label>
                    </div>
                    @endif
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Sosial Media" description="Tautan media sosial yang tampil di footer.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="social_facebook" label="Facebook" type="url" :value="$settings['social_facebook'] ?? ''" placeholder="https://facebook.com/namapage" />
                    <x-admin.input name="social_instagram" label="Instagram" type="url" :value="$settings['social_instagram'] ?? ''" placeholder="https://instagram.com/namauser" />
                    <x-admin.input name="social_linkedin" label="LinkedIn" type="url" :value="$settings['social_linkedin'] ?? ''" placeholder="https://linkedin.com/in/namauser" />
                    <x-admin.input name="social_youtube" label="YouTube" type="url" :value="$settings['social_youtube'] ?? ''" placeholder="https://youtube.com/@channel" />
                    <x-admin.input name="github_url" label="GitHub" type="url" :value="$settings['github_url'] ?? ''" placeholder="https://github.com/username" />
                </div>
                <p class="form-hint">Kosongkan jika tidak ingin menampilkan ikon media sosial tertentu.</p>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Logo & Favicon" description="Atur logo dan ikon tab browser.">
            <div class="space-y-5">
                <div x-data="{ preview: null, removeImage: false }">
                    <label class="form-label">Logo Gambar</label>
                    <input type="file" name="logo_image" accept="image/png,image/jpeg,image/jpg,image/webp"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeImage = false"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Kosongkan untuk menggunakan logo teks + ikon default. Maks 2MB.</p>
                    <div x-show="preview" class="mt-3 relative inline-block">
                        <img :src="preview" class="h-14 rounded-xl shadow-sm border border-gray-100">
                        <button @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" type="button"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                    </div>
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
                    <p class="form-hint mt-1">Format: PNG, JPG, ICO, WEBP. Maks 1MB.</p>
                    <div x-show="preview" class="mt-3 relative inline-block">
                        <img :src="preview" class="h-10 rounded-lg shadow-sm border border-gray-100">
                        <button @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" type="button"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                    </div>
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
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Landing Page" description="Judul, subjudul, dan teks animasi tiap section di halaman utama.">
            <div class="space-y-4">
                <x-admin.input name="hero_typed_roles" label="Role Berputar (Hero)" :value="$settings['hero_typed_roles'] ?? ''" placeholder="Web Developer, Software Engineer" help="Pisahkan dengan koma. Teks ini beranimasi mengetik pada hero." />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="landing_about_title" label="Judul: Tentang" :value="$settings['landing_about_title'] ?? ''" />
                    <x-admin.input name="landing_about_subtitle" label="Subjudul: Tentang" :value="$settings['landing_about_subtitle'] ?? ''" />
                    <x-admin.input name="landing_skills_title" label="Judul: Keahlian" :value="$settings['landing_skills_title'] ?? ''" />
                    <x-admin.input name="landing_skills_subtitle" label="Subjudul: Keahlian" :value="$settings['landing_skills_subtitle'] ?? ''" />
                    <x-admin.input name="landing_resume_title" label="Judul: Perjalanan" :value="$settings['landing_resume_title'] ?? ''" />
                    <x-admin.input name="landing_resume_subtitle" label="Subjudul: Perjalanan" :value="$settings['landing_resume_subtitle'] ?? ''" />
                    <x-admin.input name="landing_portfolio_title" label="Judul: Portfolio" :value="$settings['landing_portfolio_title'] ?? ''" />
                    <x-admin.input name="landing_portfolio_subtitle" label="Subjudul: Portfolio" :value="$settings['landing_portfolio_subtitle'] ?? ''" />
                    <x-admin.input name="landing_contact_title" label="Judul: Kontak" :value="$settings['landing_contact_title'] ?? ''" />
                    <x-admin.input name="landing_contact_subtitle" label="Subjudul: Kontak" :value="$settings['landing_contact_subtitle'] ?? ''" />
                </div>
                <p class="form-hint">Kosongkan untuk memakai teks default berbahasa Indonesia. Section yang datanya kosong (misal testimoni) otomatis tidak ditampilkan.</p>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Teks Tombol & Label" description="Teks kecil antarmuka pada frontend publik: badge hero, judul hero, tombol, dan label section.">
            <div class="space-y-4">
                <x-admin.input name="ui_hero_badge" label="Badge Hero" :value="$settings['ui_hero_badge'] ?? ''" placeholder="Pengembang Web" />
                <x-admin.input name="ui_hero_headline" label="Kalimat Hero (baris kedua)" :value="$settings['ui_hero_headline'] ?? ''" placeholder="Membangun website dan solusi digital." help="Baris pertama otomatis memakai nama dari field Nama Lengkap." />
                <x-admin.input name="ui_hero_tech_label" label="Label Teknologi (Hero)" :value="$settings['ui_hero_tech_label'] ?? ''" placeholder="Teknologi yang saya gunakan" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="ui_cta_portfolio" label="Tombol: Lihat Portfolio" :value="$settings['ui_cta_portfolio'] ?? ''" />
                    <x-admin.input name="ui_cta_cv" label="Tombol: Unduh CV" :value="$settings['ui_cta_cv'] ?? ''" />
                    <x-admin.input name="ui_cta_hire" label="Tombol: Navbar & Tentang" :value="$settings['ui_cta_hire'] ?? ''" />
                    <x-admin.input name="ui_cta_contact" label="Tombol: Hubungi (Kontak)" :value="$settings['ui_cta_contact'] ?? ''" />
                    <x-admin.input name="ui_cta_about" label="Tombol: Selengkapnya Tentang" :value="$settings['ui_cta_about'] ?? ''" />
                    <x-admin.input name="ui_cta_see_all" label="Tombol: Lihat Semua (Projek)" :value="$settings['ui_cta_see_all'] ?? ''" />
                    <x-admin.input name="ui_label_view_project" label="Label: Lihat Detail (kartu)" :value="$settings['ui_label_view_project'] ?? ''" />
                    <x-admin.input name="ui_eyebrow_about" label="Label Section: Tentang" :value="$settings['ui_eyebrow_about'] ?? ''" />
                    <x-admin.input name="ui_eyebrow_skills" label="Label Section: Keahlian" :value="$settings['ui_eyebrow_skills'] ?? ''" />
                    <x-admin.input name="ui_eyebrow_portfolio" label="Label Section: Projek" :value="$settings['ui_eyebrow_portfolio'] ?? ''" />
                    <x-admin.input name="ui_eyebrow_resume" label="Label Section: Perjalanan" :value="$settings['ui_eyebrow_resume'] ?? ''" />
                    <x-admin.input name="ui_eyebrow_follow" label="Label Section: Ikuti Saya" :value="$settings['ui_eyebrow_follow'] ?? ''" />
                </div>
                <p class="form-hint">Kosongkan untuk memakai teks default berbahasa Indonesia.</p>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Section Tentang" description="Konten halaman tentang kami.">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="about_title" label="Judul" :value="$about->title" />
                    <x-admin.input name="about_subtitle" label="Subjudul" :value="$about->subtitle" />
                </div>
                <x-admin.summernote name="about_description" label="Deskripsi" :value="$about->description" :height="200" />
                <x-admin.summernote name="about_vision" label="Visi" :value="$about->vision" :height="140" />
                <x-admin.textarea name="about_mission" label="Misi (satu baris per item)" :value="$about->mission ? implode(chr(10), $about->mission) : ''" :rows="5" placeholder="Menyediakan website berkualitas" />
                <div x-data="{ preview: null, removeAboutImage: false }">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="about_image" accept="image/png,image/jpeg,image/jpg,image/webp"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeAboutImage = false"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div x-show="preview" class="mt-3 relative inline-block">
                        <img :src="preview" class="h-28 rounded-xl shadow-sm border border-gray-100">
                        <button @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" type="button"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                    </div>
                    @if($about->image)
                    <div x-show="!preview && !removeAboutImage" class="mt-3 flex items-center gap-4">
                        <img src="{{ asset($about->image) }}" class="h-28 rounded-xl shadow-sm border border-gray-100" onerror="this.style.display='none'">
                        <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                            <input type="checkbox" name="remove_about_image" value="1" @change="removeAboutImage = $event.target.checked" class="rounded border-gray-300 text-red-500 focus:ring-red-500 w-3.5 h-3.5">
                            Hapus gambar
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </x-admin.form-card>

        <div class="flex justify-end">
            <x-admin.form-actions submitLabel="Simpan" />
        </div>
    </div>
</form>
@endsection
