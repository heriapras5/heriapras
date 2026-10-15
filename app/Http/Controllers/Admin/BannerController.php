<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::when($request->get('search'), fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('sort_order')->paginate(10)->withQueryString();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $data['image'] = 'storage/' . $path;
            if (function_exists('optimizeImage')) optimizeImage($data['image']);
        }
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'remove_image' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->boolean('remove_image') && $banner->image) {
            if (function_exists('deleteFile')) deleteFile($banner->image);
            $data['image'] = null;
        }
        if ($request->hasFile('image')) {
            if ($banner->image && function_exists('deleteFile')) deleteFile($banner->image);
            $path = $request->file('image')->store('banners', 'public');
            $data['image'] = 'storage/' . $path;
            if (function_exists('optimizeImage')) optimizeImage($data['image']);
        }
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && function_exists('deleteFile')) deleteFile($banner->image);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus.');
    }
}
