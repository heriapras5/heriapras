<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    protected array $map = [
        'projects' => [Project::class, 'status'],
        'project_categories' => [ProjectCategory::class, 'status'],
        'services' => [Service::class, 'status'],
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
