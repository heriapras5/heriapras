<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\Client;
use App\Models\ContactInfo;
use App\Models\CtaSection;
use App\Models\HeroSection;
use App\Models\Menu;
use App\Models\Partner;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\TimelineItem;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    protected array $map = [
        'hero_sections' => [HeroSection::class, 'status'],
        'projects' => [Project::class, 'status'],
        'project_categories' => [ProjectCategory::class, 'status'],
        'clients' => [Client::class, 'status'],
        'partners' => [Partner::class, 'status'],
        'menus' => [Menu::class, 'status'],
        'contact_info' => [ContactInfo::class, 'status'],
        'cta_sections' => [CtaSection::class, 'is_active'],
        'about_sections' => [AboutSection::class, 'is_active'],
        'portfolio_timeline_items' => [TimelineItem::class, 'is_active'],
    ];

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'resource' => 'required|string',
            'id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if (! isset($this->map[$data['resource']])) {
            return response()->json(['success' => false, 'message' => 'Resource tidak dikenal.'], 422);
        }

        [$model, $column] = $this->map[$data['resource']];
        $item = $model::find($data['id']);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $item->update([$column => (bool) $data['status']]);

        return response()->json(['success' => true]);
    }
}
