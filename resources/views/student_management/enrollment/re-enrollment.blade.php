@extends('layout.layout')
@section('title', 'Re-enroll Student')
@section('content')

<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{ route('enrollments.index') }}">Enrollment</a></li>
         <li class="text-blue-600 font-semibold">Old Student</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Enrollment for Old Students</h1>
   </div>

   <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
      <!-- Search Section (2/5 width) -->
      <div class="lg:col-span-2">
         <div class="card bg-base-100 shadow-md">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-4">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Search Student</h2>
               </div>

               <div class="form-control">
                  <div class="flex gap-2">
                     <input type="text" id="studentSearchInput" placeholder="Enter name or LRN..."
                        class="input input-bordered flex-1 rounded-lg focus:outline-none focus:border-blue-600"
                        minlength="2">
                     <div id="searchSpinner" class="hidden">
                        <span class="loading loading-spinner loading-md text-blue-600"></span>
                     </div>
                  </div>
                  <label class="label">
                     <span class="label-text-alt text-gray-500">Type at least 2 characters to search</span>
                  </label>
               </div>

               <!-- Display search results (dynamically loaded) -->
               <div id="searchResults"></div>
            </div>
         </div>
      </div>

      <!-- Form Section (3/5 width) -->
      <div class="lg:col-span-3">
         <div class="card bg-base-100 shadow-md">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-6">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Enrollment Details</h2>
               </div>

               <form id="enrollmentForm" action="{{ route('enrollments.create-re-enrollment') }}" method="POST"
                  class="space-y-6">
                  @csrf

                  <!-- Selected Student Info -->
                  <div id="studentInfo" class="hidden w-full">
                     <div class="card bg-base-200 shadow-sm border-2 border-blue-600">
                        <div class="card-body p-4">
                           <div class="flex items-center gap-4">
                              <div class="flex-1">
                                 <h3 class="font-bold text-base" id="selectedStudentName">-</h3>
                                 <p class="text-sm text-gray-500">LRN: <span id="selectedStudentLRN"
                                       class="font-mono">-</span></p>
                              </div>
                              <div id="eligibilityStatus">
                                 <span id="statusBadge" class="badge badge-sm"></span>
                              </div>
                           </div>
                           <p id="statusMessage" class="text-xs text-gray-600 mt-2 hidden"></p>
                        </div>
                     </div>
                     <input type="hidden" id="studentIdInput" name="student_id">
                  </div>

                  <!-- Validation Errors -->
                  @if ($errors->any())
                  <div class="alert alert-error">
                     <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     <div>
                        <h3 class="font-bold">Please correct the following errors:</h3>
                        <ul class="text-sm list-disc list-inside">
                           @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
                  @endif

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <!-- Academic Year -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              School Year <span class="text-error">*</span>
                           </span>
                        </label>
                        <input type="text" class="input input-bordered rounded-lg w-full bg-base-200 cursor-not-allowed"
                           value="{{ $activeAcademicYear ? $activeAcademicYear->year_name : 'No active school year' }}"
                           readonly />
                        <input type="hidden" name="academic_year_id" id="academicYearSelect"
                           value="{{ $activeAcademicYear ? $activeAcademicYear->id : '' }}" />
                     </div>

                     <!-- Program Type -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              Program Type <span class="text-error">*</span>
                           </span>
                        </label>
                        <select name="program_type_id" id="programTypeSelect"
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-blue-600"
                           required>
                           <option value="">Select Program Type</option>
                           @foreach ($programTypes as $program)
                           <option value="{{ $program->id }}">{{ $program->program_name }}</option>
                           @endforeach
                        </select>
                     </div>

                     <!-- Grade Level -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              Grade Level <span class="text-error">*</span>
                           </span>
                        </label>
                        <select name="grade_level_id" id="gradeLevelSelect"
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-blue-600"
                           required>
                           <option value="">Select Grade Level</option>
                           @foreach ($gradeLevels as $grade)
                           <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                           @endforeach
                        </select>
                     </div>

                     <!-- Section -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              Section <span class="text-error">*</span>
                           </span>
                        </label>
                        <select name="section_id" id="sectionSelect"
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-blue-600"
                           required>
                           <option value="">Select Grade Level First</option>
                        </select>
                     </div>
                  </div>

                  <!-- Capacity Indicator -->
                  <div id="capacityIndicator" class="hidden alert">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-info shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <div>
                        <h3 class="font-bold" id="capacityStatus">Section Capacity</h3>
                        <div class="text-xs mt-1">
                           <span id="capacityBadge" class="badge badge-sm mr-2"></span>
                           <span id="capacityText"></span>
                        </div>
                     </div>
                  </div>

                  <!-- Divider -->
                  <div class="divider"></div>

                  <!-- Submit Button -->
                  <div class="flex justify-end gap-3">
                     <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
                        Cancel
                     </a>
                     <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white w-40 rounded-lg" id="submitBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Enroll Student
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Grade Repeat Confirmation Modal -->
<dialog id="grade_repeat_modal" class="modal">
   <div class="modal-box">
      <h3 class="font-bold text-lg mb-4">⚠️ Same Grade Level Detected</h3>
      <div class="alert alert-warning mb-4">
         <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
         </svg>
         <div>
            <p class="font-semibold">You are enrolling this student to the same grade level as the previous year.</p>
            <p class="text-sm mt-1">This typically means the student is repeating the grade.</p>
         </div>
      </div>
      
      <p class="mb-4">Please confirm:</p>
      <p class="font-medium mb-2">Is the student repeating <span id="repeatGradeName" class="text-blue-600"></span>?</p>
      
      <div class="modal-action">
         <button type="button" onclick="grade_repeat_modal.close()" class="btn btn-ghost rounded-lg">
            Cancel - Let me check
         </button>
         <button type="button" id="confirmRepeatBtn" class="btn bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Yes, Student is Repeating
         </button>
      </div>
   </div>
   <form method="dialog" class="modal-backdrop">
      <button>close</button>
   </form>
</dialog>

<!-- Hidden inputs for JavaScript -->
<input type="hidden" id="searchStudentsUrl" value="{{ route('enrollments.search-students') }}">
<input type="hidden" id="sectionsUrl" value="{{ route('enrollments.sections-by-grade') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{ asset('js/re-enrollment.js') }}"></script>

@endsection