<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProgramTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;

/* ============================================
   PUBLIC ROUTES - Authentication
   ============================================ */

Route::get('/', [AuthController::class, 'showLoginForm'])->name('showLogin');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* ============================================
   SHARED AUTHENTICATED ROUTES
   ============================================ */
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

/* ============================================
   ADMIN ROUTES
   ============================================ */
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

    // Announcements Management
    Route::resource('announcements', AnnouncementController::class);

    // Billings & Payments
    Route::resource('billings', BillingController::class);
    Route::resource('payments', PaymentController::class);
    Route::post('/billings/{billing}/payment', [PaymentController::class, 'processBillingPayment'])->name('billings.process-payment');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Academic Structure
    Route::resource('grade-levels', GradeLevelController::class);
    Route::resource('program-types', ProgramTypeController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);

    // Personnel Management
    Route::resource('guardians', GuardianController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('users', UserController::class);

    // Enrollment Management
    Route::resource('enrollments', EnrollmentController::class);
    Route::get('/enrollments/sections-by-grade', [EnrollmentController::class, 'getSectionsByGradeLevel'])->name('enrollments.sections-by-grade');
    Route::get('/enrollments/{enrollment}/billing', [EnrollmentController::class, 'showBilling'])->name('enrollments.billing');
    Route::get('/enrollments/re-enrollment/form', [EnrollmentController::class, 'reEnrollmentForm'])->name('enrollments.re-enrollment');
    Route::post('/enrollments/search', [EnrollmentController::class, 'searchStudents'])->name('enrollments.search-students');
    Route::post('/enrollments/re-enrollment/create', [EnrollmentController::class, 'createReEnrollment'])->name('enrollments.create-re-enrollment');

    // Schedule & Fees
    Route::resource('schedules', ScheduleController::class);
    Route::resource('fee-structures', FeeStructureController::class);

    // Activity Logs
    Route::resource('activity-logs', ActivityLogController::class);

    // Student Management
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/student-profile', [StudentController::class, 'studentProfile'])->name('students.student-profile');
    Route::get('/students/{student}/documents/{document}', [StudentController::class, 'viewDocument'])->name('students.view-document');

    // Reports
    Route::get('/reports/enrollments', [EnrollmentController::class, 'generateReport'])->name('reports.enrollments');
    Route::get('/reports/students', [StudentController::class, 'generateReport'])->name('reports.students');

    // Regular Registration (Multi-step)
    Route::get('/registration/step1', [StudentController::class, 'createStep1'])->name('students.registration.step1');
    Route::post('/registration/step1', [StudentController::class, 'storeStep1'])->name('students.registration.store-step1');
    Route::get('/registration/step2', [StudentController::class, 'createStep2'])->name('students.registration.step2');
    Route::post('/registration/step2', [StudentController::class, 'storeStep2'])->name('students.registration.store-step2');
    Route::get('/registration/step3', [StudentController::class, 'createStep3'])->name('students.registration.step3');
    Route::post('/registration/step3', [StudentController::class, 'storeStep3'])->name('students.registration.store-step3');
    Route::get('/registration/review', [StudentController::class, 'review'])->name('students.registration.review');
    Route::post('/registration/final', [StudentController::class, 'storeFinal'])->name('students.registration.store-final');

    // SPED Registration (Multi-step)
    Route::get('/sped-registration/step1', [StudentController::class, 'createSpedStep1'])->name('students.sped-registration.step1');
    Route::post('/sped-registration/step1', [StudentController::class, 'storeSpedStep1'])->name('students.sped-registration.store-step1');
    Route::get('/sped-registration/step2', [StudentController::class, 'createSpedStep2'])->name('students.sped-registration.step2');
    Route::post('/sped-registration/step2', [StudentController::class, 'storeSpedStep2'])->name('students.sped-registration.store-step2');
    Route::get('/sped-registration/step3', [StudentController::class, 'createSpedStep3'])->name('students.sped-registration.step3');
    Route::post('/sped-registration/step3', [StudentController::class, 'storeSpedStep3'])->name('students.sped-registration.store-step3');
    Route::get('/sped-registration/review', [StudentController::class, 'spedReview'])->name('students.sped-registration.review');
    Route::post('/sped-registration/final', [StudentController::class, 'storeSpedFinal'])->name('students.sped-registration.store-final');
});

/* ============================================
   STUDENT PORTAL ROUTES
   ============================================ */
Route::middleware(['auth', 'role:student'])->prefix('students')->group(function () {
    // Student Dashboard & Profile
    Route::get('/student/dashboard', [StudentController::class, 'studentPortal'])->name('students.dashboard');
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('students.profile');

    // Announcements & Updates
    Route::get('/student/announcements', [StudentController::class, 'announcements'])->name('students.announcements');

    // Academic Information
    Route::get('/student/school-years', [StudentController::class, 'schoolYears'])->name('students.schoolYears');
    Route::get('/student/school-years/{academicYear}', [StudentController::class, 'switchSchoolYear'])->name('students.switchSchoolYear');
    Route::get('/student/class-schedule', [StudentController::class, 'classSchedule'])->name('students.classSchedule');

    // Billing & Payments
    Route::get('/student/payment-history', [StudentController::class, 'paymentHistory'])->name('students.paymentHistory');

    // Documents
    Route::post('/student/documents/upload', [DocumentController::class, 'store'])->name('student.documents.upload');
    Route::get('/student/documents/{id}/download', [DocumentController::class, 'download'])->name('student.documents.download');
    Route::delete('/student/documents/{id}', [DocumentController::class, 'destroy'])->name('student.documents.delete');

    // Account Settings
    Route::get('/student/change-password', [StudentController::class, 'showChangePasswordForm'])->name('students.change-password');
    Route::post('/student/change-password', [StudentController::class, 'changePassword'])->name('students.update-password');
});
