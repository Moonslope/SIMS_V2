@extends('layout.layout')
@section('title', 'Create Section')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academics</a></li>
         <li><a href="{{route('sections.index')}}">Sections</a></li>
         <li><a href="{{route('sections.create')}}">Create New Section</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Create New Section</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('sections.store')}}" method="POST">
         @csrf

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="section_name" class="text-sm font-medium">Section Name <span
                           class="text-error">*</span></label>
                     <input name="section_name" type="text" placeholder="e.g., Diamond, Sapphire, Einstein"
                        class="input w-full input-bordered rounded-lg @error('section_name') input-error @enderror"
                        value="{{ old('section_name') }}" />
                     @error('section_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="academic_year_id" class="text-sm font-medium">Academic Year <span
                           class="text-error">*</span></label>
                     <input type="text" class="input w-full input-bordered rounded-lg bg-base-200 cursor-not-allowed"
                        value="{{ $activeAcademicYear->year_name ?? 'No active year' }}" readonly />
                     <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ??  '' }}" />
                     @error('academic_year_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="grade_level_id" class="text-sm font-medium">Grade Level <span
                           class="text-error">*</span></label>
                     <select name="grade_level_id"
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
                     <label for="teacher_id" class="text-sm font-medium">Teacher <span
                           class="text-error">*</span></label>
                     <select name="teacher_id"
                        class="select w-full select-bordered rounded-lg @error('teacher_id') select-error @enderror">
                        <option disabled selected>Select Teacher</option>
                        @forelse($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id')==$teacher->id ? 'selected' : '' }}>
                           {{ $teacher->first_name . ' ' . $teacher->middle_name . ' ' . $teacher->last_name }}
                        </option>
                        @empty
                        <option disabled>No teachers available</option>
                        @endforelse
                     </select>
                     @error('teacher_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="capacity" class="text-sm font-medium">Capacity <span
                           class="text-error">*</span></label>
                     <input name="capacity" type="number" placeholder="e.g., 30"
                        class="input w-full input-bordered rounded-lg @error('capacity') input-error @enderror"
                        value="{{ old('capacity') }}" min="1" max="100" />
                     @error('capacity')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active')
                     ? 'checked' : '' }} />
                  <label class="text-sm font-medium">Set as Active Section</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('sections.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">
                  Save
               </button>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection