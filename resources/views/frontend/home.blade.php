@extends('layouts.frontend')

@section('body_class', 'index-page')

@section('content')
@php
    $fullName = setting('full_name', 'Heri Agus Prasetyo');
    $role = setting('role', 'Web Developer & Software Engineer');
    $bio = setting('bio', 'Saya membangun website modern dan solusi digital yang mengubah ide menjadi produk nyata.');
    $studyProgram = setting('study_program', 'Program Studi Sistem Informasi');
    $university = setting('university', 'Universitas Mercu Buana Yogyakarta');
    $location = setting('location', 'Yogyakarta, Indonesia');
    $typedRoles = setting('hero_typed_roles') ?: 'Web Developer, Software Engineer';
    $initials = collect(explode(' ', $fullName))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    $profilePhoto = setting('profile_photo');
    $aboutImage = ($aboutSection && $aboutSection->image) ? $aboutSection->image : $profilePhoto;
    $skills = $skills->values();
@endphp

@include('partials.frontend.hero')
@include('partials.frontend.featured-work')
@endsection
