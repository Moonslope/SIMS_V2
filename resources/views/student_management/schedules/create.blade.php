@extends('layout.layout')
@section('title', 'Create Schedule')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{route('schedules.index')}}">Academics</a></li>
         <li><a>Schedules</a></li>
         <li class="text-blue-600 font-semibold">Create</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Create New Schedule</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('schedules.store')}}" method="POST" id="scheduleForm">
         @csrf

         <div class="flex flex-col gap-8">
            <!-- Program Type and Grade Level Selection -->
            <div class="flex flex-col gap-4 pb-4">
               <h2 class="text-lg font-semibold">Select Program Type and Grade Level</h2>
               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="program_type_id" class="text-sm font-medium">Program <span
                           class="text-error">*</span></label>
                     <select name="program_type_id" id="program_type_id"
                        class="select w-full select-bordered rounded-lg @error('program_type_id') select-error @enderror">
                        <option disabled selected>Select Program Type</option>
                        @forelse($programTypes as $programType)
                        <option value="{{ $programType->id }}" {{ old('program_type_id')==$programType->id ? 'selected'
                           : '' }}>
                           {{ $programType->program_name }}
                        </option>
                        @empty
                        <option disabled>No program types available</option>
                        @endforelse
                     </select>
                     @error('program_type_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="grade_level_id" class="text-sm font-medium">Grade Level <span
                           class="text-error">*</span></label>
                     <select name="grade_level_id" id="grade_level_id"
                        class="select w-full select-bordered rounded-lg @error('grade_level_id') select-error @enderror">
                        <option disabled selected>Select Grade Level</option>
                        @forelse($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id')==$gradeLevel->id ? 'selected' :
                           '' }}>
                           {{ $gradeLevel->grade_name }}
                        </option>
                        @empty
                        <option disabled>No grade levels available</option>
                        @endforelse
                     </select>
                     @error('grade_level_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="academic_year_id" class="text-sm font-medium">Academic Year <span
                           class="text-error">*</span></label>
                     <input type="text" class="input w-full input-bordered rounded-lg bg-base-200 cursor-not-allowed"
                        value="{{ $currentAcademicYear->year_name }}" readonly />
                     <input type="hidden" name="academic_year_id" value="{{ $currentAcademicYear->id }}" />
                     @error('academic_year_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>
            </div>

            <div class="divider p-0 m-0"></div>

            <!-- Schedules Section -->
            <div class="flex flex-col gap-4">
               <div class="flex justify-between items-center">
                  <h2 class="text-lg font-semibold">Schedule Details</h2>
                  <div class="flex gap-2">
                     <button type="button" id="clearAllBtn" class="btn btn-sm btn-ghost text-error rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Clear All
                     </button>
                     <button type="button" id="addScheduleBtn" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Schedule
                     </button>
                  </div>
               </div>

               <div id="schedulesContainer" class="flex flex-col gap-4">
                  <!-- Schedule items will be added here dynamically -->
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('schedules.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white w-35 btn-sm rounded-lg">
                  Save All Schedules
               </button>
            </div>
         </div>
      </form>
   </div>
</div>

<script>
   window.subjectsData = @json($subjects);
</script>
<script src="{{ asset('js/schedules-create.js') }}"></script>

@endsection