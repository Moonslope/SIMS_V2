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
   SHARED AUTHENTICATED ROUTES (All Roles)
   ============================================ */
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

/* ============================================
   ADMIN + REGISTRAR + CASHIER ROUTES
   ============================================ */
Route::middleware(['auth', 'role:admin,registrar,cashier'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

    // Student Management - VIEW ONLY for Registrar/Cashier
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/student-profile', [StudentController::class,  'studentProfile'])->name('students.student-profile');
    Route::get('/students/archived/list', [StudentController::class, 'archived'])->name('students.archived');
    Route::get('/students/{student}/documents/{document}', [StudentController::class, 'viewDocument'])->name('students.view-document');
    
    // Document Upload - All staff can upload
    Route::post('/students/{student}/documents/upload', [StudentController::class, 'uploadDocument'])->name('students.upload-document');
    Route::delete('/students/{student}/documents/{document}', [StudentController::class, 'deleteDocument'])->name('students.delete-document');


    // Enrollment - CREATE ONLY for Registrar/Cashier
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
    Route::get('/enrollments/sections-by-grade', [EnrollmentController::class, 'getSectionsByGradeLevel'])->name('enrollments.sections-by-grade');
    Route::get('/enrollments/re-enrollment/form', [EnrollmentController::class, 'reEnrollmentForm'])->name('enrollments.re-enrollment');
    Route::get('/enrollments/archived/list', [EnrollmentController::class, 'archived'])->name('enrollments.archived');
    Route::post('/enrollments/search', [EnrollmentController::class, 'searchStudents'])->name('enrollments.search-students');
    Route::post('/enrollments/re-enrollment/create', [EnrollmentController::class, 'createReEnrollment'])->name('enrollments.create-re-enrollment');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('/enrollments/{enrollment}/billing', [EnrollmentController::class, 'showBilling'])->name('enrollments.billing');


    // Regular Registration (Multi-step) - All staff
    Route::get('/registration/step1', [StudentController::class, 'createStep1'])->name('students.registration.step1');
    Route::post('/registration/step1', [StudentController::class, 'storeStep1'])->name('students.registration.store-step1');
    Route::get('/registration/step2', [StudentController::class, 'createStep2'])->name('students.registration.step2');
    Route::post('/registration/step2', [StudentController::class, 'storeStep2'])->name('students.registration.store-step2');
    Route::get('/registration/step3', [StudentController::class, 'createStep3'])->name('students.registration.step3');
    Route::post('/registration/step3', [StudentController::class, 'storeStep3'])->name('students.registration.store-step3');
    Route::get('/registration/review', [StudentController::class, 'review'])->name('students.registration.review');
    Route::post('/registration/final', [StudentController::class, 'storeFinal'])->name('students.registration.store-final');

    // SPED Registration (Multi-step) - All staff
    Route::get('/sped-registration/step1', [StudentController::class, 'createSpedStep1'])->name('students.sped-registration.step1');
    Route::post('/sped-registration/step1', [StudentController::class, 'storeSpedStep1'])->name('students.sped-registration.store-step1');
    Route::get('/sped-registration/step2', [StudentController::class, 'createSpedStep2'])->name('students.sped-registration.step2');
    Route::post('/sped-registration/step2', [StudentController::class, 'storeSpedStep2'])->name('students.sped-registration.store-step2');
    Route::get('/sped-registration/step3', [StudentController::class, 'createSpedStep3'])->name('students.sped-registration.step3');
    Route::post('/sped-registration/step3', [StudentController::class, 'storeSpedStep3'])->name('students.sped-registration.store-step3');
    Route::get('/sped-registration/review', [StudentController::class, 'spedReview'])->name('students.sped-registration.review');
    Route::post('/sped-registration/final', [StudentController::class, 'storeSpedFinal'])->name('students.sped-registration.store-final');

    // Billing & Payments - All staff (Registrar, Cashier, Admin)
    Route::resource('billings', BillingController::class)->only(['index', 'show']);
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('/billings/{billing}/payment', [PaymentController::class, 'processBillingPayment'])->name('billings.process-payment');
});

/* ============================================
   ADMIN ONLY ROUTES
   ============================================ */
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Student Management - EDIT/DELETE (Admin Only)
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::delete('/students/{id}/archive', [StudentController::class, 'archive'])->name('students.archive');
    Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
    Route::delete('/students/{id}/force-delete', [StudentController::class, 'forceDelete'])->name('students.force-delete');

    // Enrollment - EDIT/DELETE (Admin Only)
    Route::get('/enrollments/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::delete('/enrollments/{id}/archive', [EnrollmentController::class, 'archive'])->name('enrollments.archive');
    Route::post('/enrollments/{id}/restore', [EnrollmentController::class, 'restore'])->name('enrollments.restore');
    Route::delete('/enrollments/{id}/force-delete', [EnrollmentController::class, 'forceDelete'])->name('enrollments.force-delete');

    // Academic Structure (Admin Only)
    Route::resource('grade-levels', GradeLevelController::class);
    Route::resource('program-types', ProgramTypeController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('teachers', TeacherController::class);

    // Financial Management - CREATE/EDIT/DELETE (Admin Only)
    Route::resource('billings', BillingController::class)->except(['index', 'show']);
    Route::resource('payments', PaymentController::class)->except(['index', 'show']);
    Route::resource('fee-structures', FeeStructureController::class);

    // System Management (Admin Only)
    Route::resource('users', UserController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('activity-logs', ActivityLogController::class);

    // Other Admin Resources
    Route::resource('guardians', GuardianController::class);

    // Reports (Admin Only)
    Route::get('/reports/enrollments', [EnrollmentController::class, 'generateReport'])->name('reports.enrollments');
    Route::get('/reports/students', [StudentController::class, 'generateReport'])->name('reports.students');
    Route::get('/reports/payments', [PaymentController::class, 'generateReport'])->name('reports.payments');
    Route::get('/reports/class-list', [EnrollmentController::class, 'generateClassList'])->name('reports.classlist');
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

    // Account Settings
    Route::get('/student/change-password', [StudentController::class, 'showChangePasswordForm'])->name('students.change-password');
    Route::post('/student/change-password', [StudentController::class, 'changePassword'])->name('students.update-password');
});
