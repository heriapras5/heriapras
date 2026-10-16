<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;

class AdminController extends Controller
{
    /**
     * Dashboard ringkas: hanya Hero + Portfolio.
     * Statistik kunjungan (page_views) sengaja dihapus.
     */
    public function dashboard()
    {
        $contentSummary = [
            ['label' => 'Teknologi Hero', 'count' => Service::where('type', 'skill')->count(), 'route' => route('admin.services.index'), 'icon' => 'paket', 'colors' => ['bg' => 'from-violet-500 to-violet-600', 'iconBg' => 'bg-violet-50', 'iconText' => 'text-violet-600']],
            ['label' => 'Projek', 'count' => Project::count(), 'route' => route('admin.projects.index'), 'icon' => 'folder', 'colors' => ['bg' => 'from-blue-500 to-blue-600', 'iconBg' => 'bg-blue-50', 'iconText' => 'text-blue-600']],
            ['label' => 'Kategori Projek', 'count' => ProjectCategory::count(), 'route' => route('admin.project-categories.index'), 'icon' => 'tag', 'colors' => ['bg' => 'from-indigo-500 to-indigo-600', 'iconBg' => 'bg-indigo-50', 'iconText' => 'text-indigo-600']],
        ];

        $latestProjects = Project::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('contentSummary', 'latestProjects'));
    }
}
