<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Teacher;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');
        $gradeLevelFilter = $request->input('grade_level');

        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();

        $sections = Section::with(['academicYear', 'gradeLevel', 'teacher'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search section name
                    $q->where('section_name', 'LIKE', "%{$search}%")
                        // Search capacity
                        ->orWhere('capacity', 'LIKE', "%{$search}%")
                        // Search in academic year relationship
                        ->orWhereHas('academicYear', function ($q) use ($search) {
                            $q->where('year_name', 'LIKE', "%{$search}%");
                        })
                        // Search in grade level relationship
                        ->orWhereHas('gradeLevel', function ($q) use ($search) {
                            $q->where('grade_name', 'LIKE', "%{$search}%");
                        })
                        // Search in teacher relationship
                        ->orWhereHas('teacher', function ($q) use ($search) {
                            $q->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('middle_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                                ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                });
            })
            ->when($academicYearFilter && $academicYearFilter !== 'all', function ($query) use ($academicYearFilter) {
                $query->where('academic_year_id', $academicYearFilter);
            })
            ->when($gradeLevelFilter && $gradeLevelFilter !== 'all', function ($query) use ($gradeLevelFilter) {
                $query->where('grade_level_id', $gradeLevelFilter);
            })
            ->orderBy('section_name', 'asc')
            ->paginate(10);

        return view('academics.sections.index', compact('sections', 'academicYears', 'gradeLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        return view('academics.sections.add', compact('gradeLevels', 'teachers', 'activeAcademicYear'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SectionRequest $sectionRequest)
    {
        $validated = $sectionRequest->validated();

        $createdCount = 0;

        // Loop through each section and create it
        foreach ($validated['sections'] as $sectionData) {
            $section = Section::create([
                'grade_level_id' => $validated['grade_level_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'section_name' => $sectionData['section_name'],
                'teacher_id' => $sectionData['teacher_id'],
                'capacity' => $sectionData['capacity'],
                'is_active' => $sectionData['is_active'],
            ]);

            // Log activity
            ActivityLogService::created($section, "Created section: '{$section->section_name}'");
            $createdCount++;
        }

        $message = $createdCount > 1
            ? "Successfully created {$createdCount} sections."
            : "New Section has been added successfully.";

        return redirect()->route('sections.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();

        return view('academics.sections.edit', compact('gradeLevels', 'teachers', 'section'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section, SectionRequest $sectionRequest)
    {
        $oldSectionName = $section->section_name;

        $updated_data = $sectionRequest->validated();
        $section->update($updated_data);

        // Log activity
        if ($oldSectionName !== $section->section_name) {
            ActivityLogService::updated($section, "Updated section from '{$oldSectionName}' to '{$section->section_name}'");
        } else {
            ActivityLogService::updated($section, "Updated section: '{$section->section_name}'");
        }

        return redirect()->route('sections.index')->with('success', 'Sections details has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'The selected section has been deleted successfully');
    }
}
