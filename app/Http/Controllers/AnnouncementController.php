<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $announcements = Announcement::with('publisher')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search title
                    $q->where('title', 'LIKE', "%{$search}%")
                        // Search body/content
                        ->orWhere('body', 'LIKE', "%{$search}%")
                        // Search announcement date
                        ->orWhere('announcement_date', 'LIKE', "%{$search}%")
                        // Search in publisher (user) relationship
                        ->orWhereHas('publisher', function ($q) use ($search) {
                            $q->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"]);
                        });
                });
            })
            ->orderBy('announcement_date', 'desc')
            ->paginate(10);

        return view('system.announcement.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('system.announcement.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'announcement_date' => 'required|date',
        ]);

        $validated['publishedBy'] = Auth::id();

        $announcement = Announcement::create($validated);

        // Log activity
        ActivityLogService::created($announcement, "Created announcement: '{$announcement->title}'");

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully! ');
    }

    /**
     * Display the specified resource. 
     */
    public function show(Announcement $announcement)
    {
        // Log view activity
        ActivityLogService::custom("Viewed announcement: '{$announcement->title}'");

        return view('system.announcement.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('system.announcement.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $oldTitle = $announcement->title;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'announcement_date' => 'required|date',
        ]);

        $announcement->update($validated);

        // Log activity
        if ($oldTitle !== $announcement->title) {
            ActivityLogService::updated($announcement, "Updated announcement from '{$oldTitle}' to '{$announcement->title}'");
        } else {
            ActivityLogService::updated($announcement, "Updated announcement: '{$announcement->title}'");
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;

        // Log activity before deletion
        ActivityLogService::deleted($announcement, "Deleted announcement: '{$title}'");

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}
