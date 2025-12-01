@extends('layout.layout')
@section('title', 'Enrollment - Step 1: Student Information')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Registration</a></li>
         <li><a>Enrollment Form</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - Regular</h1>
   </div>

   <!-- Progress Bar -->
   <div class="flex justify-center my-4">
      <ul class="steps steps-vertical lg:steps-horizontal text-sm">
         <li class="step step-primary">Student Information</li>
         <li class="step">Guardian Information</li>
         <li class="step">Documents</li>
         <li class="step">Review</li>
      </ul>
   </div>

   <div class="rounded-lg bg-base-100 shadow-md p-8">
      <div class="flex items-center gap-3 mb-6">
         <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
         <h2 class="text-2xl font-semibold">Student Information</h2>
      </div>

      <form action="{{ route('students.registration.store-step1') }}" method="POST" class="space-y-6">
         @csrf

         <!-- Learner Reference Number -->
         <div class="form-control w-full">
            <label class="label mb-2">
               <span class="label-text font-medium text-base">
                  Learner Reference Number <span class="text-error">*</span>
               </span>
            </label>
            <input name="learner_reference_number" type="text"
               class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('learner_reference_number') input-error @enderror"
               value="{{ old('learner_reference_number', session('student_registration. learner_reference_number')) }}"
               placeholder="Enter 12-digit LRN">
            @error('learner_reference_number')
            <label class="label">
               <span class="label-text-alt text-error text-sm">{{ $message }}</span>
            </label>
            @enderror
         </div>

         <!-- Name Fields -->
         <div class="grid grid-cols-1 md:grid-cols-8 gap-6">
            <div class="form-control w-full md:col-span-2">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     First Name <span class="text-error">*</span>
                  </span>
               </label>
               <input name="first_name" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('first_name') input-error @enderror"
                  value="{{ old('first_name', session('student_registration.first_name')) }}"
                  placeholder="Enter first name">
               @error('first_name')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full md:col-span-2">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Middle Name</span>
               </label>
               <input name="middle_name" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('middle_name') input-error @enderror"
                  value="{{ old('middle_name', session('student_registration. middle_name')) }}"
                  placeholder="Enter middle name">
               @error('middle_name')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full md:col-span-2">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Last Name <span class="text-error">*</span>
                  </span>
               </label>
               <input name="last_name" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('last_name') input-error @enderror"
                  value="{{ old('last_name', session('student_registration.last_name')) }}"
                  placeholder="Enter last name">
               @error('last_name')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full md:col-span-2">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Suffix</span>
               </label>
               <input name="extension_name" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('extension_name') input-error @enderror"
                  value="{{ old('extension_name', session('student_registration.extension_name')) }}"
                  placeholder="Jr, Sr">
               @error('extension_name')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>
         </div>

         <!-- Personal Details Row 1 -->
         <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Nickname</span>
               </label>
               <input name="nickname" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('nickname') input-error @enderror"
                  value="{{ old('nickname', session('student_registration.nickname')) }}" placeholder="Enter nickname">
               @error('nickname')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Gender <span class="text-error">*</span>
                  </span>
               </label>
               <select
                  class="select select-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('gender') select-error @enderror"
                  name="gender">
                  <option disabled selected>Select gender</option>
                  <option value="male" {{ old('gender', session('student_registration.gender'))=='male' ? 'selected'
                     : '' }}>Male</option>
                  <option value="female" {{ old('gender', session('student_registration.gender'))=='female' ? 'selected'
                     : '' }}>Female</option>
               </select>
               @error('gender')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Birthdate <span class="text-error">*</span>
                  </span>
               </label>
               <input name="birthdate" type="date"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('birthdate') input-error @enderror"
                  value="{{ old('birthdate', session('student_registration.birthdate')) }}" max="{{ date('Y-m-d') }}">
               @error('birthdate')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Birthplace <span class="text-error">*</span></span>
               </label>
               <input name="birthplace" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('birthplace') input-error @enderror"
                  value="{{ old('birthplace', session('student_registration.birthplace')) }}"
                  placeholder="Enter birthplace">
               @error('birthplace')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>
         </div>

         <!-- Cultural/Religious Information -->
         <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Nationality <span class="text-error">*</span>
                  </span>
               </label>
               <input name="nationality" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('nationality') input-error @enderror"
                  value="{{ old('nationality', session('student_registration.nationality')) }}"
                  placeholder="e.g., Filipino">
               @error('nationality')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Spoken Dialect <span class="text-error">*</span>
                  </span>
               </label>
               <input name="spoken_dialect" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('spoken_dialect') input-error @enderror"
                  value="{{ old('spoken_dialect', session('student_registration.spoken_dialect')) }}"
                  placeholder="e.g., Tagalog, Cebuano">
               @error('spoken_dialect')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Other Dialect</span>
               </label>
               <input name="other_spoken_dialect" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('other_spoken_dialect') input-error @enderror"
                  value="{{ old('other_spoken_dialect', session('student_registration.other_spoken_dialect')) }}"
                  placeholder="Other dialect">
               @error('other_spoken_dialect')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Religion</span>
               </label>
               <input name="religion" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('religion') input-error @enderror"
                  value="{{ old('religion', session('student_registration.religion')) }}" placeholder="Enter religion">
               @error('religion')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>
         </div>

         <!-- Address -->
         <div class="form-control w-full">
            <label class="label mb-2">
               <span class="label-text font-medium text-base">Permanent Address <span class="text-error">*</span></span>
            </label>
            <input name="address" type="text"
               class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('address') input-error @enderror"
               value="{{ old('address', session('student_registration.address')) }}"
               placeholder="House No., Street, Barangay, City, Province">
            @error('address')
            <label class="label">
               <span class="label-text-alt text-error text-sm">{{ $message }}</span>
            </label>
            @enderror
         </div>

         <!-- Divider -->
         <div class="divider"></div>

         <!-- Action Buttons -->
         <div class="flex items-center justify-end gap-3">
            <a href="{{ route('students.registration.step1') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
               Cancel
            </a>
            <button type="submit" class="btn btn-sm btn-primary w-35 rounded-lg px-6">
               Next
            </button>
         </div>
      </form>
   </div>
</div>
@endsection