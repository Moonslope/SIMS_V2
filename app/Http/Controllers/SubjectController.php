<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gradeLevelFilter = $request->input('grade_level');

        $subjects = Subject::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('subject_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhereHas('gradeLevel', function ($q) use ($search) {
                            $q->where('grade_name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($gradeLevelFilter && $gradeLevelFilter !== 'all', function ($query) use ($gradeLevelFilter) {
                $query->where('grade_level_id', $gradeLevelFilter);
            })
            ->orderBy('subject_name', 'asc')
            ->paginate(10);

        $gradeLevels = GradeLevel::where('is_active', true)->get();

        return view('academics.subjects.index', compact('subjects', 'gradeLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        return view('academics.subjects.add', compact('gradeLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SubjectRequest $subjectRequest)
    {
        $validated = $subjectRequest->validated();

        $createdCount = 0;

        // Loop through each subject and create it
        foreach ($validated['subjects'] as $subjectData) {
            $subject = Subject::create([
                'grade_level_id' => $validated['grade_level_id'],
                'subject_name' => $subjectData['subject_name'],
                'description' => $subjectData['description'] ?? null,
                'is_active' => $subjectData['is_active'],
            ]);

            // Log activity
            ActivityLogService::created($subject, "Created subject: '{$subject->subject_name}'");
            $createdCount++;
        }

        $message = $createdCount > 1
            ? "Successfully created {$createdCount} subjects."
            : "New subject has been added successfully.";

        return redirect()->route('subjects.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        return view('academics.subjects.edit', compact('subject', 'gradeLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject, SubjectRequest $subjectRequest)
    {
        $oldSubjectName = $subject->subject_name;

        $updated_data = $subjectRequest->validated();
        $subject->update($updated_data);

        // Log activity
        if ($oldSubjectName !== $subject->subject_name) {
            ActivityLogService::updated($subject, "Updated subject from '{$oldSubjectName}' to '{$subject->subject_name}'");
        } else {
            ActivityLogService::updated($subject, "Updated subject: '{$subject->subject_name}'");
        }

        return redirect()->route('subjects.index')->with('success', 'Subject details has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
