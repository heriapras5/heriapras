<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $services = Service::where('type', 'skill')
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->orderBy('sort_order')->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => ['required', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if (safeIcon($value) === '') {
                    $fail('Ikon tidak valid.');
                }
            }],
            'group_name' => 'required|string|max:255',
            'description' => 'required|string',
            'percent' => 'nullable|integer|min:0|max:100',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        $data['type'] = 'skill';
        $data['status'] = $request->boolean('status');
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['features'] = $data['features'] ?? [];
        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => ['required', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if (safeIcon($value) === '') {
                    $fail('Ikon tidak valid.');
                }
            }],
            'group_name' => 'required|string|max:255',
            'description' => 'required|string',
            'percent' => 'nullable|integer|min:0|max:100',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        $data['type'] = 'skill';
        $data['status'] = $request->boolean('status');
        $data['slug'] = $this->generateUniqueSlug($data['title'], $service->id);
        $data['features'] = $data['features'] ?? [];
        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Keahlian berhasil dihapus.');
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'layanan';
        }
        $base = $slug;
        $counter = 2;
        while (true) {
            $query = Service::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (! $query->exists()) {
                break;
            }
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
