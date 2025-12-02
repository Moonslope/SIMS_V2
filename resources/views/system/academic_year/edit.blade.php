@extends('layout.layout')
@section('title', 'Edit Academic Year')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academics</a></li>
         <li><a href="{{route('academic-years.index')}}">Academic Years</a></li>
         <li><a>Edit Academic Year</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Academic Year</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('academic-years.update', $academicYear->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="year_name" class="text-sm font-medium">Year Name <span
                           class="text-error">*</span></label>
                     <input name="year_name" type="text" placeholder="e.g., 2024-2025"
                        class="input input-bordered rounded-lg @error('year_name') input-error @enderror"
                        value="{{ old('year_name', $academicYear->year_name) }}" />
                     @error('year_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="start_date" class="text-sm font-medium">Start Date <span
                           class="text-error">*</span></label>
                     <input id="start_date" name="start_date" type="date"
                        class="input input-bordered rounded-lg @error('start_date') input-error @enderror"
                        value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" />
                     @error('start_date')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="end_date" class="text-sm font-medium">End Date <span
                           class="text-error">*</span></label>
                     <input id="end_date" name="end_date" type="date"
                        class="input input-bordered rounded-lg @error('end_date') input-error @enderror"
                        value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" />
                     @error('end_date')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="flex flex-col gap-2">
                  <label for="description" class="text-sm font-medium">Description</label>
                  <textarea name="description"
                     class="textarea w-full textarea-bordered rounded-lg @error('description') textarea-error @enderror"
                     placeholder="Write a short description here (Optional)">{{ old('description', $academicYear->description) }}</textarea>
                  @error('description')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active',
                     $academicYear->is_active) ? 'checked' : '' }}
                  />
                  <label class="text-sm font-medium">Set as Active Academic Year</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('academic-years.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">Save Changes</button>
            </div>
         </div>
      </form>
   </div>

</div>

<script type="module" src="https://unpkg.com/cally"></script>
@endsection