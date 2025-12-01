<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        return view('layout.admin-layout');
    }

    public function allStudents()
    {
        return view('student_management.all-student');
    }

    public function gradeLevels()
    {
        return view('academics.grade_level.grade-levels');
    }

    public function dashboard()
    {
        // Get current and previous academic years
        $currentYear = AcademicYear::where('is_active', true)->first();
        $previousYear = AcademicYear::where('is_active', false)
            ->orderBy('start_date', 'desc')
            ->first();

        // Total students for current year
        $totalStudents = 0;
        $regularStudents = 0;
        $spedStudents = 0;
        $previousTotalStudents = 0;

        if ($currentYear) {
            $totalStudents = Enrollment::where('academic_year_id', $currentYear->id)->count();
            $regularStudents = Enrollment::where('academic_year_id', $currentYear->id)
                ->whereHas('programType', function ($query) {
                    $query->where('program_name', 'like', '%regular%');
                })
                ->count();
            $spedStudents = Enrollment::where('academic_year_id', $currentYear->id)
                ->whereHas('programType', function ($query) {
                    $query->where('program_name', 'like', '%sped%')
                        ->orWhere('program_name', 'like', '%special%');
                })
                ->count();
        }

        if ($previousYear) {
            $previousTotalStudents = Enrollment::where('academic_year_id', $previousYear->id)->count();
        }

        // Calculate percentage change
        $studentChange = 0;
        $changePercentage = 0;
        if ($previousTotalStudents > 0) {
            $studentChange = $totalStudents - $previousTotalStudents;
            $changePercentage = round(($studentChange / $previousTotalStudents) * 100, 1);
        }

        // Grade level distribution for current year
        $gradeLevelDistribution = [];
        if ($currentYear) {
            $gradeLevelDistribution = GradeLevel::select(
                'grade_levels.grade_name',
                DB::raw('COUNT(enrollments.id) as student_count')
            )
                ->leftJoin('enrollments', function ($join) use ($currentYear) {
                    $join->on('grade_levels.id', '=', 'enrollments.grade_level_id')
                        ->where('enrollments.academic_year_id', '=', $currentYear->id);
                })
                ->groupBy('grade_levels.id', 'grade_levels.grade_name')
                ->orderBy('grade_levels.id')
                ->get();
        }

        // Recent activity logs
        $recentActivities = ActivityLog::with('user')
            ->orderBy('log_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'regularStudents',
            'spedStudents',
            'previousTotalStudents',
            'studentChange',
            'changePercentage',
            'gradeLevelDistribution',
            'recentActivities',
            'currentYear',
            'previousYear'
        ));
    }

    public function addGradeLevel()
    {
        return view('academics.grade_level.add');
    }
}
