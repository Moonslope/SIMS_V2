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

   <div class="rounded-lg bg-blue-600 shadow-lg">
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
               <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white w-35 btn-sm rounded-lg">Save Changes</button>
            </div>
         </div>
      </form>
   </div>

   </div>

   <!-- Active Year Confirmation Modal -->
   @if($activeYear)
   <dialog id="activeYearModal" class="modal">
      <div class="modal-box">
         <h3 class="font-bold text-lg mb-4">Change Active Academic Year?</h3>
         <div class="alert alert-warning alert-soft mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
               <p class="font-semibold">There is already an active academic year.</p>
            </div>
         </div>
         
         <p class="mb-4">Setting this as active will automatically deactivate academic year <strong>{{ $activeYear->year_name }}</strong>.</p>

         <div class="divider"></div>
         <div class="modal-action">
            <button type="button" id="cancelActiveBtn" class="btn btn-sm btn-ghost rounded-lg">
               Cancel
            </button>
            <button type="button" id="confirmActiveBtn" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
               Yes, Set as Active
            </button>
         </div>
      </div>
   </dialog>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const isActiveCheckbox = document.querySelector('input[name="is_active"][type="checkbox"]');
         const form = document.querySelector('form');
         const modal = document.getElementById('activeYearModal');
         const wasActiveInitially = {{ $academicYear->is_active ? 'true' : 'false' }};
         
         @if($activeYear)
         let confirmationNeeded = false;
         
         if (isActiveCheckbox) {
            isActiveCheckbox.addEventListener('change', function() {
               if (this.checked && !wasActiveInitially) {
                  // Show confirmation modal only if changing from inactive to active
                  confirmationNeeded = true;
                  modal.showModal();
               }
            });
         }
         
         // Handle modal buttons
         document.getElementById('cancelActiveBtn').addEventListener('click', function() {
            isActiveCheckbox.checked = wasActiveInitially;
            confirmationNeeded = false;
            modal.close();
         });
         
         document.getElementById('confirmActiveBtn').addEventListener('click', function() {
            confirmationNeeded = false;
            modal.close();
         });
         
         // Intercept form submission if checkbox is checked but not confirmed
         form.addEventListener('submit', function(e) {
            if (isActiveCheckbox.checked && !wasActiveInitially && confirmationNeeded) {
               e.preventDefault();
               modal.showModal();
            }
         });
         @endif
      });
   </script>
   @endif
</div>

<script type="module" src="https://unpkg.com/cally"></script>
@endsection