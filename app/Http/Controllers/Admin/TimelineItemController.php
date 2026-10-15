<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineItem;
use Illuminate\Http\Request;

class TimelineItemController extends Controller
{
    public function index()
    {
        $items = TimelineItem::orderBy('sort_order')->orderBy('id')->paginate(20);

        return view('admin.timeline.index', compact('items'));
    }

    public function create()
    {
        return view('admin.timeline.form', ['item' => new TimelineItem]);
    }

    public function store(Request $request)
    {
        TimelineItem::create($this->validated($request));

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil ditambahkan.');
    }

    public function edit(TimelineItem $timeline)
    {
        return view('admin.timeline.form', ['item' => $timeline]);
    }

    public function update(Request $request, TimelineItem $timeline)
    {
        $timeline->update($this->validated($request));

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil diperbarui.');
    }

    public function destroy(TimelineItem $timeline)
    {
        $timeline->delete();

        return redirect()->route('admin.timeline.index')->with('success', 'Timeline berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => 'required|in:education,experience,learning',
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
