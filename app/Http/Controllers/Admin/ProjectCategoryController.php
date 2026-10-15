<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = ProjectCategory::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')->paginate(10);
        return view('admin.project-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.project-categories.form', ['category' => new ProjectCategory]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:project_categories,slug',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        if (!$data['slug']) $data['slug'] = \Str::slug($data['name']);
        $data['status'] = $request->boolean('status');
        ProjectCategory::create($data);
        return redirect()->route('admin.project-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        return view('admin.project-categories.form', ['category' => $projectCategory]);
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:project_categories,slug,' . $projectCategory->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);
        if (!$data['slug']) $data['slug'] = \Str::slug($data['name']);
        $data['status'] = $request->boolean('status');
        $projectCategory->update($data);
        return redirect()->route('admin.project-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();
        return redirect()->route('admin.project-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
