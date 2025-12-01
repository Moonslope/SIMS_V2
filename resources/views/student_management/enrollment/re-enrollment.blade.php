@extends('layout.layout')
@section('title', 'Re-enroll Student')
@section('content')

<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
         <li><a href="{{ route('enrollments.index') }}">Enrollments</a></li>
         <li>Old Student</li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Enrollment for Old Students</h1>
   </div>

   <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
      <!-- Search Section (2/5 width) -->
      <div class="lg:col-span-2">
         <div class="card bg-base-100 shadow-md">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-4">
                  <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
                  <h2 class="text-xl font-semibold">Search Student</h2>
               </div>

               <form method="POST" action="{{ route('enrollments.search-students') }}" class="space-y-4">
                  @csrf
                  <div class="form-control">
                     <div class="flex gap-2">
                        <input type="text" name="query" placeholder="Enter name or LRN..."
                           class="input input-bordered flex-1 rounded-lg focus:outline-none focus:border-primary"
                           minlength="2" required>
                        <button type="submit" class="btn btn-primary rounded-lg">
                           <svg xmlns="http://www.w3. org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                           </svg>
                           Search
                        </button>
                     </div>
                  </div>
               </form>

               <!-- Display search results -->
               @if(isset($students) && $students->count() > 0)
               <div class="divider"></div>
               <div class="space-y-2 max-h-[500px] overflow-y-auto">
                  <p class="text-sm font-medium text-gray-600 mb-3">{{ $students->count() }} {{ $students->count() === 1
                     ? 'student' : 'students' }} found</p>

                  @foreach($students as $student)
                  @php
                  $latestEnrollment = \App\Models\Enrollment::where('student_id',
                  $student->id)->latest('date_enrolled')->first();
                  $isEligible = true;
                  $statusMessage = 'Ready for re-enrollment';
                  $statusColor = 'badge-success';

                  if ($latestEnrollment) {
                  $billing = \App\Models\Billing::where('enrollment_id', $latestEnrollment->id)->first();
                  if ($billing) {
                  $totalPaid = \App\Models\Payment::where('billing_id', $billing->id)->sum('amount_paid');
                  $remainingBalance = $billing->total_amount - $totalPaid;
                  if ($remainingBalance > 0) {
                  $isEligible = false;
                  $statusMessage = 'Unpaid balance: ₱' . number_format($remainingBalance, 2);
                  $statusColor = 'badge-error';
                  }
                  }
                  }
                  @endphp

                  <button type="button"
                     class="card bg-base-200 hover:bg-base-300 w-full text-left transition-colors cursor-pointer"
                     onclick="selectThisStudent(
                        {{ $student->id }}, 
                        '{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}', 
                        '{{ $student->learner_reference_number }}', 
                        {{ $isEligible ? 'true' : 'false' }}, 
                        '{{ $statusMessage }}', 
                        {{ $latestEnrollment?->program_type_id ?? 'null' }}, 
                        {{ $latestEnrollment?->academic_year_id ??  'null' }}
                     )">
                     <div class="card-body p-4">

                        <div class="flex items-center gap-3">
                           <div class="avatar placeholder">
                              <div
                                 class="bg-primary flex justify-center items-center text-primary-content rounded-full w-10">
                                 <span class="text-sm">{{ substr($student->first_name, 0, 1) }}{{
                                    substr($student->last_name, 0, 1) }}</span>
                              </div>
                           </div>

                           <div>
                              <p class="font-semibold">{{ $student->last_name }}, {{ $student->first_name }}</p>
                              <p class="text-xs text-gray-500">LRN: {{ $student->learner_reference_number }}</p>
                           </div>

                           <div class="ms-10">
                              <span class="badge {{ $isEligible ? 'badge-success' : 'badge-error' }} badge-sm">
                                 {{ $isEligible ? 'Eligible' : 'Not Eligible' }}
                              </span>
                           </div>
                        </div>

                        @if(! $isEligible)
                        <p class="text-xs text-error mt-2">{{ $statusMessage }}</p>
                        @endif
                     </div>
                  </button>
                  @endforeach
               </div>
               @elseif(request()->filled('query'))
               <div class="alert alert-neutral mt-4">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     class="stroke-current shrink-0 w-6 h-6">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>No students found matching "{{ request('query') }}"</span>
               </div>
               @endif
            </div>
         </div>
      </div>

      <!-- Form Section (3/5 width) -->
      <div class="lg:col-span-3">
         <div class="card bg-base-100 shadow-md">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-6">
                  <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
                  <h2 class="text-xl font-semibold">Enrollment Details</h2>
               </div>

               <form id="enrollmentForm" action="{{ route('enrollments.create-re-enrollment') }}" method="POST"
                  class="space-y-6">
                  @csrf

                  <!-- Selected Student Info -->
                  <div id="studentInfo" class="hidden w-full">
                     <div class="card bg-base-200 shadow-sm border-2 border-primary">
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
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary"
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
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary"
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
                           class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary"
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
                     <button type="submit" class="btn btn-sm btn-primary w-40 rounded-lg" id="submitBtn" disabled>
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

<script>
   function selectThisStudent(id, name, lrn, isEligible, statusMsg, programTypeId, academicYearId) {
   // Show student info
   document.getElementById('studentIdInput'). value = id;
   document. getElementById('selectedStudentName').textContent = name;
   document. getElementById('selectedStudentLRN').textContent = lrn;
   
   
   document.getElementById('studentInfo').classList.remove('hidden');
   
   // Update status badge
   let badge = document.getElementById('statusBadge');
   let statusMsgEl = document.getElementById('statusMessage');
   
   if (isEligible) {
      badge. className = 'badge badge-sm badge-success';
      badge.textContent = 'Eligible';
      statusMsgEl.classList.add('hidden');
   } else {
      badge.className = 'badge badge-sm badge-error';
      badge.textContent = 'Not Eligible';
      statusMsgEl.textContent = statusMsg;
      statusMsgEl.classList.remove('hidden');
   }
   
   // Pre-fill program type
   if (programTypeId) {
      document.getElementById('programTypeSelect').value = programTypeId;
   }
   
   // Enable/Disable submit button
   document.getElementById('submitBtn').disabled = !isEligible;
}

   // Dynamic section filtering by grade level
   document.addEventListener('DOMContentLoaded', function() {
      const gradeLevelSelect = document.getElementById('gradeLevelSelect');
      const sectionSelect = document.getElementById('sectionSelect');
      const academicYearSelect = document.getElementById('academicYearSelect');
      const capacityIndicator = document.getElementById('capacityIndicator');
      const capacityBadge = document. getElementById('capacityBadge');
      const capacityText = document.getElementById('capacityText');
      const capacityStatus = document.getElementById('capacityStatus');
      
      // When grade level changes, fetch sections
      gradeLevelSelect. addEventListener('change', function() {
         const gradeLevelId = this.value;
         const academicYearId = academicYearSelect.value;
         
         if (! gradeLevelId || !academicYearId) {
            sectionSelect.innerHTML = '<option value="">Select Grade Level and School Year First</option>';
            capacityIndicator.classList.add('hidden');
            return;
         }
         
         // Show loading
         sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
         sectionSelect.disabled = true;
         capacityIndicator.classList. add('hidden');
         
         // Fetch sections via AJAX
         fetch(`{{ route('enrollments.sections-by-grade') }}?grade_level_id=${gradeLevelId}&academic_year_id=${academicYearId}`)
            .then(response => response.json())
            . then(data => {
               sectionSelect.innerHTML = '<option value="">Select Section</option>';
               
               if (data.length === 0) {
                  sectionSelect.innerHTML += '<option value="">No sections available</option>';
               } else {
                  data. forEach(section => {
                     const option = document.createElement('option');
                     option.value = section.id;
                     option.textContent = section.display;
                     option.dataset.capacity = section.capacity;
                     option.dataset.enrolled = section.enrolled;
                     option.dataset.available = section.available;
                     option.dataset.status = section.status;
                     option.dataset.color = section. color;
                     
                     if (section.status === 'full') {
                        option.disabled = true;
                        option. textContent += ' - FULL';
                     }
                     
                     sectionSelect. appendChild(option);
                  });
               }
               
               sectionSelect.disabled = false;
            })
            .catch(error => {
               console.error('Error fetching sections:', error);
               sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
               sectionSelect.disabled = false;
            });
      });
      
      // Also trigger when academic year changes
      academicYearSelect.addEventListener('change', function() {
         if (gradeLevelSelect.value) {
            gradeLevelSelect.dispatchEvent(new Event('change'));
         }
      });
      
      // When section changes, show capacity indicator
      sectionSelect. addEventListener('change', function() {
         const selectedOption = this.options[this.selectedIndex];
         
         if (! selectedOption. value) {
            capacityIndicator.classList.add('hidden');
            return;
         }
         
         const capacity = selectedOption.dataset.capacity;
         const enrolled = selectedOption. dataset.enrolled;
         const available = selectedOption.dataset.available;
         const status = selectedOption. dataset.status;
         const color = selectedOption.dataset.color;
         
         // Update status text
         if (status === 'full') {
            capacityStatus.textContent = 'Section Full';
         } else if (status === 'almost-full') {
            capacityStatus.textContent = 'Almost Full - Limited Slots';
         } else {
            capacityStatus.textContent = 'Section Available';
         }
         
         // Update badge
         capacityBadge.className = `badge badge-sm badge-${color} mr-2`;
         capacityBadge.textContent = status === 'full' ? 'FULL' : status === 'almost-full' ?  'Almost Full' : 'Available';
         
         // Update text
         capacityText.innerHTML = `<strong>${enrolled}/${capacity}</strong> enrolled • <strong>${available}</strong> slots remaining`;
         
         // Update alert color
         const alertDiv = capacityIndicator;
         alertDiv.className = `alert alert-${color === 'error' ? 'error' : color === 'warning' ? 'warning' : 'success'}`;
         
         // Show indicator
         capacityIndicator.classList.remove('hidden');
      });
   });
</script>

@endsection