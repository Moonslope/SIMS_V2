@extends('layout.portal')
@section('title', 'Class Schedule')
@section('content')

<div class="p-5">
   <!-- Page Header -->
   <div class="my-8">
      <h1 class="text-4xl font-bold text-primary mb-2 text-center">Class Schedule</h1>
      <p class="text-gray-600 text-center">Your official schedule for the current school year</p>
   </div>

   <!-- Old School Year Alert -->
   @if($isOldSchoolYear)
   <div role="alert" class="alert alert-warning rounded-lg mb-6">
      <i class="fi fi-sr-triangle-warning"></i>
      <span>You are viewing an old school year. <a href="{{ route('students.schoolYears') }}"
            class="link link-primary font-bold">Switch School Year</a></span>
   </div>
   @endif

   <!-- Student Information Card -->
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <h3 class="card-title text-primary mb-4">

            Student Information
         </h3>
         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start gap-2">
               <i class="fi fi-sr-user text-primary mt-1"></i>
               <div>
                  <p class="text-sm text-gray-600">Name</p>
                  <p class="font-semibold">{{ $student->last_name }}, {{ $student->first_name }} {{
                     $student->middle_name }}</p>
               </div>
            </div>
            <div class="flex items-start gap-2">
               <i class="fi fi-sr-calendar text-primary mt-1"></i>
               <div>
                  <p class="text-sm text-gray-600">School Year</p>
                  <p class="font-semibold">{{ $currentAcademicYear->year_name ?? 'N/A' }}</p>
               </div>
            </div>
            <div class="flex items-start gap-2">
               <i class="fi fi-sr-book text-primary mt-1"></i>
               <div>
                  <p class="text-sm text-gray-600">Program</p>
                  <p class="font-semibold">{{ $currentEnrollment->programType->program_name ?? 'N/A' }}</p>
               </div>
            </div>
            <div class="flex items-start gap-2">
               <i class="fi fi-sr-diploma text-primary mt-1"></i>
               <div>
                  <p class="text-sm text-gray-600">Grade Level</p>
                  <p class="font-semibold">{{ $currentEnrollment->gradeLevel->grade_name ?? 'N/A' }}</p>
               </div>
            </div>
            <div class="flex items-start gap-2">
               <i class="fi fi-sr-users text-primary mt-1"></i>
               <div>
                  <p class="text-sm text-gray-600">Section</p>
                  <p class="font-semibold">{{ $currentEnrollment->section->section_name ?? 'N/A' }}</p>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Schedule Table Card -->
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <h3 class="card-title text-primary mb-4">
            Weekly Schedule
         </h3>

         @if($scheduleData && count($scheduleData) > 0)
         <!-- Desktop View -->
         <div class="lg:block overflow-x-auto">
            <table class="table table-zebra">
               <thead>
                  <tr class="bg-base-300">
                     <th class="font-bold">Time</th>
                     <th class="font-bold text-center">Duration</th>
                     <th class="font-bold text-center text-black">
                        <div class="flex flex-col items-center">
                           <span>Monday</span>
                        </div>
                     </th>
                     <th class="font-bold text-center">
                        <div class="flex flex-col items-center text-black">
                           <span>Tuesday</span>
                        </div>
                     </th>
                     <th class="font-bold text-center">
                        <div class="flex flex-col items-center text-black">
                           <span>Wednesday</span>
                        </div>
                     </th>
                     <th class="font-bold text-center">
                        <div class="flex flex-col items-center text-black">
                           <span>Thursday</span>
                        </div>
                     </th>
                     <th class="font-bold text-center">
                        <div class="flex flex-col items-center text-black">
                           <span>Friday</span>
                        </div>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($scheduleData as $data)
                  <tr class="hover">
                     <td class="font-semibold whitespace-nowrap">
                        <div class="flex items-center gap-2">
                           <i class="fi fi-sr-clock text-primary"></i>
                           {{ $data['time_slot'] }}
                        </div>
                     </td>
                     <td class="text-center">
                        <span class="badge badge-ghost">{{ $data['minutes'] }} min</span>
                     </td>
                     <td class="text-center">
                        @if($data['subjects']['Monday'])
                        <span class="font-semibold">
                           {{ $data['subjects']['Monday'] }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                     </td>
                     <td class="text-center">
                        @if($data['subjects']['Tuesday'])
                        <span class="font-semibold">
                           {{ $data['subjects']['Tuesday'] }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                     </td>
                     <td class="text-center">
                        @if($data['subjects']['Wednesday'])
                        <span class="font-semibold">
                           {{ $data['subjects']['Wednesday'] }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                     </td>
                     <td class="text-center">
                        @if($data['subjects']['Thursday'])
                        <span class="font-semibold">
                           {{ $data['subjects']['Thursday'] }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                     </td>
                     <td class="text-center">
                        @if($data['subjects']['Friday'])
                        <span class="font-semibold">
                           {{ $data['subjects']['Friday'] }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>

         @else
         <!-- Empty State -->
         <div class="text-center py-12">
            <i class="fi fi-sr-calendar-exclamation text-[80px] text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Schedule Available</h3>
            <p class="text-gray-500">No schedule found for your current enrollment.</p>
            <div class="mt-4">
               <a href="{{ route('students.dashboard') }}" class="btn btn-sm bg-primary text-white rounded-lg">
                  <i class="fi fi-sr-home"></i>
                  Back to Dashboard
               </a>
            </div>
         </div>
         @endif
      </div>
   </div>

   <!-- Subjects List Card (if available) -->
   @if(isset($subjects) && $subjects->count() > 0)
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body">
         <h3 class="card-title text-primary mb-4">

            Enrolled Subjects
         </h3>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($subjects as $subject)
            <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
               <i class="fi fi-sr-book text-primary"></i>
               <span class="font-medium">{{ $subject->subject_name ?? $subject->name }}</span>
            </div>
            @endforeach
         </div>
      </div>
   </div>
   @endif

   <!-- Back Button -->
   <div class="text-center mt-6">
      <a href="{{ route('students.dashboard') }}" class="btn btn-ghost btn-sm text-primary rounded-lg">
         <i class="fi fi-sr-arrow-left"></i>
         Back to Dashboard
      </a>
   </div>
</div>

@endsection