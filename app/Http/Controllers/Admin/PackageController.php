<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $packages = Package::when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')->paginate(10);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.form', ['package' => new Package]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'old_price' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'renewal' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        $data['status'] = $request->boolean('status');
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['facilities'] = array_values(array_filter(array_map('trim', $data['facilities'] ?? [])));
        Package::create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.form', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'old_price' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'renewal' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        $data['status'] = $request->boolean('status');
        $data['slug'] = $this->generateUniqueSlug($data['name'], $package->id);
        $data['facilities'] = array_values(array_filter(array_map('trim', $data['facilities'] ?? [])));
        $package->update($data);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus.');
    }

    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = \Str::slug($name) ?: 'paket';
        $slug = $base;
        $i = 2;
        while (Package::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
