<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $projects = Project::with('categories', 'images')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->where('id', $categoryId)))
            ->orderBy('created_at', 'desc')->paginate(10);
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'categories'));
    }

    public function create()
    {
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.form', ['project' => new Project, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                if (isEmptyRichText($value)) {
                    $fail('Kolom deskripsi wajib diisi.');
                }
            }],
            'url' => 'nullable|url|max:255',
            'client' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'duration' => 'nullable|string|max:100',
            'result_text' => 'nullable|string|max:2000',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:project_categories,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'features' => 'nullable|array',
            'features.*.feature' => 'nullable|string|max:255',
            'features.*.description' => 'nullable|string|max:500',
            'features.*.icon' => ['nullable', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if ($value !== null && $value !== '' && safeIcon($value) === '') {
                    $fail('Ikon fitur tidak valid.');
                }
            }],
            'technologies' => 'nullable|array',
            'technologies.*.name' => 'nullable|string|max:255',
            'technologies.*.icon' => ['nullable', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if ($value !== null && $value !== '' && safeIcon($value) === '') {
                    $fail('Ikon teknologi tidak valid.');
                }
            }],
        ]);
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['status'] = $request->boolean('status');
        $project = Project::create($data);
        $project->categories()->sync($data['categories'] ?? []);

        $this->syncFeatures($project, $data['features'] ?? []);
        $this->syncTechnologies($project, $data['technologies'] ?? []);

        if ($request->hasFile('main_image')) {
            $stored = storeImageAsWebp($request->file('main_image'), 'projects');
            $project->images()->create([
                'image' => $stored,
                'type' => 'cover',
                'sort_order' => 0,
            ]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $i => $file) {
                $stored = storeImageAsWebp($file, 'projects');
                $project->images()->create([
                    'image' => $stored,
                    'type' => 'gallery',
                    'sort_order' => $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::orderBy('name')->get();
        $project->load('images', 'features', 'technologies');

        return view('admin.projects.form', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                if (isEmptyRichText($value)) {
                    $fail('Kolom deskripsi wajib diisi.');
                }
            }],
            'url' => 'nullable|url|max:255',
            'client' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'duration' => 'nullable|string|max:100',
            'result_text' => 'nullable|string|max:2000',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:project_categories,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'replace_gallery' => 'boolean',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'integer|exists:project_images,id',
            'features' => 'nullable|array',
            'features.*.feature' => 'nullable|string|max:255',
            'features.*.description' => 'nullable|string|max:500',
            'features.*.icon' => ['nullable', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if ($value !== null && $value !== '' && safeIcon($value) === '') {
                    $fail('Ikon fitur tidak valid.');
                }
            }],
            'technologies' => 'nullable|array',
            'technologies.*.name' => 'nullable|string|max:255',
            'technologies.*.icon' => ['nullable', 'string', 'max:2000', function ($attribute, $value, $fail) {
                if ($value !== null && $value !== '' && safeIcon($value) === '') {
                    $fail('Ikon teknologi tidak valid.');
                }
            }],
        ]);
        $data['slug'] = $this->generateUniqueSlug($data['name'], $project->id);
        $data['status'] = $request->boolean('status');
        $project->update($data);
        $project->categories()->sync($data['categories'] ?? []);

        $this->syncFeatures($project, $data['features'] ?? []);
        $this->syncTechnologies($project, $data['technologies'] ?? []);

        if ($request->has('deleted_images')) {
            $images = ProjectImage::where('project_id', $project->id)
                ->whereIn('id', $request->input('deleted_images'))
                ->get();
            foreach ($images as $img) {
                deleteFile($img->image);
                $img->delete();
            }
        }

        if ($request->hasFile('main_image')) {
            $project->images()->where('type', 'cover')->each(function ($img) {
                deleteFile($img->image);
                $img->delete();
            });
            $stored = storeImageAsWebp($request->file('main_image'), 'projects');
            $project->images()->create([
                'image' => $stored,
                'type' => 'cover',
                'sort_order' => 0,
            ]);
        }

        if ($request->hasFile('gallery_images')) {
            if ($request->boolean('replace_gallery')) {
                $project->images()->where('type', 'gallery')->each(function ($img) {
                    deleteFile($img->image);
                    $img->delete();
                });
                $maxSort = 0;
            } else {
                $maxSort = $project->images()->max('sort_order') ?? 0;
            }
            foreach ($request->file('gallery_images') as $i => $file) {
                $stored = storeImageAsWebp($file, 'projects');
                $project->images()->create([
                    'image' => $stored,
                    'type' => 'gallery',
                    'sort_order' => $maxSort + $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroyImage(Project $project, ProjectImage $image)
    {
        if ($image->project_id !== $project->id) {
            return response()->json(['success' => false, 'message' => 'Gambar bukan milik proyek ini.'], 403);
        }
        deleteFile($image->image);
        $image->delete();

        return response()->json(['success' => true]);
    }

    protected function syncFeatures(Project $project, array $rows): void
    {
        $project->features()->delete();
        $rows = array_values(array_filter($rows, fn ($r) => trim($r['feature'] ?? '') !== ''));
        foreach ($rows as $i => $r) {
            $project->features()->create([
                'feature' => trim($r['feature']),
                'description' => trim($r['description'] ?? '') ?: null,
                'icon' => $r['icon'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    protected function syncTechnologies(Project $project, array $rows): void
    {
        $project->technologies()->delete();
        $rows = array_values(array_filter($rows, fn ($r) => trim($r['name'] ?? '') !== ''));
        foreach ($rows as $i => $r) {
            $project->technologies()->create([
                'name' => trim($r['name']),
                'icon' => $r['icon'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    public function destroy(Project $project)
    {
        foreach ($project->images as $img) {
            deleteFile($img->image);
        }
        $project->images()->delete();
        $project->features()->delete();
        $project->technologies()->delete();
        $project->results()->delete();
        $project->testimonial()->delete();
        $project->categories()->detach();
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = \Str::slug($name) ?: 'project';
        $slug = $base;
        $i = 2;
        while (Project::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
