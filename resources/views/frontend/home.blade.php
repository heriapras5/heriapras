@extends('layouts.frontend')

@section('body_class', 'index-page')

@section('content')
@php
    $fullName = setting('full_name', 'Heri Agus Prasetyo');
    $role = setting('role', 'Web Developer & Software Engineer');
    $bio = setting('bio', 'Nama saya Heri Agus Prasetyo, lulusan Universitas Mercu Buana Yogyakarta, Program Studi Sistem Informasi. Saya adalah seorang Web Developer yang berpengalaman membangun dan mengembangkan berbagai proyek website dan aplikasi berbasis web. Sejak tahun 2025, saya aktif mengerjakan berbagai proyek freelance dengan fokus pada pengembangan solusi digital sesuai kebutuhan klien.');
    $typedRoles = setting('hero_typed_roles') ?: 'Web Developer, Software Engineer';
    $initials = collect(explode(' ', $fullName))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    $profilePhoto = setting('profile_photo');
    $skills = $skills->values();
@endphp

@include('partials.frontend.hero')
@include('partials.frontend.featured-work')
@endsection
