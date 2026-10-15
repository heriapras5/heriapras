<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $heroSections = HeroSection::when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->orderBy('sort_order')->paginate(10);
        return view('admin.hero-sections.index', compact('heroSections'));
    }

    public function create()
    {
        return view('admin.hero-sections.form', ['heroSection' => new HeroSection]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $sortOrder = $data['sort_order'] ?? 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('hero', 'public');
                $imgPath = 'storage/' . $path;
                optimizeImage($imgPath);
                HeroSection::create([
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'] ?? null,
                    'button_text' => $data['button_text'] ?? null,
                    'button_url' => $data['button_url'] ?? null,
                    'image' => $imgPath,
                    'sort_order' => $sortOrder + $i,
                    'is_active' => $data['is_active'],
                ]);
            }
        } else {
            HeroSection::create($data);
        }

        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section berhasil ditambahkan.');
    }

    public function edit(HeroSection $heroSection)
    {
        return view('admin.hero-sections.form', compact('heroSection'));
    }

    public function update(Request $request, HeroSection $heroSection)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'remove_image' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_image')) {
            if ($heroSection->image) {
                deleteFile($heroSection->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($heroSection->image) {
                deleteFile($heroSection->image);
            }
            $path = $request->file('image')->store('hero', 'public');
            $data['image'] = 'storage/' . $path;
            optimizeImage($data['image']);
        }

        $heroSection->update($data);
        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section berhasil diperbarui.');
    }

    public function destroy(HeroSection $heroSection)
    {
        if ($heroSection->image) {
            deleteFile($heroSection->image);
        }
        $heroSection->delete();
        return redirect()->route('admin.hero-sections.index')->with('success', 'Hero section berhasil dihapus.');
    }
}
