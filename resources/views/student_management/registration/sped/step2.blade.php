@extends('layout.layout')
@section('title', 'SPED Registration - Step 2: Guardian Information')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{ route('students.index') }}">Students</a></li>
         <li><a>SPED Registration</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - SPED</h1>
   </div>

   <!-- Progress Bar -->
   <div class="flex justify-center my-4">
      <ul class="steps steps-vertical lg:steps-horizontal text-sm">
         <li class="step step-primary">Student Information</li>
         <li class="step step-primary">Guardian Information</li>
         <li class="step">Documents</li>
         <li class="step">Review</li>
      </ul>
   </div>

   <form action="{{ route('students.sped-registration.store-step2') }}" method="POST" class="space-y-6">
      @csrf

      <!-- Guardian Information Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
               <h2 class="text-xl font-semibold">Guardian/Parent Information</h2>
            </div>

            <!-- Guardian Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        First Name <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="guardian_first_name" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('guardian_first_name') input-error @enderror"
                     value="{{ old('guardian_first_name', session('sped_student_registration.guardian_first_name')) }}"
                     placeholder="Enter first name">
                  @error('guardian_first_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Middle Name</span>
                     <span class="label-text-alt text-gray-500">(Optional)</span>
                  </label>
                  <input name="guardian_middle_name" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('guardian_middle_name') input-error @enderror"
                     value="{{ old('guardian_middle_name', session('sped_student_registration.guardian_middle_name')) }}"
                     placeholder="Enter middle name">
                  @error('guardian_middle_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Last Name <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="guardian_last_name" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('guardian_last_name') input-error @enderror"
                     value="{{ old('guardian_last_name', session('sped_student_registration.guardian_last_name')) }}"
                     placeholder="Enter last name">
                  @error('guardian_last_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Relationship <span class="text-error">*</span>
                     </span>
                  </label>
                  <select name="relation"
                     class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('relation') select-error @enderror">
                     <option disabled selected>Select relationship</option>
                     <option value="Father" {{ old('relation', session('sped_student_registration.relation'))=='Father'
                        ? 'selected' : '' }}>Father</option>
                     <option value="Mother" {{ old('relation', session('sped_student_registration.relation'))=='Mother'
                        ? 'selected' : '' }}>Mother</option>
                     <option value="Guardian" {{ old('relation', session('sped_student_registration.
                        relation'))=='Guardian' ? 'selected' : '' }}>Guardian</option>
                  </select>
                  @error('relation')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Contact Number <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="contact_number" type="tel"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('contact_number') input-error @enderror"
                     value="{{ old('contact_number', session('sped_student_registration.contact_number')) }}"
                     placeholder="e.g., 09171234567">
                  @error('contact_number')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Email Address</span>
                  </label>
                  <input name="email" type="email"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('email') input-error @enderror"
                     value="{{ old('email', session('sped_student_registration.email')) }}"
                     placeholder="email@example.com">
                  @error('email')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Address</span>
                  </label>
                  <input name="guardian_address" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('guardian_address') input-error @enderror"
                     value="{{ old('guardian_address', session('sped_student_registration.guardian_address')) }}"
                     placeholder="Complete address">
                  @error('guardian_address')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>
         </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3">
         <a href="{{ route('students.sped-registration.step1') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
            Previous
         </a>
         <button type="submit" class="btn btn-sm btn-primary w-40 rounded-lg px-6">
            Next
         </button>
      </div>
   </form>
</div>
@endsection