@extends('admin.layouts.app')
@section('title', 'Pengaturan SEO')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pengaturan SEO']]" />

<form method="POST" action="{{ route('admin.seo.update') }}" enctype="multipart/form-data">
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
        <x-admin.form-card title="Meta Tags" description="Judul dan deskripsi di hasil pencarian (Google).">
            <div class="space-y-4">
                <x-admin.input name="seo_title" label="SEO Title" :value="$settings['seo_title'] ?? ''" placeholder="Heri Agus Prasetyo - Web Developer & Software Engineer" help="Kosongkan untuk memakai nama website. Maks 60 karakter ideal." />
                <x-admin.textarea name="seo_description" label="Meta Description" :value="$settings['seo_description'] ?? ''" :rows="3" placeholder="Deskripsi singkat website Anda untuk mesin pencari." help="Kosongkan untuk memakai deskripsi website. Maks 160 karakter ideal." />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="seo_keywords" label="Keywords" :value="$settings['seo_keywords'] ?? ''" placeholder="website, jasa pembuatan website" help="Pisahkan dengan koma." />
                    <x-admin.input name="seo_author" label="Author" :value="$settings['seo_author'] ?? ''" placeholder="Heri Agus Prasetyo" />
                </div>
                <x-admin.select name="seo_robots" label="Robots" :options="['index,follow' => 'index, follow (default)', 'noindex,follow' => 'noindex, follow', 'index,nofollow' => 'index, nofollow', 'noindex,nofollow' => 'noindex, nofollow']" :selected="old('seo_robots', $settings['seo_robots'] ?? 'index,follow')" />
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Open Graph / Sosial" description="Gambar dan tautan saat dibagikan di media sosial.">
            <div x-data="{ preview: null, removeImage: false }">
                <label class="form-label">OG Image</label>
                <input type="file" name="seo_og_image" accept="image/png,image/jpeg,image/jpg,image/webp"
                       @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; removeImage = false"
                       class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="form-hint mt-1">Gambar utama saat link dibagikan (tampil paling atas). Rekomendasi 1200x630px, PNG/JPG/WEBP, maks 2MB. Jika kosong, dipakai Logo Website. Jika logo juga kosong, preview tanpa gambar.</p>
                <div x-show="preview" class="mt-3 relative inline-block">
                    <img :src="preview" class="h-24 rounded-xl shadow-sm border border-gray-100">
                    <button @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" type="button" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                </div>
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
        </x-admin.form-card>

        <x-admin.form-card title="Analytics dan Lanjutan" description="Integrasi pelacakan dan tautan kanonikal.">
            <div class="space-y-4">
                <x-admin.input name="seo_google_analytics" label="Google Analytics ID" :value="$settings['seo_google_analytics'] ?? ''" placeholder="G-XXXXXXXXXX" help="ID pengukuran. Kosongkan jika tidak digunakan." />
                <x-admin.input name="seo_facebook_pixel" label="Facebook Pixel ID" :value="$settings['seo_facebook_pixel'] ?? ''" placeholder="1234567890" help="Kosongkan jika tidak digunakan." />
                <x-admin.input name="seo_canonical" label="Canonical URL" type="url" :value="$settings['seo_canonical'] ?? ''" placeholder="https://domainanda.com" help="Kosongkan untuk otomatis mengikuti halaman." />
            </div>
        </x-admin.form-card>

        <x-admin.form-actions :cancelUrl="route('admin.dashboard')" submitLabel="Simpan" />
    </div>
</form>
@endsection
