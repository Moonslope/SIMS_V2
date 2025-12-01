<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $teachers = Teacher::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('contact_number', 'LIKE', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('last_name', 'asc')
            ->paginate(10);

        return view('academics.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academics.teachers.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TeacherRequest $teacherRequest)
    {
        $validated = $teacherRequest->validated();
        $teacher = Teacher::create($validated);

        // Log activity
        ActivityLogService::created($teacher, "Created teacher: '{$teacher->first_name} {$teacher->last_name}'");

        return redirect()->route('teachers.index')->with('success', 'New Teacher has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('academics.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherRequest $teacherRequest, Teacher $teacher)
    {
        $oldTeacherName = "{$teacher->first_name} {$teacher->last_name}";

        $validated = $teacherRequest->validated();
        $teacher->update($validated);

        // Log activity
        $newTeacherName = "{$teacher->first_name} {$teacher->last_name}";
        if ($oldTeacherName !== $newTeacherName) {
            ActivityLogService::updated($teacher, "Updated teacher from '{$oldTeacherName}' to '{$newTeacherName}'");
        } else {
            ActivityLogService::updated($teacher, "Updated teacher: '{$newTeacherName}'");
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher details has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'The selected Teacher has been deleted successfully.');
    }
}
