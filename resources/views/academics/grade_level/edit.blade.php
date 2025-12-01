@extends('layout.layout')
@section('title', 'Edit Grade Level')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academics</a></li>
         <li><a href="{{route('grade-levels.index')}}">Grade Levels</a></li>
         <li><a>Edit Grade Level Details</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Grade Level Details</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('grade-levels.update', $gradeLevel->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="flex flex-col gap-2">
                  <label for="grade_name" class="text-sm font-medium">Grade Name <span
                        class="text-error">*</span></label>
                  <input name="grade_name" type="text" placeholder="e.g., Grade 1, Kindergarten"
                     class="input w-full input-bordered rounded-lg @error('grade_name') input-error @enderror"
                     value="{{ old('grade_name', $gradeLevel->grade_name) }}" />
                  @error('grade_name')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>

               <div class="flex flex-col gap-2">
                  <label for="description" class="text-sm font-medium">Description</label>
                  <textarea name="description" rows="4"
                     class="textarea w-full textarea-bordered rounded-lg @error('description') textarea-error @enderror"
                     placeholder="Write a short description about this grade level...">{{ old('description', $gradeLevel->description) }}</textarea>
                  @error('description')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active',
                     $gradeLevel->is_active) ? 'checked' : '' }}
                  />
                  <label class="text-sm font-medium">Set as Active Grade Level</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('grade-levels.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">
                  Save Changes
               </button>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection