<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeLevelRequest;
use App\Models\GradeLevel;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $gradeLevels = GradeLevel::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('grade_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('grade_name', 'asc')->paginate(10);

        return view('academics.grade_level.grade-levels', compact('gradeLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academics.grade_level.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, GradeLevelRequest $gradeLevelRequest)
    {

        $validated = $gradeLevelRequest->validated();
        $gradeLevel = GradeLevel::create($validated);

        // Log activity
        ActivityLogService::created($gradeLevel, "Created grade level: '{$gradeLevel->grade_name}'");

        return redirect()->route('grade-levels.index')->with('success', 'New Grade Level has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeLevel $gradeLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradeLevel $gradeLevel)
    {

        $gradeLevel = GradeLevel::findOrFail($gradeLevel->id);
        return view('academics.grade_level.edit', compact('gradeLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradeLevel $gradeLevel, GradeLevelRequest $gradeLevelRequest)
    {
        $oldGradeName = $gradeLevel->grade_name;

        $updated_data = $gradeLevelRequest->validated();
        $gradeLevel->update($updated_data);

        // Log activity
        if ($oldGradeName !== $gradeLevel->grade_name) {
            ActivityLogService::updated($gradeLevel, "Updated grade level from '{$oldGradeName}' to '{$gradeLevel->grade_name}'");
        } else {
            ActivityLogService::updated($gradeLevel, "Updated grade level: '{$gradeLevel->grade_name}'");
        }

        return redirect()->route('grade-levels.index')->with('success', 'Grade Level has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeLevel $gradeLevel)
    {

        $gradeLevel->delete();
        return redirect()->route('grade-levels.index')->with('success', 'Grade Level has been deleted successfully.');
    }
}
