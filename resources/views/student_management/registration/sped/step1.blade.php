@extends('layout.layout')
@section('title', 'SPED Enrollment - Step 1: Student Information')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{ route('students.index') }}">Students</a></li>
         <li><a>SPED Registration</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - SPED</h1>
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

   <form action="{{ route('students.sped-registration.store-step1') }}" method="POST" class="space-y-6">
      @csrf

      <!-- Basic Information Card -->
      <div class="card bg-base-100 shadow-md rounded-lg">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-primary rounded"></div>
               <h2 class="text-xl font-semibold">Student Information</h2>
            </div>

            <!-- LRN -->
            <div class="form-control w-full mb-6">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">
                     Learner Reference Number <span class="text-error">*</span>
                  </span>
               </label>
               <input name="learner_reference_number" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('learner_reference_number') input-error @enderror"
                  value="{{ old('learner_reference_number', session('sped_student_registration. learner_reference_number')) }}"
                  placeholder="Enter 12-digit LRN">
               @error('learner_reference_number')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <!-- Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-8 gap-6 mb-6">
               <div class="form-control w-full md:col-span-2">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        First Name <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="first_name" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('first_name') input-error @enderror"
                     value="{{ old('first_name', session('sped_student_registration.first_name')) }}"
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
                     value="{{ old('middle_name', session('sped_student_registration.middle_name')) }}"
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
                     value="{{ old('last_name', session('sped_student_registration.last_name')) }}"
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
                     value="{{ old('extension_name', session('sped_student_registration.extension_name')) }}"
                     placeholder="Jr, Sr">
                  @error('extension_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>

            <!-- Personal Details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Nickname</span>
                  </label>
                  <input name="nickname" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('nickname') input-error @enderror"
                     value="{{ old('nickname', session('sped_student_registration.nickname')) }}"
                     placeholder="Enter nickname">
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
                     <option value="male" {{ old('gender', session('sped_student_registration.gender'))=='male'
                        ? 'selected' : '' }}>Male</option>
                     <option value="female" {{ old('gender', session('sped_student_registration.gender'))=='female'
                        ? 'selected' : '' }}>Female</option>
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
                     value="{{ old('birthdate', session('sped_student_registration.birthdate')) }}"
                     max="{{ date('Y-m-d') }}">
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
                     value="{{ old('birthplace', session('sped_student_registration.birthplace')) }}"
                     placeholder="Enter birthplace">
                  @error('birthplace')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>

            <!-- Cultural Information -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Nationality <span class="text-error">*</span></span>
                  </label>
                  <input name="nationality" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('nationality') input-error @enderror"
                     value="{{ old('nationality', session('sped_student_registration.nationality')) }}"
                     placeholder="e.g., Filipino">
                  @error('nationality')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Spoken Dialect <span
                           class="text-error">*</span></span>
                  </label>
                  <input name="spoken_dialect" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('spoken_dialect') input-error @enderror"
                     value="{{ old('spoken_dialect', session('sped_student_registration.spoken_dialect')) }}"
                     placeholder="e.g., Tagalog">
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
                     value="{{ old('other_spoken_dialect', session('sped_student_registration.other_spoken_dialect')) }}"
                     placeholder="Other dialect">
                  @error('other_spoken_dialect')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Religion <span class="text-error">*</span></span>
                  </label>
                  <input name="religion" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('religion') input-error @enderror"
                     value="{{ old('religion', session('sped_student_registration.religion')) }}"
                     placeholder="Enter religion">
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
                  <span class="label-text font-medium text-base">Permanent Address <span
                        class="text-error">*</span></span>
               </label>
               <input name="address" type="text"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('address') input-error @enderror"
                  value="{{ old('address', session('sped_student_registration. address')) }}"
                  placeholder="House No., Street, Barangay, City, Province">
               @error('address')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>
         </div>
      </div>

      <!-- SPED Specific Information Card -->
      <div class="card bg-base-100 shadow-md rounded-lg">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-primary rounded"></div>
               <h2 class="text-xl font-semibold">Special Education (SPED) Details</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Type of Disability <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="type_of_disability" type="text"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('type_of_disability') input-error @enderror"
                     placeholder="Enter type of disability"
                     value="{{ old('type_of_disability', session('sped_student_registration.type_of_disability')) }}">
                  @error('type_of_disability')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Date of Diagnosis <span class="text-error">*</span>
                     </span>
                  </label>
                  <input name="date_of_diagnosis" type="date"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('date_of_diagnosis') input-error @enderror"
                     value="{{ old('date_of_diagnosis', session('sped_student_registration. date_of_diagnosis')) }}"
                     max="{{ date('Y-m-d') }}">
                  @error('date_of_diagnosis')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>

            <div class="form-control w-full">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Cause of Disability</span>
                  <span class="label-text-alt text-gray-500">(Optional)</span>
               </label>
               <textarea name="cause_of_disability" rows="4"
                  class="textarea textarea-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('cause_of_disability') textarea-error @enderror"
                  placeholder="Please describe the cause of disability, if known... ">{{ old('cause_of_disability', session('sped_student_registration.cause_of_disability')) }}</textarea>
               @error('cause_of_disability')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>
         </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3">
         <a href="{{ route('students.index') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
            Cancel
         </a>
         <button type="submit" class="btn btn-sm btn-primary w-35 rounded-lg px-6">
            Next
         </button>
      </div>
   </form>
</div>
@endsection