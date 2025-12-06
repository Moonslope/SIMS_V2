@extends('layout.layout')
@section('title', 'Edit Schedule')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{route('schedules.index')}}">Academics</a></li>
         <li><a>Schedules</a></li>
         <li class="text-blue-600 font-semibold">Edit</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Schedule Details</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('schedules.update', $schedule->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="subject_id" class="text-sm font-medium">Subject <span
                           class="text-error">*</span></label>
                     <select name="subject_id"
                        class="select w-full select-bordered rounded-lg @error('subject_id') select-error @enderror">
                        <option disabled>Select Subject</option>
                        @forelse($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id)==$subject->id ?
                           'selected' : '' }}>
                           {{ $subject->subject_name }}
                        </option>
                        @empty
                        <option disabled>No subjects available</option>
                        @endforelse
                     </select>
                     @error('subject_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="academic_year_id" class="text-sm font-medium">Academic Year</label>
                     <input type="text" class="input w-full input-bordered rounded-lg bg-base-200 cursor-not-allowed"
                        value="{{ $schedule->academicYear->year_name ??  'N/A' }}" readonly />
                     <input type="hidden" name="academic_year_id" value="{{ $schedule->academic_year_id }}" />
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="program_type_id" class="text-sm font-medium">Program <span
                           class="text-error">*</span></label>
                     <select name="program_type_id"
                        class="select w-full select-bordered rounded-lg @error('program_type_id') select-error @enderror">
                        <option disabled>Select Program Type</option>
                        @forelse($programTypes as $programType)
                        <option value="{{ $programType->id }}" {{ old('program_type_id', $schedule->
                           program_type_id)==$programType->id ? 'selected' : '' }}>
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
                     <select name="grade_level_id"
                        class="select w-full select-bordered rounded-lg @error('grade_level_id') select-error @enderror">
                        <option disabled>Select Grade Level</option>
                        @forelse($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id', $schedule->
                           grade_level_id)==$gradeLevel->id ? 'selected' : '' }}>
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
               </div>

               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="day_of_the_week" class="text-sm font-medium">Day of Week <span
                           class="text-error">*</span></label>
                     <select name="day_of_the_week"
                        class="select w-full select-bordered rounded-lg @error('day_of_the_week') select-error @enderror">
                        <option disabled>Select Day</option>
                        <option value="Monday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Monday' ?
                           'selected' : '' }}>Monday</option>
                        <option value="Tuesday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Tuesday' ?
                           'selected' : '' }}>Tuesday</option>
                        <option value="Wednesday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Wednesday' ?
                           'selected' : '' }}>Wednesday</option>
                        <option value="Thursday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Thursday' ?
                           'selected' : '' }}>Thursday</option>
                        <option value="Friday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Friday' ?
                           'selected' : '' }}>Friday</option>
                        <option value="Saturday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Saturday' ?
                           'selected' : '' }}>Saturday</option>
                        <option value="Sunday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Sunday' ?
                           'selected' : '' }}>Sunday</option>
                        <option value="Monday to Friday" {{ old('day_of_the_week', $schedule->day_of_the_week)=='Monday
                           to Friday' ?
                           'selected' : '' }}>Monday to Friday</option>
                     </select>
                     @error('day_of_the_week')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="start_time" class="text-sm font-medium">Start Time <span
                           class="text-error">*</span></label>
                     <input type="time" name="start_time"
                        class="input input-bordered rounded-lg @error('start_time') input-error @enderror"
                        value="{{ old('start_time', $schedule->start_time) }}" min="06:00" max="20:00" required />
                     @error('start_time')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="end_time" class="text-sm font-medium">End Time <span
                           class="text-error">*</span></label>
                     <input type="time" name="end_time"
                        class="input input-bordered rounded-lg @error('end_time') input-error @enderror"
                        value="{{ old('end_time', $schedule->end_time) }}" min="06:00" max="20:00" required />
                     @error('end_time')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active',
                     $schedule->is_active) ? 'checked' : '' }}
                  />
                  <label class="text-sm font-medium">Set as Active Schedule</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('schedules.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white w-35 btn-sm rounded-lg">
                  Update Schedule
               </button>
            </div>
         </div>
      </form>
   </div>
</div>
@endsection