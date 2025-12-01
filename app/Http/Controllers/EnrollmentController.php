<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\AcademicYear;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Enrollment;
use App\Models\EnrollmentSchedule;
use App\Models\EnrollmentSubject;
use App\Models\FeeStructure;
use App\Models\GradeLevel;
use App\Models\Payment;
use App\Models\ProgramType;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAccountCreated;

class EnrollmentController extends Controller
{

    // Add this method to your existing EnrollmentController

    public function generateReport(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');
        $gradeLevelFilter = $request->input('grade_level');
        $programTypeFilter = $request->input('program_type');
        $sectionFilter = $request->input('section');

        // Get filter data
        $academicYear = null;
        $academicYearName = 'All Academic Years';

        if ($academicYearFilter && $academicYearFilter !== 'all') {
            $academicYear = AcademicYear::find($academicYearFilter);
            $academicYearName = $academicYear ?  $academicYear->year_name : 'N/A';
        }

        $gradeLevel = $gradeLevelFilter && $gradeLevelFilter !== 'all'
            ? GradeLevel::find($gradeLevelFilter)
            : null;
        $programType = $programTypeFilter && $programTypeFilter !== 'all'
            ?   ProgramType::find($programTypeFilter)
            : null;

        // Build query
        $enrollments = Enrollment::with(['student', 'gradeLevel', 'programType', 'section', 'academicYear'])
            ->when($search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('learner_reference_number', 'like', "%{$search}%");
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
            ->when($sectionFilter && $sectionFilter !== 'all', function ($query) use ($sectionFilter) {
                $query->where('section_id', $sectionFilter);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalEnrollments = $enrollments->count();

        $maleCount = $enrollments->filter(function ($enrollment) {
            $gender = strtolower($enrollment->student->gender ??  '');
            return in_array($gender, ['male', 'm']);
        })->count();

        $femaleCount = $enrollments->filter(function ($enrollment) {
            $gender = strtolower($enrollment->student->gender ?? '');
            return in_array($gender, ['female', 'f']);
        })->count();

        // Group by grade level
        $byGradeLevel = $enrollments->groupBy('grade_level_id')->map(function ($group) {
            return [
                'name' => $group->first()->gradeLevel->grade_name ??   'N/A',
                'count' => $group->count()
            ];
        });

        // Log report generation
        ActivityLogService::custom("Generated Enrollment Report - AY: {$academicYearName} - Total: {$totalEnrollments} enrollments");

        return view('reports.enrollment-report', compact(
            'enrollments',
            'academicYear',
            'academicYearName',
            'gradeLevel',
            'programType',
            'totalEnrollments',
            'maleCount',
            'femaleCount',
            'byGradeLevel'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');
        $gradeLevelFilter = $request->input('grade_level');
        $programTypeFilter = $request->input('program_type');
        $sectionFilter = $request->input('section');
        $statusFilter = $request->input('status');

        // Get filter options
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $gradeLevels = GradeLevel::where('is_active', true)->orderBy('grade_name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('program_name')->get();
        $sections = Section::where('is_active', true)->orderBy('section_name')->get();

        // Start query with relationships
        $enrollments = Enrollment::with(['student', 'gradeLevel', 'programType', 'section', 'academicYear'])
            // Search filter
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('student', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('learner_reference_number', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                            ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    })
                        // Search in section
                        ->orWhereHas('section', function ($q) use ($search) {
                            $q->where('section_name', 'LIKE', "%{$search}%");
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
                        })
                        // Search enrollment status
                        ->orWhere('enrollment_status', 'LIKE', "%{$search}%");
                });
            })
            // Academic Year filter
            ->when($academicYearFilter && $academicYearFilter !== 'all', function ($query) use ($academicYearFilter) {
                $query->where('academic_year_id', $academicYearFilter);
            })
            // Grade Level filter
            ->when($gradeLevelFilter && $gradeLevelFilter !== 'all', function ($query) use ($gradeLevelFilter) {
                $query->where('grade_level_id', $gradeLevelFilter);
            })
            // Program Type filter
            ->when($programTypeFilter && $programTypeFilter !== 'all', function ($query) use ($programTypeFilter) {
                $query->where('program_type_id', $programTypeFilter);
            })
            // Section filter
            ->when($sectionFilter && $sectionFilter !== 'all', function ($query) use ($sectionFilter) {
                $query->where('section_id', $sectionFilter);
            })
            // Status filter
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->where('enrollment_status', $statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student_management.enrollment.index', compact(
            'enrollments',
            'academicYears',
            'gradeLevels',
            'programTypes',
            'sections'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $source = $request->get('source');
        $studentId = $request->get('student');
        $student = $studentId ? Student::find($studentId) : null;

        $currentAcademicYear = AcademicYear::latest()->first();
        $programTypes = ProgramType::where('is_active', true)->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();

        return view('student_management.enrollment.create', compact(
            'currentAcademicYear',
            'programTypes',
            'gradeLevels',
            'sections',
            'student',
            'source'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EnrollmentRequest $enrollmentRequest)
    {
        $validated = $enrollmentRequest->validated();
        $source = $request->source;

        // First, get the student data to create user account
        $student = Student::with('guardians')->find($validated['student_id']);

        $accountCreated = false;
        $temporaryPassword = '12345678';

        // Check if user already exists for this student
        if (! $student->user_id) {
            // Create user account for the student only if they don't have one
            $user = User::create([
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'email' => strtolower($student->first_name . $student->last_name) . '@gmail.com',
                'password' => Hash::make($temporaryPassword),
                'role' => 'student'
            ]);

            // Update student with user_id
            $student->update(['user_id' => $user->id]);

            $accountCreated = true;

            // Log user account creation
            ActivityLogService::created($user, "Created student account for: {$student->first_name} {$student->last_name} (LRN: {$student->learner_reference_number})");

            // Send email to parent/guardian (synchronous for now until queue is verified)
            try {
                $guardian = $student->guardians()->first();

                if ($guardian && $guardian->email) {
                    Mail::to($guardian->email)->send(
                        new StudentAccountCreated($student, $user, $temporaryPassword)
                    );
                }
            } catch (\Exception $e) {
                // Silently fail - don't stop enrollment if email fails
                Log::error('Failed to send student account email: ' . $e->getMessage());
            }
        }

        // Create the enrollment
        $enrollment = Enrollment::create($validated);

        // Get academic year and grade level names for logging
        $academicYear = AcademicYear::find($validated['academic_year_id']);
        $gradeLevel = GradeLevel::find($validated['grade_level_id']);
        $section = Section::find($validated['section_id']);

        // Log enrollment creation
        ActivityLogService::created(
            $enrollment,
            "Enrolled student: {$student->first_name} {$student->last_name} (LRN: {$student->learner_reference_number}) - Grade: {$gradeLevel->grade_name}, Section: {$section->section_name}, AY: {$academicYear->year_name}"
        );

        // Handle schedules based on source
        if ($source === 'sped') {
            $schedules = Schedule::where('academic_year_id', $validated['academic_year_id'])
                ->where('program_type_id', $validated['program_type_id'])
                ->get();
            $subjects = collect();
        } else {
            $schedules = Schedule::where('grade_level_id', $validated['grade_level_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->get();

            $subjects = Subject::where('grade_level_id', $validated['grade_level_id'])
                ->get();
        }

        // Create enrollment schedules
        foreach ($schedules as $schedule) {
            EnrollmentSchedule::create([
                'enrollment_id' => $enrollment->id,
                'schedule_id' => $schedule->id
            ]);
        }

        // Create enrollment subjects only for regular students
        if ($source !== 'sped') {
            foreach ($subjects as $subject) {
                EnrollmentSubject::create([
                    'enrollment_id' => $enrollment->id,
                    'subject_id' => $subject->id
                ]);
            }
        }

        // Create billing and billing items
        if ($source === 'sped') {
            $feeStructures = FeeStructure::where('program_type_id', $validated['program_type_id'])
                ->where('is_active', true)
                ->get();
        } else {
            $feeStructures = FeeStructure::where('grade_level_id', $validated['grade_level_id'])
                ->where('program_type_id', $validated['program_type_id'])
                ->where('is_active', true)
                ->get();
        }

        // Calculate total amount
        $totalAmount = $feeStructures->sum('amount');

        // Create billing record
        $billing = Billing::create([
            'enrollment_id' => $enrollment->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'created_date' => now()->format('Y-m-d')
        ]);

        // Create billing items for each fee structure
        foreach ($feeStructures as $feeStructure) {
            BillingItem::create([
                'billing_id' => $billing->id,
                'fee_structure_id' => $feeStructure->id,
                'amount' => $feeStructure->amount
            ]);
        }

        $message = 'Student has been enrolled successfully. ';
        if ($accountCreated) {
            $guardian = $student->guardians()->first();
            if ($guardian && $guardian->email) {
                $message .= ' Account credentials have been sent to ' .  $guardian->email . '. ';
            } else {
                $message .= ' User account created but no guardian email found.';
            }
        }

        return redirect()->route('enrollments.billing', ['enrollment' => $enrollment->id])
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        $schedules = EnrollmentSchedule::where('enrollment_id', $enrollment->id)->with('schedule.subject')->get();
        $subjects = EnrollmentSubject::where('enrollment_id', $enrollment->id)->get();
        $billing = Billing::where('enrollment_id', $enrollment->id)->with(['billingItems.feeStructure'])->first();

        // Get payment
        $payments = null;
        $totalPaid = 0;
        $paymentAmount = 0;

        if ($billing) {
            $payments = Payment::where('billing_id', $billing->id)->get();
            $totalPaid = $payments->sum('amount_paid');
            $paymentAmount = $totalPaid;
        }

        // Get all unique time slots
        $timeSlots = [];
        foreach ($schedules as $schedule) {
            $timeSlot = $schedule->schedule->start_time . ' - ' . $schedule->schedule->end_time;
            if (!in_array($timeSlot, $timeSlots)) {
                $timeSlots[] = $timeSlot;
            }
        }

        $scheduleData = [];
        foreach ($timeSlots as $timeSlot) {
            [$startTime, $endTime] = explode(' - ', $timeSlot);

            // Format time to AM/PM
            $formattedStart = date('g:iA', strtotime($startTime));
            $formattedEnd = date('g:iA', strtotime($endTime));
            $formattedTimeSlot = $formattedStart . ' - ' . $formattedEnd;

            // Calculate minutes
            $start = strtotime($startTime);
            $end = strtotime($endTime);
            $minutes = ($end - $start) / 60;

            // Find subjects for each day in this time slot
            $subjectsByDay = [
                'Monday' => '',
                'Tuesday' => '',
                'Wednesday' => '',
                'Thursday' => '',
                'Friday' => ''
            ];

            foreach ($schedules as $schedule) {
                if (
                    $schedule->schedule->start_time == $startTime &&
                    $schedule->schedule->end_time == $endTime
                ) {
                    $day = $schedule->schedule->day_of_the_week;
                    $subjectsByDay[$day] = $schedule->schedule->subject->subject_name;
                }
            }

            $scheduleData[] = [
                'time_slot' => $formattedTimeSlot,
                'minutes' => $minutes,
                'subjects' => $subjectsByDay
            ];
        }

        return view('student_management.enrollment.show', compact(
            'enrollment',
            'subjects',
            'scheduleData',
            'billing',
            'totalPaid',
            'paymentAmount'
        ));
    }

    public function showBilling(Enrollment $enrollment)
    {
        // Get billing information
        $billing = Billing::where('enrollment_id', $enrollment->id)
            ->with(['billingItems.feeStructure'])
            ->first();

        // Get payments if billing exists
        $payments = null;
        $totalPaid = 0;
        $paymentAmount = 0;

        if ($billing) {
            $payments = Payment::where('billing_id', $billing->id)->get();
            $totalPaid = $payments->sum('amount_paid');

            $latestPayment = $payments->last();
            $paymentAmount = $latestPayment ? $latestPayment->amount_paid : 0;
        }

        return view('student_management.enrollment.showBilling', compact(
            'enrollment',
            'billing',
            'payments',
            'totalPaid',
            'paymentAmount'
        ));
    }

    /**
     * Re-enrollment Form - Show form for re-enrolling old students
     */
    public function reEnrollmentForm()
    {
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        // Get active academic year
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        return view('student_management.enrollment.re-enrollment', compact(
            'academicYears',
            'gradeLevels',
            'sections',
            'programTypes',
            'activeAcademicYear'
        ));
    }

    /**
     * Get sections by grade level with capacity info (AJAX endpoint)
     */
    public function getSectionsByGradeLevel(Request $request)
    {
        $gradeLevelId = $request->get('grade_level_id');
        $academicYearId = $request->get('academic_year_id');

        if (!$gradeLevelId || ! $academicYearId) {
            return response()->json([]);
        }

        $sections = Section::where('grade_level_id', $gradeLevelId)
            ->where('academic_year_id', $academicYearId)
            ->where('is_active', true)
            ->with(['enrollments' => function ($query) use ($academicYearId) {
                $query->where('academic_year_id', $academicYearId);
            }])
            ->get();

        $sectionsData = $sections->map(function ($section) {
            $enrolledCount = $section->enrollments->count();
            $capacity = $section->capacity;
            $available = $capacity - $enrolledCount;

            // Determine status color
            $percentage = ($enrolledCount / $capacity) * 100;
            if ($percentage >= 100) {
                $status = 'full';
                $color = 'error';
            } elseif ($percentage >= 80) {
                $status = 'almost-full';
                $color = 'warning';
            } else {
                $status = 'available';
                $color = 'success';
            }

            return [
                'id' => $section->id,
                'section_name' => $section->section_name,
                'capacity' => $capacity,
                'enrolled' => $enrolledCount,
                'available' => $available,
                'status' => $status,
                'color' => $color,
                'display' => "{$section->section_name} ({$enrolledCount}/{$capacity})"
            ];
        });

        return response()->json($sectionsData);
    }

    /**
     * Search for existing students
     */
    public function searchStudents(Request $request)
    {
        $query = trim($request->input('query', ''));

        // Active academic year drives who is eligible to show up for re-enrollment
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // Base student search
        $studentsQuery = Student::query()
            ->where(function ($q) use ($query) {
                $q->where('learner_reference_number', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%");
            });

        // Exclude students already enrolled in the active academic year
        if ($activeAcademicYear) {
            $studentsQuery->whereDoesntHave('enrollments', function ($q) use ($activeAcademicYear) {
                $q->where('academic_year_id', $activeAcademicYear->id);
            });
        }

        $students = $studentsQuery
            ->orderBy('last_name')
            ->limit(10)
            ->get();

        // Keep passing data to the same view
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('student_management.enrollment.re-enrollment', compact(
            'students',
            'academicYears',
            'gradeLevels',
            'sections',
            'programTypes',
            'activeAcademicYear'
        ));
    }

    /**
     * Create Re-enrollment for existing student
     */
    public function createReEnrollment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'required|exists:sections,id',
            'program_type_id' => 'required|exists:program_types,id',
        ]);

        $student = Student::find($validated['student_id']);
        $academicYear = AcademicYear::find($validated['academic_year_id']);
        $gradeLevel = GradeLevel::find($validated['grade_level_id']);
        $section = Section::find($validated['section_id']);

        // Check if student is eligible to enroll
        $latestEnrollment = Enrollment::where('student_id', $student->id)
            ->latest('date_enrolled')
            ->first();

        if ($latestEnrollment) {
            $billing = Billing::where('enrollment_id', $latestEnrollment->id)->first();

            if ($billing) {
                $totalPaid = Payment::where('billing_id', $billing->id)->sum('amount_paid');
                $remainingBalance = $billing->total_amount - $totalPaid;

                if ($remainingBalance > 0) {
                    // Log failed re-enrollment attempt
                    ActivityLogService::custom("Failed re-enrollment attempt for {$student->first_name} {$student->last_name} (LRN: {$student->learner_reference_number}) - Unpaid balance: ₱" . number_format($remainingBalance, 2));

                    return redirect()->back()->with('error', 'Student has an unpaid balance of ₱' . number_format($remainingBalance, 2) . '. Please settle the balance before re-enrolling.');
                }
            }
        }

        // Check if already enrolled in this year
        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'Student is already enrolled in ' . $academicYear->year_name);
        }

        // Create new enrollment
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'grade_level_id' => $validated['grade_level_id'],
            'section_id' => $validated['section_id'],
            'program_type_id' => $validated['program_type_id'],
            'enrollment_status' => 'enrolled',
            'date_enrolled' => now(),
            'createdBy' => Auth::id(),
        ]);

        // Log re-enrollment
        ActivityLogService::created(
            $enrollment,
            "Re-enrolled student: {$student->first_name} {$student->last_name} (LRN: {$student->learner_reference_number}) - Grade: {$gradeLevel->grade_name}, Section: {$section->section_name}, AY: {$academicYear->year_name}"
        );

        // Get schedules and assign to enrollment
        $schedules = Schedule::where('grade_level_id', $validated['grade_level_id'])
            ->where('academic_year_id', $academicYear->id)
            ->get();

        foreach ($schedules as $schedule) {
            EnrollmentSchedule::create([
                'enrollment_id' => $enrollment->id,
                'schedule_id' => $schedule->id
            ]);
        }

        // Get subjects and assign to enrollment
        $subjects = Subject::where('grade_level_id', $validated['grade_level_id'])->get();

        foreach ($subjects as $subject) {
            EnrollmentSubject::create([
                'enrollment_id' => $enrollment->id,
                'subject_id' => $subject->id
            ]);
        }

        // Get fee structures and create billing
        $feeStructures = FeeStructure::where('grade_level_id', $validated['grade_level_id'])
            ->where('program_type_id', $validated['program_type_id'])
            ->where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->get();

        $totalAmount = $feeStructures->sum('amount');

        // Create billing record
        $billing = Billing::create([
            'enrollment_id' => $enrollment->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'created_date' => now()->format('Y-m-d')
        ]);

        // Log billing creation for re-enrollment
        ActivityLogService::created(
            $billing,
            "Created billing for re-enrollment ID: {$enrollment->id} - Total Amount: ₱" .  number_format($totalAmount, 2)
        );

        // Create billing items
        foreach ($feeStructures as $feeStructure) {
            BillingItem::create([
                'billing_id' => $billing->id,
                'fee_structure_id' => $feeStructure->id,
                'amount' => $feeStructure->amount
            ]);
        }

        return redirect()->route('enrollments.billing', ['enrollment' => $enrollment->id])
            ->with('success', "Student re-enrolled successfully in {$academicYear->year_name}!");
    }

    /**
     * Show the form for editing the specified resource. 
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage. 
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage. 
     */
    public function destroy(Enrollment $enrollment)
    {
        //
    }

    public function enrollmentsReport(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');
        $gradeLevelFilter = $request->input('grade_level');
        $programTypeFilter = $request->input('program_type');

        $enrollments = Enrollment::with(['student', 'gradeLevel', 'programType', 'section', 'academicYear'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('student', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('learner_reference_number', 'like', "%{$search}%");
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
            ->orderBy('created_at', 'desc')
            ->get();

        $currentAcademicYear = AcademicYear::where('is_active', true)->first();
        $reportTitle = 'Enrollment List Report';
        $reportDate = now()->format('F d, Y');

        return view('reports.enrollments-report', compact('enrollments', 'reportTitle', 'reportDate', 'currentAcademicYear'));
    }
}
