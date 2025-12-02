@extends('layout.portal')
@section('title', 'Student Portal')
@section('content')

<div class="p-5">
   <!-- Old School Year Alert -->
   @if($isOldSchoolYear)
   <div role="alert" class="alert alert-warning rounded-lg mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4v2m0 0v2m0-6v-2m0 0V7a2 2 0 012-2h2.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2.586a1 1 0 01-.707-.293l-5.414-5.414a1 1 0 01-.293-.707V9z">
         </path>
      </svg>
      <span>You are viewing an old school year. <a href="{{ route('students.schoolYears') }}"
            class="link link-primary font-bold">Switch School Year</a></span>
   </div>
   @endif

   <!-- Student Info Section -->
   <div class="p-4 sm:p-10 rounded-lg bg-white shadow-sm mb-6">
      <p class="text-lg sm:text-2xl font-bold text-primary mb-2 text-center">{{ $student->learner_reference_number }}
      </p>
      <p class="text-xl sm:text-3xl font-bold text-center mb-2">{{ $student->last_name }}, {{ $student->first_name }}
      </p>
      <p class="text-lg sm:text-2xl font-semibold text-center text-primary">School Year {{
         $enrollment->academicYear->year_name ?? 'N/A' }}</p>
      <p class="text-lg sm:text-2xl font-semibold text-center">{{ $enrollment->gradeLevel->grade_name ?? 'N/A' }}</p>
   </div>

   <!-- Alert Section -->
   <div role="alert" class="alert bg-[#d1ecf1] rounded-lg my-4 sm:my-5">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <span class="text-sm sm:text-base">For any concerns regarding to your assessment, please visit the office at the
         School.</span>
   </div>

   <!-- Stats Cards -->
   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
      <div class="card bg-base-100 shadow rounded-lg">
         <div class="card-body text-center sm:text-left">
            <p class="text-2xl sm:text-3xl font-bold text-primary">₱{{ number_format($totalAssessment, 2) }}</p>
            <p class="text-sm sm:text-base">Total Assessment</p>
         </div>
      </div>

      <div class="card bg-base-100 shadow rounded-lg">
         <div class="card-body text-center sm:text-left">
            <p class="text-2xl sm:text-3xl font-bold text-primary">₱{{ number_format($totalPaid, 2) }}</p>
            <p class="text-sm sm:text-base">Total Payment</p>
         </div>
      </div>

      <div class="card bg-base-100 shadow rounded-lg">
         <div class="card-body text-center sm:text-left">
            <p class="text-2xl sm:text-3xl font-bold text-primary">₱{{ number_format($currentBalance, 2) }}</p>
            <p class="text-sm sm:text-base">Current Balance</p>
         </div>
      </div>
   </div>

   <!-- Additional Content for Mobile -->
   <div class="lg:hidden mt-6">
      <div class="card bg-base-100 shadow rounded-lg">
         <div class="card-body">
            <h3 class="card-title">Quick Actions</h3>
            <div class="flex flex-col gap-2">
               <a href="{{ route('students.paymentHistory') }}" class="btn btn-primary btn-sm rounded-lg">View Payment
                  History</a>
               <a href="{{ route('students.schoolYears') }}" class="btn btn-outline btn-sm rounded-lg">Switch School
                  Year</a>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection