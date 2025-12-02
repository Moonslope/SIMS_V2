<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\StudentGuardian;
use App\Models\Document;
use App\Models\SpedStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RegularStudentStep1Request;
use App\Http\Requests\RegularStudentStep2Request;
use App\Http\Requests\RegularStudentStep3Request;
use App\Http\Requests\SpedStudentStep1Request;
use App\Http\Requests\SpedStudentStep2Request;
use App\Http\Requests\SpedStudentStep3Request;
use App\Models\Announcement;
use App\Models\AcademicYear;
use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\EnrollmentSchedule;
use App\Models\EnrollmentSubject;
use App\Models\Payment;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Show student profile
     */
    public function studentProfile(Student $student)
    {
        $student->load([
            'guardians',
            'documents',
            'enrollments.academicYear',
            'enrollments.gradeLevel',
            'enrollments.section',
            'enrollments.programType',
        ]);

        // Get latest enrollment
        $latestEnrollment = $student->enrollments()->latest('date_enrolled')->first();

        // Get billing info if enrolled
        $billing = null;
        $totalPaid = 0;
        $balance = 0;

        if ($latestEnrollment) {
            $billing = Billing::where('enrollment_id', $latestEnrollment->id)->first();
            if ($billing) {
                $totalPaid = Payment::where('billing_id', $billing->id)->sum('amount_paid');
                $balance = $billing->total_amount - $totalPaid;
            }
        }

        // Get documents
        $documents = $student->documents;

        return view('student_management.students.student-profile', compact(
            'student',
            'latestEnrollment',
            'billing',
            'totalPaid',
            'balance',
            'documents'
        ));
    }

    /**
     * View/Download student document
     */
    public function viewDocument(Student $student, Document $document)
    {
        // Verify the document belongs to this student
        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized access to document');
        }

        // If file_path is a Cloudinary URL, redirect to it
        if (str_starts_with($document->file_path, 'https://res.cloudinary.com') || str_starts_with($document->file_path, 'http://res.cloudinary.com')) {
            return redirect($document->file_path);
        }

        // Handle local storage files
        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Document file not found on server.');
        }

        return response()->file($filePath);
    }


    public function profile()
    {
        $student = Student::where('user_id', Auth::id())
            ->with(['documents', 'guardians'])
            ->first();
        $documents = $student ? $student->documents : collect();
        $guardians = $student ? $student->guardians : collect();

        return view('student_portal.profile', compact('student', 'documents', 'guardians'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('student_portal.change-password');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['error' => 'User not authenticated.']);
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log activity
        ActivityLogService::custom("Changed password for user: {$user->first_name} {$user->last_name}");

        return back()->with('success', 'Password changed successfully!');
    }

    public function announcements()
    {
        $announcements = Announcement::orderBy('announcement_date', 'desc')
            ->paginate(10);

        return view('student_portal.announcement', compact('announcements'));
    }

    public function studentPortal()
    {
        $student = Auth::user()->student;

        // Get the selected academic year from session
        $selectedAcademicYearId = session('selected_academic_year_id');

        if ($selectedAcademicYearId) {
            $enrollment = Enrollment::where('student_id', $student->id)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->with(['academicYear', 'programType', 'gradeLevel'])
                ->first();
        } else {
            // Get the active academic year first
            $activeAcademicYear = AcademicYear::where('is_active', true)->first();

            if ($activeAcademicYear) {
                // Try to get enrollment for active academic year
                $enrollment = Enrollment::where('student_id', $student->id)
                    ->where('academic_year_id', $activeAcademicYear->id)
                    ->with(['academicYear', 'programType', 'gradeLevel'])
                    ->first();

                // If not found, get the latest enrollment
                if (!$enrollment) {
                    $enrollment = Enrollment::where('student_id', $student->id)
                        ->with(['academicYear', 'programType', 'gradeLevel'])
                        ->latest('date_enrolled')
                        ->first();
                }
            } else {
                // If no active academic year, just get the latest
                $enrollment = Enrollment::where('student_id', $student->id)
                    ->with(['academicYear', 'programType', 'gradeLevel'])
                    ->latest('date_enrolled')
                    ->first();
            }

            // Store in session for future use
            if ($enrollment) {
                session(['selected_academic_year_id' => $enrollment->academic_year_id]);
            }
        }

        $totalAssessment = 0;
        $totalPaid = 0;
        $currentBalance = 0;
        $isOldSchoolYear = false;

        if ($enrollment) {
            $billing = Billing::where('enrollment_id', $enrollment->id)->first();

            if ($billing) {
                $totalAssessment = $billing->total_amount;
                $totalPaid = Payment::where('billing_id', $billing->id)->sum('amount_paid');
                $currentBalance = $totalAssessment - $totalPaid;
            }

            // Check if viewing old school year by comparing academic year end dates
            $latestAcademicYear = AcademicYear::orderBy('end_date', 'desc')->first();

            if ($latestAcademicYear && $enrollment->academicYear && $enrollment->academicYear->id !== $latestAcademicYear->id) {
                $isOldSchoolYear = true;
            }
        }

        return view('student_portal.dashboard', compact(
            'student',
            'enrollment',
            'totalAssessment',
            'totalPaid',
            'currentBalance',
            'isOldSchoolYear'
        ));
    }

    public function classSchedule()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found.');
        }

        // Get the selected academic year from session, or use the latest
        $selectedAcademicYearId = session('selected_academic_year_id');

        if ($selectedAcademicYearId) {
            $currentEnrollment = Enrollment::where('student_id', $student->id)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->with(['gradeLevel', 'section', 'academicYear', 'programType'])
                ->first();
        } else {
            $currentEnrollment = Enrollment::where('student_id', $student->id)
                ->with(['gradeLevel', 'section', 'academicYear', 'programType'])
                ->latest('date_enrolled')
                ->first();
        }

        if (!$currentEnrollment) {
            return view('student_portal.classSchedule', compact('student'))->with('error', 'No active enrollment found.');
        }

        $schedules = EnrollmentSchedule::where('enrollment_id', $currentEnrollment->id)
            ->with('schedule.subject')
            ->get();

        $subjects = EnrollmentSubject::where('enrollment_id', $currentEnrollment->id)
            ->with('subject')
            ->get()
            ->pluck('subject');

        $currentAcademicYear = $currentEnrollment->academicYear;

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

            $formattedStart = date('g:iA', strtotime($startTime));
            $formattedEnd = date('g:iA', strtotime($endTime));
            $formattedTimeSlot = $formattedStart . ' - ' . $formattedEnd;

            $start = strtotime($startTime);
            $end = strtotime($endTime);
            $minutes = ($end - $start) / 60;

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

                    // Handle "Monday to Friday" by populating all weekdays
                    if ($day === 'Monday to Friday') {
                        $subjectName = $schedule->schedule->subject->subject_name ?? $schedule->schedule->subject->name ?? 'N/A';
                        $subjectsByDay['Monday'] = $subjectName;
                        $subjectsByDay['Tuesday'] = $subjectName;
                        $subjectsByDay['Wednesday'] = $subjectName;
                        $subjectsByDay['Thursday'] = $subjectName;
                        $subjectsByDay['Friday'] = $subjectName;
                    } else {
                        $subjectsByDay[$day] = $schedule->schedule->subject->subject_name ?? $schedule->schedule->subject->name ?? 'N/A';
                    }
                }
            }

            $scheduleData[] = [
                'time_slot' => $formattedTimeSlot,
                'minutes' => $minutes,
                'subjects' => $subjectsByDay
            ];
        }

        // Check if viewing old school year by comparing academic year end dates
        $isOldSchoolYear = false;
        $latestAcademicYear = AcademicYear::orderBy('end_date', 'desc')->first();

        if ($latestAcademicYear && $currentAcademicYear && $currentAcademicYear->id !== $latestAcademicYear->id) {
            $isOldSchoolYear = true;
        }

        return view('student_portal.classSchedule', compact(
            'student',
            'scheduleData',
            'subjects',
            'currentEnrollment',
            'currentAcademicYear',
            'isOldSchoolYear'
        ));
    }

    public function schoolYears()
    {
        $student = Auth::user()->student;

        $enrollments = Enrollment::where('student_id', $student->id)
            ->with('academicYear')
            ->get();

        $academicYears = $enrollments->pluck('academicYear')->sortByDesc('year_name')->unique('id');

        return view('student_portal.schoolYears', compact('academicYears', 'student'));
    }

    public function switchSchoolYear(AcademicYear $academicYear)
    {
        $student = Auth::user()->student;

        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->with(['academicYear', 'gradeLevel', 'billing', 'programType'])
            ->first();

        if (!$enrollment) {
            return redirect()->route('students.schoolYears')->with('error', 'Enrollment not found.');
        }

        // Store selected academic year in session
        session(['selected_academic_year_id' => $academicYear->id]);

        $totalAssessment = 0;
        $totalPaid = 0;
        $currentBalance = 0;

        if ($enrollment->billing) {
            $totalAssessment = $enrollment->billing->total_amount;
            $totalPaid = Payment::where('billing_id', $enrollment->billing->id)->sum('amount_paid');
            $currentBalance = $totalAssessment - $totalPaid;
        }

        return view('student_portal.switchedYear', compact(
            'student',
            'enrollment',
            'academicYear',
            'totalAssessment',
            'totalPaid',
            'currentBalance'
        ));
    }

    public function paymentHistory()
    {
        $student = Auth::user()->student;

        $enrollments = $student->enrollments;
        $payments = collect();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->billing) {
                foreach ($enrollment->billing->payments as $payment) {
                    $payments->push($payment);
                }
            }
        }

        $payments = $payments->sortByDesc('payment_date');

        return view('student_portal.paymentHistory', compact('payments', 'student'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('learner_reference_number', 'LIKE', "%{$search}%")
                        ->orWhere('nickname', 'LIKE', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('last_name', 'asc')
            ->paginate(10);

        return view('student_management.students.index', compact('students'));
    }

    public function create()
    {
        return redirect()->route('students.registration.step1');
    }

    public function createStep1()
    {
        if (!session('student_registration') && !request()->has('edit')) {
            session()->forget('student_registration');
        }
        return view('student_management.registration.regular.step1');
    }

    public function storeStep1(RegularStudentStep1Request $request)
    {
        $validated = $request->validated();
        session(['student_registration.step1' => $validated]);
        $currentSession = session('student_registration', []);
        $updatedSession = array_merge($currentSession, $validated);
        session(['student_registration' => $updatedSession]);
        return redirect()->route('students.registration.step2');
    }

    public function createStep2()
    {
        if (!session('student_registration.step1')) {
            return redirect()->route('students.registration.step1')->with('error', 'Please complete student information first.');
        }
        return view('student_management.registration.regular.step2');
    }

    public function storeStep2(RegularStudentStep2Request $request)
    {
        $validated = $request->validated();
        session(['student_registration.step2' => $validated]);
        $currentSession = session('student_registration', []);
        $updatedSession = array_merge($currentSession, $validated);
        session(['student_registration' => $updatedSession]);
        return redirect()->route('students.registration.step3');
    }

    public function createStep3()
    {
        if (!session('student_registration.step2')) {
            return redirect()->route('students.registration.step1')->with('error', 'Please complete guardian information first.');
        }
        return view('student_management.registration.regular.step3');
    }

    public function storeStep3(RegularStudentStep3Request $request)
    {
        $validated = $request->validated();
        $documentData = [];

        foreach ($validated['documents'] ??  [] as $type => $document) {
            if ($type === 'additional') {
                foreach ($document as $index => $additional) {
                    if (isset($additional['file']) && $additional['file']->isValid()) {
                        // Store in storage/app/public/student-documents/
                        $path = $additional['file']->store('student-documents', 'public');

                        $documentData['additional'][$index] = [
                            'type' => $additional['type'],
                            'file_path' => $path,
                            'file_name' => $additional['file']->getClientOriginalName(),
                        ];
                    }
                }
            } else {
                if ($document && $document->isValid()) {
                    // Store in storage/app/public/student-documents/
                    $path = $document->store('student-documents', 'public');

                    $documentData[$type] = [
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                    ];
                }
            }
        }

        session(['student_registration. step3' => $documentData]);
        $currentSession = session('student_registration', []);
        $updatedSession = array_merge($currentSession, ['documents' => $documentData]);
        session(['student_registration' => $updatedSession]);
        return redirect()->route('students.registration.review');
    }

    public function review()
    {
        $registrationData = session('student_registration', []);
        if (empty($registrationData)) {
            return redirect()->route('students.registration.step1')->with('error', 'Please start registration from the beginning.');
        }
        return view('student_management.registration.regular.review', compact('registrationData'));
    }

    public function storeFinal(Request $request)
    {
        $registrationData = session('student_registration', []);
        if (empty($registrationData)) {
            return redirect()->route('students. registration.step1')->with('error', 'Registration session expired.');
        }

        $student = Student::create([
            'learner_reference_number' => $registrationData['learner_reference_number'],
            'first_name' => $registrationData['first_name'],
            'middle_name' => $registrationData['middle_name'],
            'last_name' => $registrationData['last_name'],
            'extension_name' => $registrationData['extension_name'] ?? null,
            'nickname' => $registrationData['nickname'] ?? null,
            'gender' => $registrationData['gender'],
            'birthdate' => $registrationData['birthdate'],
            'birthplace' => $registrationData['birthplace'] ?? null,
            'nationality' => $registrationData['nationality'] ?? null,
            'spoken_dialect' => $registrationData['spoken_dialect'] ?? null,
            'other_spoken_dialect' => $registrationData['other_spoken_dialect'] ?? null,
            'religion' => $registrationData['religion'] ?? null,
            'address' => $registrationData['address'] ?? null,
            'student_status' => 'active',
        ]);

        // Create guardians (support multiple guardians)
        if (isset($registrationData['guardians']) && is_array($registrationData['guardians'])) {
            foreach ($registrationData['guardians'] as $guardianData) {
                $guardian = Guardian::create([
                    'first_name' => $guardianData['first_name'],
                    'middle_name' => $guardianData['middle_name'] ?? null,
                    'last_name' => $guardianData['last_name'],
                    'relation' => $guardianData['relation'],
                    'contact_number' => $guardianData['contact_number'],
                    'email' => $guardianData['email'] ?? null,
                    'address' => $guardianData['address'] ?? null,
                ]);

                StudentGuardian::create([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,
                ]);
            }
        }

        // Documents saving code... 
        if (isset($registrationData['documents']) && !empty($registrationData['documents'])) {
            foreach ($registrationData['documents'] as $type => $documentInfo) {
                if ($type === 'additional' && is_array($documentInfo)) {
                    foreach ($documentInfo as $additionalDoc) {
                        if (isset($additionalDoc['file_path']) && !empty($additionalDoc['file_path'])) {
                            Document::create([
                                'student_id' => $student->id,
                                'document_name' => $additionalDoc['file_name'],
                                'document_type' => $additionalDoc['type'],
                                'file_path' => $additionalDoc['file_path'],
                            ]);
                        }
                    }
                } else {
                    if (isset($documentInfo['file_path']) && !empty($documentInfo['file_path'])) {
                        Document::create([
                            'student_id' => $student->id,
                            'document_name' => $documentInfo['file_name'],
                            'document_type' => $type,
                            'file_path' => $documentInfo['file_path'],
                        ]);
                    }
                }
            }
        }

        // Log activity
        ActivityLogService::created($student, "Registered regular student: '{$student->first_name} {$student->last_name}'");

        session()->forget('student_registration');
        return redirect()->route('enrollments.create', ['student' => $student->id, 'source' => 'regular'])
            ->with('success', 'Student registered successfully!');
    }

    public function show(Student $student)
    {
        $student->load('guardians', 'documents');
        return view('student_management.registration.show', compact('student'));
    }

    public function clearSped()
    {
        session()->forget('sped_student_registration');
        return view('student_management.registration.sped.step1');
    }

    public function createSpedStep1()
    {
        if (!session('sped_student_registration') && !request()->has('edit')) {
            session()->forget('sped_student_registration');
        }
        return view('student_management.registration.sped.step1');
    }

    public function storeSpedStep1(SpedStudentStep1Request $request)
    {
        $validated = $request->validated();
        session(['sped_student_registration.step1' => $validated]);
        session(['sped_student_registration' => array_merge(session('sped_student_registration', []), $validated)]);
        return redirect()->route('students.sped-registration.step2');
    }

    public function createSpedStep2()
    {
        if (!session('sped_student_registration.step1')) {
            return redirect()->route('students.sped-registration.step1')->with('error', 'Please complete student information first.');
        }
        return view('student_management.registration.sped.step2');
    }

    public function storeSpedStep2(SpedStudentStep2Request $request)
    {
        $validated = $request->validated();
        session(['sped_student_registration.step2' => $validated]);
        session(['sped_student_registration' => array_merge(session('sped_student_registration', []), $validated)]);
        return redirect()->route('students.sped-registration.step3');
    }

    public function createSpedStep3()
    {
        if (!session('sped_student_registration.step2')) {
            return redirect()->route('students.sped-registration.step1')->with('error', 'Please complete guardian information first.');
        }
        return view('student_management.registration.sped.step3');
    }

    public function storeSpedStep3(SpedStudentStep3Request $request)
    {
        $validated = $request->validated();
        $documentData = [];

        foreach ($validated['documents'] ?? [] as $type => $document) {
            if ($type === 'additional') {
                foreach ($document as $index => $additional) {
                    if (isset($additional['file']) && $additional['file']->isValid()) {
                        // Store in storage/app/public/student-documents/
                        $path = $additional['file']->store('student-documents', 'public');

                        $documentData['additional'][$index] = [
                            'type' => $additional['type'],
                            'file_path' => $path,
                            'file_name' => $additional['file']->getClientOriginalName(),
                        ];
                    }
                }
            } else {
                if ($document && $document->isValid()) {
                    // Store in storage/app/public/student-documents/
                    $path = $document->store('student-documents', 'public');

                    $documentData[$type] = [
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                    ];
                }
            }
        }

        session(['sped_student_registration.step3' => $documentData]);
        session(['sped_student_registration' => array_merge(session('sped_student_registration', []), ['documents' => $documentData])]);
        return redirect()->route('students.sped-registration.review');
    }

    public function spedReview()
    {
        $registrationData = session('sped_student_registration', []);
        if (empty($registrationData)) {
            return redirect()->route('students.sped-registration.step1')->with('error', 'Please start registration from the beginning.');
        }
        return view('student_management.registration.sped.review', compact('registrationData'));
    }

    public function storeSpedFinal(Request $request)
    {
        $registrationData = session('sped_student_registration', []);
        if (empty($registrationData)) {
            return redirect()->route('students.sped-registration.step1')->with('error', 'Registration session expired.');
        }

        $student = Student::create([
            'learner_reference_number' => $registrationData['learner_reference_number'],
            'first_name' => $registrationData['first_name'],
            'middle_name' => $registrationData['middle_name'],
            'last_name' => $registrationData['last_name'],
            'extension_name' => $registrationData['extension_name'] ?? null,
            'nickname' => $registrationData['nickname'] ?? null,
            'gender' => $registrationData['gender'],
            'birthdate' => $registrationData['birthdate'],
            'birthplace' => $registrationData['birthplace'] ?? null,
            'nationality' => $registrationData['nationality'] ?? null,
            'spoken_dialect' => $registrationData['spoken_dialect'] ?? null,
            'other_spoken_dialect' => $registrationData['other_spoken_dialect'] ?? null,
            'religion' => $registrationData['religion'] ?? null,
            'address' => $registrationData['address'] ?? null,
            'student_status' => 'active',
        ]);

        SpedStudent::create([
            'student_id' => $student->id,
            'type_of_disability' => $registrationData['type_of_disability'],
            'date_of_diagnosis' => $registrationData['date_of_diagnosis'],
            'cause_of_disability' => $registrationData['cause_of_disability'] ?? null,
        ]);

        // Create guardians (support multiple guardians)
        if (isset($registrationData['guardians']) && is_array($registrationData['guardians'])) {
            foreach ($registrationData['guardians'] as $guardianData) {
                $guardian = Guardian::create([
                    'first_name' => $guardianData['first_name'],
                    'middle_name' => $guardianData['middle_name'] ?? null,
                    'last_name' => $guardianData['last_name'],
                    'relation' => $guardianData['relation'],
                    'contact_number' => $guardianData['contact_number'],
                    'email' => $guardianData['email'] ?? null,
                    'address' => $guardianData['address'] ?? null,
                ]);

                StudentGuardian::create([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,
                ]);
            }
        }

        // FIX: The documents are stored directly in $registrationData['documents']
        if (isset($registrationData['documents']) && !empty($registrationData['documents'])) {
            foreach ($registrationData['documents'] as $type => $documentInfo) {
                if ($type === 'additional' && is_array($documentInfo)) {
                    // Handle additional documents
                    foreach ($documentInfo as $additionalDoc) {
                        if (isset($additionalDoc['file_path']) && ! empty($additionalDoc['file_path'])) {
                            Document::create([
                                'student_id' => $student->id,
                                'document_name' => $additionalDoc['file_name'],
                                'document_type' => $additionalDoc['type'],
                                'file_path' => $additionalDoc['file_path'],
                            ]);
                        }
                    }
                } else {
                    // Handle regular documents (birth_certificate, report_card, etc.)
                    if (isset($documentInfo['file_path']) && !empty($documentInfo['file_path'])) {
                        Document::create([
                            'student_id' => $student->id,
                            'document_name' => $documentInfo['file_name'],
                            'document_type' => $type,
                            'file_path' => $documentInfo['file_path'],
                        ]);
                    }
                }
            }
        }

        // Log activity
        ActivityLogService::created($student, "Registered SPED student: '{$student->first_name} {$student->last_name}'");

        session()->forget('sped_student_registration');
        return redirect()->route('enrollments.create', ['student' => $student->id, 'source' => 'sped'])
            ->with('success', 'Student registered successfully!');
    }

    public function edit(Student $student)
    {
        //
    }

    public function update(Request $request, Student $student)
    {
        //
    }

    public function destroy(Student $student)
    {
        //
    }

    public function studentsReport(Request $request)
    {
        $search = $request->input('search');


        $students = Student::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('learner_reference_number', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('last_name', 'asc')
            ->get();

        $reportTitle = 'Student List Report';
        $reportDate = now()->format('F d, Y');

        return view('reports.students-report', compact('students', 'reportTitle', 'reportDate'));
    }

    /**
     * Archive a student (soft delete)
     */
    public function archive($id)
    {
        $student = Student::findOrFail($id);
        $studentName = $student->first_name . ' ' . $student->last_name;

        $student->delete();

        ActivityLogService::deleted('Student', "Archived student: {$studentName}");

        return redirect()->route('students.index')->with('success', 'Student archived successfully!');
    }

    /**
     * Show archived students
     */
    public function archived(Request $request)
    {
        $search = $request->input('search');

        $students = Student::onlyTrashed()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('learner_reference_number', 'LIKE', "%{$search}%")
                        ->orWhere('nickname', 'LIKE', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('student_management.students.archived', compact('students'));
    }

    /**
     * Restore an archived student
     */
    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $studentName = $student->first_name . ' ' . $student->last_name;

        $student->restore();

        ActivityLogService::custom("Restored archived student: {$studentName}");

        return redirect()->route('students.archived')->with('success', 'Student restored successfully!');
    }

    /**
     * Permanently delete a student
     */
    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $studentName = $student->first_name . ' ' . $student->last_name;

        $student->forceDelete();

        ActivityLogService::deleted('Student', "Permanently deleted student: {$studentName}");

        return redirect()->route('students.archived')->with('success', 'Student permanently deleted!');
    }
}
