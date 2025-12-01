@extends('layout.layout')
@section('title', 'Enroll Student')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{route('enrollments.index')}}">Enrollment</a></li>
         <li><a>Enroll Student</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Enroll Student</h1>
      @if($currentAcademicYear)
      <h1 class="text-[20px] font-semibold text-base-300 me-3 p-2">SY {{ $currentAcademicYear->year_name }}</h1>
      @else
      <div class="badge badge-warning me-3">No active academic year</div>
      @endif
   </div>

   <!-- Student Info Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3">
            <div class="avatar placeholder">
               <div class="bg-primary text-primary-content flex justify-center items-center rounded-full w-12">
                  <span class="text-xl">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1)
                     }}</span>
               </div>
            </div>
            <div>
               <h3 class="font-semibold text-lg">
                  {{ $student->first_name }}
                  {{ $student->middle_name }}
                  {{ $student->last_name }}
                  {{ $student->extension_name ?? '' }}
               </h3>
               <p class="text-sm text-gray-500">LRN: {{ $student->learner_reference_number }}</p>
            </div>
         </div>
      </div>
   </div>

   <!-- Enrollment Form -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-8">
         <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
            <h2 class="text-2xl font-semibold">Enrollment Details</h2>
         </div>

         <form action="{{ route('enrollments.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="academic_year_id" id="academic_year_id" value="{{ $currentAcademicYear->id }}">
            <input type="hidden" name="enrollment_status" value="Enrolled">
            <input type="hidden" name="createdBy" value="{{Auth()->user()->id}}">
            <input type="hidden" name="source" value="{{ $source }}">
            <input type="hidden" name="date_enrolled" value="{{ now()->toDateString() }}">

            <!-- Three Column Grid: Program Type, Grade Level, Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
               <!-- Program Type -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Program Type <span class="text-error">*</span>
                     </span>
                  </label>
                  <select name="program_type_id"
                     class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('program_type_id') select-error @enderror">
                     <option disabled selected>Select Program Type</option>
                     @forelse($programTypes as $programType)
                     <option value="{{ $programType->id }}" {{ old('program_type_id')==$programType->id ||
                        ($source === 'regular' && str_contains(strtolower($programType->program_name), 'regular')) ||
                        ($source === 'sped' && str_contains(strtolower($programType->program_name), 'sped'))
                        ? 'selected' : ''
                        }}>
                        {{ $programType->program_name }}
                     </option>
                     @empty
                     <option disabled>No program types available</option>
                     @endforelse
                  </select>
                  @error('program_type_id')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Grade Level -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Grade Level <span class="text-error">*</span>
                     </span>
                  </label>
                  <select name="grade_level_id" id="grade_level_id"
                     class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('grade_level_id') select-error @enderror">
                     <option disabled selected>Select Grade Level</option>
                     @forelse($gradeLevels as $gradeLevel)
                     <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id')==$gradeLevel->id ? 'selected' : ''
                        }}>
                        {{ $gradeLevel->grade_name }}
                     </option>
                     @empty
                     <option disabled>No grade levels available</option>
                     @endforelse
                  </select>
                  @error('grade_level_id')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Section -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Section <span class="text-error">*</span>
                     </span>
                  </label>
                  <select name="section_id" id="section_id"
                     class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('section_id') select-error @enderror">
                     <option disabled selected>Select Grade Level First</option>
                  </select>
                  @error('section_id')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>

            <!-- Capacity Indicator -->
            <div id="capacity_indicator" class="hidden">
               <div class="alert shadow-sm">
                  <svg xmlns="http://www.w3. org/2000/svg" fill="none" viewBox="0 0 24 24"
                     class="stroke-info shrink-0 w-6 h-6">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <div>
                     <h3 class="font-bold" id="capacity_status">Section Capacity</h3>
                     <div class="text-xs" id="capacity_details">
                        <span id="capacity_badge" class="badge badge-sm mr-2"></span>
                        <span id="capacity_text"></span>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Info Alert -->
            {{-- <div class="alert alert-info shadow-sm alert-soft">
               <svg xmlns="http://www. w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  class="stroke-current shrink-0 w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>
               <div>
                  <h3 class="font-bold">Enrollment Date</h3>
                  <div class="text-xs">{{ now()->format('F d, Y') }} (automatically recorded)</div>
               </div>
            </div> --}}

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
               <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
                  Cancel
               </a>
               <button type="submit" class="btn btn-sm btn-primary w-40 rounded-lg px-6">
                  Enroll Student
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      const gradeLevelSelect = document.getElementById('grade_level_id');
      const sectionSelect = document.getElementById('section_id');
      const academicYearId = document.getElementById('academic_year_id'). value;
      const capacityIndicator = document.getElementById('capacity_indicator');
      const capacityBadge = document.getElementById('capacity_badge');
      const capacityText = document.getElementById('capacity_text');
      const capacityStatus = document.getElementById('capacity_status');
      
      // When grade level changes, fetch sections
      gradeLevelSelect.addEventListener('change', function() {
         const gradeLevelId = this.value;
         
         if (! gradeLevelId) {
            sectionSelect.innerHTML = '<option disabled selected>Select Grade Level First</option>';
            capacityIndicator.classList.add('hidden');
            return;
         }
         
         // Show loading
         sectionSelect.innerHTML = '<option disabled selected>Loading sections...</option>';
         sectionSelect.disabled = true;
         capacityIndicator.classList.add('hidden');
         
         // Fetch sections via AJAX
         fetch(`{{ route('enrollments.sections-by-grade') }}?grade_level_id=${gradeLevelId}&academic_year_id=${academicYearId}`)
            .then(response => response.json())
            .then(data => {
               sectionSelect.innerHTML = '<option disabled selected>Select Section</option>';
               
               if (data.length === 0) {
                  sectionSelect.innerHTML += '<option disabled>No sections available</option>';
               } else {
                  data. forEach(section => {
                     const option = document.createElement('option');
                     option.value = section.id;
                     option.textContent = section.display;
                     option.dataset.capacity = section.capacity;
                     option.dataset.enrolled = section.enrolled;
                     option.dataset.available = section.available;
                     option.dataset. status = section.status;
                     option.dataset.color = section.color;
                     
                     // Disable if full
                     if (section.status === 'full') {
                        option.disabled = true;
                        option.textContent += ' - FULL';
                     }
                     
                     sectionSelect. appendChild(option);
                  });
               }
               
               sectionSelect.disabled = false;
            })
            .catch(error => {
               console.error('Error fetching sections:', error);
               sectionSelect.innerHTML = '<option disabled selected>Error loading sections</option>';
               sectionSelect.disabled = false;
            });
      });
      
      // When section changes, show capacity indicator
      sectionSelect.addEventListener('change', function() {
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
         capacityBadge. className = `badge badge-sm badge-${color} mr-2`;
         capacityBadge. textContent = status === 'full' ? 'FULL' : status === 'almost-full' ?  'Almost Full' : 'Available';
         
         // Update text
         capacityText.innerHTML = `<strong>${enrolled}/${capacity}</strong> enrolled • <strong>${available}</strong> slots remaining`;
         
         // Update alert color
         const alertDiv = capacityIndicator.querySelector('. alert');
         alertDiv.className = `alert shadow-sm alert-${color === 'error' ? 'error' : color === 'warning' ? 'warning' : 'success'}`;
         
         // Show indicator
         capacityIndicator.classList.remove('hidden');
      });
   });
</script>
@endsection