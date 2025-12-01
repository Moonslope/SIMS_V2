@extends('layout.layout')
@section('title', 'Edit Teacher')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academics</a></li>
         <li><a href="{{route('teachers.index')}}">Teachers</a></li>
         <li><a>Edit Teacher Details</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Teacher Details</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('teachers.update', $teacher->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="first_name" class="text-sm font-medium">First Name <span
                           class="text-error">*</span></label>
                     <input name="first_name" type="text" placeholder="Enter first name"
                        class="input input-bordered rounded-lg @error('first_name') input-error @enderror"
                        value="{{ old('first_name', $teacher->first_name) }}" />
                     @error('first_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="middle_name" class="text-sm font-medium">Middle Name</label>
                     <input name="middle_name" type="text" placeholder="Enter middle name"
                        class="input input-bordered rounded-lg @error('middle_name') input-error @enderror"
                        value="{{ old('middle_name', $teacher->middle_name) }}" />
                     @error('middle_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="last_name" class="text-sm font-medium">Last Name <span
                           class="text-error">*</span></label>
                     <input name="last_name" type="text" placeholder="Enter last name"
                        class="input input-bordered rounded-lg @error('last_name') input-error @enderror"
                        value="{{ old('last_name', $teacher->last_name) }}" />
                     @error('last_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="contact_number" class="text-sm font-medium">Contact Number <span
                           class="text-error">*</span></label>
                     <input name="contact_number" type="tel" placeholder="Enter phone number (e.g., 09123456789)"
                        class="input w-full input-bordered rounded-lg @error('contact_number') input-error @enderror"
                        value="{{ old('contact_number', $teacher->contact_number) }}" />
                     @error('contact_number')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="address" class="text-sm font-medium">Address <span
                           class="text-error">*</span></label>
                     <input name="address" type="text" placeholder="Enter complete address"
                        class="input w-full input-bordered rounded-lg @error('address') input-error @enderror"
                        value="{{ old('address', $teacher->address) }}" />
                     @error('address')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active',
                     $teacher->is_active) ? 'checked' : '' }}
                  />
                  <label class="text-sm font-medium">Set as Active Teacher</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('teachers.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">Save Changes</button>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection