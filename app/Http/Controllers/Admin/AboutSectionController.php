<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::orderBy('id', 'desc')->paginate(10);
        return view('admin.about-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.about-sections.form', ['section' => new AboutSection]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ]);
        // Editor rich text mengirim '<p><br></p>' saat dikosongkan. Perlakukan
        // sebagai kosong agar blok Deskripsi/Visi di halaman publik tidak
        // tersimpan sebagai markup hampa.
        foreach (['description', 'vision'] as $richKey) {
            if (isEmptyRichText($data[$richKey] ?? null)) {
                $data[$richKey] = null;
            }
        }
        $data['is_active'] = $request->boolean('is_active', true);
        if (! empty($data['mission'])) {
            $data['mission'] = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['mission']))));
        }
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('about', 'public');
            $data['image'] = 'storage/' . $path;
            if (function_exists('optimizeImage')) optimizeImage($data['image']);
        }
        AboutSection::create($data);
        return redirect()->route('admin.about-sections.index')->with('success', 'Bagian tentang berhasil ditambahkan.');
    }

    public function edit(AboutSection $aboutSection)
    {
        $section = $aboutSection;
        if (is_array($section->mission)) $section->mission = implode("\n", $section->mission);
        return view('admin.about-sections.form', compact('section'));
    }

    public function update(Request $request, AboutSection $aboutSection)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'remove_image' => 'boolean',
        ]);
        // Editor rich text mengirim '<p><br></p>' saat dikosongkan. Perlakukan
        // sebagai kosong agar blok Deskripsi/Visi di halaman publik tidak
        // tersimpan sebagai markup hampa.
        foreach (['description', 'vision'] as $richKey) {
            if (isEmptyRichText($data[$richKey] ?? null)) {
                $data[$richKey] = null;
            }
        }
        $data['is_active'] = $request->boolean('is_active');
        if (! empty($data['mission'])) {
            $data['mission'] = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['mission']))));
        } else {
            $data['mission'] = null;
        }
        if ($request->boolean('remove_image') && $aboutSection->image) {
            if (function_exists('deleteFile')) deleteFile($aboutSection->image);
            $data['image'] = null;
        }
        if ($request->hasFile('image')) {
            if ($aboutSection->image && function_exists('deleteFile')) deleteFile($aboutSection->image);
            $path = $request->file('image')->store('about', 'public');
            $data['image'] = 'storage/' . $path;
            if (function_exists('optimizeImage')) optimizeImage($data['image']);
        }
        $aboutSection->update($data);
        return redirect()->route('admin.about-sections.index')->with('success', 'Bagian tentang berhasil diperbarui.');
    }

    public function destroy(AboutSection $aboutSection)
    {
        if ($aboutSection->image && function_exists('deleteFile')) deleteFile($aboutSection->image);
        $aboutSection->delete();
        return redirect()->route('admin.about-sections.index')->with('success', 'Bagian tentang dihapus.');
    }
}
