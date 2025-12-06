@extends('layout.portal')

@section('title', 'Student Portal')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

   @if($isOldSchoolYear)
   <div class="bg-amber-50 border-l-4 border-[#FFB703] p-4 rounded-r-lg flex items-start gap-3 shadow-xs">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#FFB703] shrink-0" fill="none" viewBox="0 0 24 24"
         stroke="currentColor">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4v2m0 0v2m0-6v-2m0 0V7a2 2 0 012-2h2.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2.586a1 1 0 01-.707-.293l-5.414-5.414a1 1 0 01-.293-.707V9z" />
      </svg>
      <div>
         <h3 class="font-bold text-gray-800">Historical View</h3>
         <p class="text-sm text-gray-600">You are viewing records for a previous school year.
            <a href="{{ route('students.schoolYears') }}"
               class="text-blue-600 font-bold underline hover:text-blue-800">Switch to Current Year</a>
         </p>
      </div>
   </div>
   @endif

   <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-1 bg-blue-600"></div>

      <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mt-2">
         <div class="shrink-0">
            <div class="bg-blue-600 text-white flex justify-center items-center rounded-full w-20 h-20 shadow-lg">
               <span class="text-3xl font-bold">
                  {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{
                  strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
               </span>
            </div>
         </div>

         <div class="flex-1 text-center md:text-left">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">
               {{ $student->last_name }}, {{ $student->first_name }}
            </h2>
            <p class="text-gray-500 text-sm mb-4 font-mono tracking-wide">
               LRN: {{ $student->learner_reference_number }}
            </p>

            <div class="flex flex-wrap gap-3 justify-center md:justify-start">
               <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm  font-medium bg-[#F0F4FF] text-blue-600 border border-blue-100">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                     </path>
                  </svg>
                  {{ $enrollment->academicYear->year_name ?? 'N/A' }}
               </span>

               <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm  font-medium bg-[#F0F4FF] text-blue-600 border border-blue-100">
                  <i class="fi fi-br-e-learning me-3 pt-1"></i>
                  {{ $enrollment->programType->program_name ?? 'N/A' }}
               </span>

               <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm  font-medium bg-amber-50 text-amber-700 border border-amber-100">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                     </path>
                  </svg>
                  {{ $enrollment->gradeLevel->grade_name ?? 'N/A' }}
               </span>

               <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                  <i class="fi fi-br-users-class me-3 pt-1"></i>
                  {{ $enrollment->section->section_name ?? 'N/A' }}
               </span>
            </div>
         </div>
      </div>
   </div>

   <div class="bg-[#F0F4FF] border-l-4 border-blue-600 p-4 rounded-r-lg flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24"
         stroke="currentColor">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="text-blue-900 text-sm font-medium">
         For any concerns regarding your assessment, please visit the School Office.
      </span>
   </div>


   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Total Assessment -->
      <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 transition hover:shadow-md">
         <p class="text-gray-500  text-xs font-bold uppercase tracking-wider mb-1">Total Assessment</p>
         <p class="text-2xl font-bold text-blue-600">₱{{ number_format($totalAssessment, 2) }}</p>
      </div>

      <!-- Total Paid  -->
      <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 transition hover:shadow-md">
         <p class="text-gray-500  text-xs font-bold uppercase tracking-wider mb-1">Total Payment</p>
         <div class="flex items-center gap-2">
            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($totalPaid, 2) }}</p>

         </div>
      </div>

      <!-- Current Balance -->
      <div
         class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 transition hover:shadow-md relative overflow-hidden">
         <div class="relative z-10">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Current Balance</p>
            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($currentBalance, 2) }}</p>
            <p class="text-xs text-amber-600 mt-1 font-medium">Due for payment</p>
         </div>
      </div>
   </div>

   <!-- MOBILE ACTIONS -->
   <div class="block lg:hidden pt-4 border-t border-gray-100">
      <h3 class="text-sm font-bold text-gray-500 uppercase mb-3">Quick Actions</h3>
      <div class="grid grid-cols-2 gap-3">
         <a href="{{ route('students.paymentHistory') }}"
            class="flex flex-col items-center justify-center p-4 bg-[#F0F4FF] text-blue-600 rounded-lg border border-blue-100 active:scale-95 transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
               </path>
            </svg>
            <span class="text-xs font-bold">History</span>
         </a>
         <a href="{{ route('students.schoolYears') }}"
            class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-600 rounded-lg border border-gray-200 active:scale-95 transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-xs font-bold">Switch Year</span>
         </a>
      </div>
   </div>

</div>
@endsection