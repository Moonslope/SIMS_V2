<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\ProgramType;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');
        $gradeLevelFilter = $request->input('grade_level');
        $programTypeFilter = $request->input('program_type');

        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        $schedules = Schedule::with([
            'section',
            'academicYear',
            'subject',
            'gradeLevel',
            'programType'
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search day of week
                    $q->where('day_of_the_week', 'LIKE', "%{$search}%")
                        // Search start time
                        ->orWhere('start_time', 'LIKE', "%{$search}%")
                        // Search end time
                        ->orWhere('end_time', 'LIKE', "%{$search}%")

                        // Search in subject
                        ->orWhereHas('subject', function ($q) use ($search) {
                            $q->where('subject_name', 'LIKE', "%{$search}%");
                        })
                        // Search in grade level
                        ->orWhereHas('gradeLevel', function ($q) use ($search) {
                            $q->where('grade_name', 'LIKE', "%{$search}%");
                        })
                        // Search in program type
                        ->orWhereHas('programType', function ($q) use ($search) {
                            $q->where('program_name', 'LIKE', "%{$search}%");
                        })
                        // Search in academic year
                        ->orWhereHas('academicYear', function ($q) use ($search) {
                            $q->where('year_name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($academicYearFilter && $academicYearFilter !== 'all', function ($query) use ($academicYearFilter) {
                $query->where('academic_year_id', $academicYearFilter);
            })
            ->when($gradeLevelFilter && $gradeLevelFilter !== 'all', function ($query) use ($gradeLevelFilter) {
                $query->where('grade_level_id', $gradeLevelFilter);
            })
            ->when($programTypeFilter && $programTypeFilter !== 'all', function ($query) use ($programTypeFilter) {
                $query->where('program_type_id', $programTypeFilter);
            })
            ->orderBy('day_of_the_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return view('student_management.schedules.index', compact('schedules', 'academicYears', 'gradeLevels', 'programTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentAcademicYear = AcademicYear::latest()->first();
        $subjects = Subject::where('is_active', true)->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('student_management.schedules.create', compact(
            'currentAcademicYear',
            'subjects',
            'gradeLevels',
            'programTypes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ScheduleRequest $scheduleRequest)
    {
        $validated = $scheduleRequest->validated();

        // Check if it's mon-fri option
        if ($request->day_of_the_week === 'monday_to_friday') {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            foreach ($days as $day) {
                $schedule = Schedule::create([
                    'grade_level_id' => $validated['grade_level_id'],
                    'subject_id' => $validated['subject_id'],
                    'academic_year_id' => $validated['academic_year_id'],
                    'program_type_id' => $validated['program_type_id'],
                    'day_of_the_week' => $day,
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'is_active' => $validated['is_active'],
                ]);
            }

            // Log activity
            ActivityLogService::custom("Created schedules for Monday to Friday: {$schedule->subject->subject_name}");

            return redirect()->route('schedules.index')
                ->with('success', 'Schedules created successfully for Monday to Friday');
        }

        // Single day schedule
        $schedule = Schedule::create($validated);

        // Log activity
        ActivityLogService::created($schedule, "Created schedule: {$schedule->subject->subject_name} on {$schedule->day_of_the_week}");

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        // Format times to H:i (remove seconds)
        $schedule->start_time = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
        $schedule->end_time = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');

        $subjects = Subject::where('is_active', true)->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('student_management.schedules.edit', compact(
            'schedule',
            'subjects',
            'gradeLevels',
            'programTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $scheduleRequest, Schedule $schedule)
    {
        $oldDay = $schedule->day_of_the_week;
        $oldSubject = $schedule->subject->subject_name;

        $validated = $scheduleRequest->validated();

        $schedule->update($validated);

        // Log activity
        $newSubject = $schedule->subject->subject_name;
        if ($oldDay !== $schedule->day_of_the_week || $oldSubject !== $newSubject) {
            ActivityLogService::updated($schedule, "Updated schedule from '{$oldSubject} on {$oldDay}' to '{$newSubject} on {$schedule->day_of_the_week}'");
        } else {
            ActivityLogService::updated($schedule, "Updated schedule: {$newSubject} on {$schedule->day_of_the_week}");
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage. 
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }
}
