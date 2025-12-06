@extends('layout.layout')
@section('title', 'Edit Student Profile')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{ route('students.index') }}">All Students</a></li>
         <li><a href="{{ route('students.student-profile', $student->id) }}">Student Profile</a></li>
         <li>Edit</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Student Profile</h1>
      <a href="{{ route('students.student-profile', $student->id) }}" class="btn btn-sm btn-ghost text-base-300 me-3 rounded-lg">
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
         </svg>
         Back to Profile
      </a>
   </div>

   @if(session('error'))
   <div class="alert alert-error shadow-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ session('error') }}</span>
   </div>
   @endif

   <form action="{{ route('students.update', $student->id) }}" method="POST">
      @csrf
      @method('PUT')

      <!-- Personal Information Card -->
      <div class="card bg-base-100 shadow-md rounded-lg mb-5">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Personal Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <!-- Learner Reference Number -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Learner Reference Number <span class="text-error">*</span></span>
                  </label>
                  <input type="text" name="learner_reference_number" 
                     class="input input-bordered rounded-lg w-full @error('learner_reference_number') input-error @enderror"
                     value="{{ old('learner_reference_number', $student->learner_reference_number) }}" required>
                  @error('learner_reference_number')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- First Name -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">First Name <span class="text-error">*</span></span>
                  </label>
                  <input type="text" name="first_name" 
                     class="input input-bordered roundedlg w-full @error('first_name') input-error @enderror"
                     value="{{ old('first_name', $student->first_name) }}" required>
                  @error('first_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Middle Name -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Middle Name</span>
                  </label>
                  <input type="text" name="middle_name" 
                     class="input input-bordered rounded-lg w-full @error('middle_name') input-error @enderror"
                     value="{{ old('middle_name', $student->middle_name) }}">
                  @error('middle_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Last Name -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Last Name <span class="text-error">*</span></span>
                  </label>
                  <input type="text" name="last_name" 
                     class="input input-bordered rounded-lg w-full @error('last_name') input-error @enderror"
                     value="{{ old('last_name', $student->last_name) }}" required>
                  @error('last_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Extension Name -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Extension Name</span>
                  </label>
                  <input type="text" name="extension_name" 
                     class="input input-bordered rounded-lg w-full @error('extension_name') input-error @enderror"
                     value="{{ old('extension_name', $student->extension_name) }}">
                  @error('extension_name')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Nickname -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Nickname</span>
                  </label>
                  <input type="text" name="nickname" 
                     class="input input-bordered rounded-lg w-full @error('nickname') input-error @enderror"
                     value="{{ old('nickname', $student->nickname) }}">
                  @error('nickname')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Gender -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Gender <span class="text-error">*</span></span>
                  </label>
                  <select name="gender" class="select select-bordered rounded-lg w-full @error('gender') select-error @enderror" required>
                     <option value="">Select Gender</option>
                     <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                     <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                  </select>
                  @error('gender')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Birthdate -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Birthdate <span class="text-error">*</span></span>
                  </label>
                  <input type="date" name="birthdate" 
                     class="input input-bordered rounded-lg w-full @error('birthdate') input-error @enderror"
                     value="{{ old('birthdate', $student->birthdate) }}" required>
                  @error('birthdate')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Birthplace -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Birthplace</span>
                  </label>
                  <input type="text" name="birthplace" 
                     class="input input-bordered rounded-lg w-full @error('birthplace') input-error @enderror"
                     value="{{ old('birthplace', $student->birthplace) }}">
                  @error('birthplace')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Nationality -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Nationality</span>
                  </label>
                  <input type="text" name="nationality" 
                     class="input input-bordered rounded-lg w-full @error('nationality') input-error @enderror"
                     value="{{ old('nationality', $student->nationality) }}">
                  @error('nationality')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Spoken Dialect -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Spoken Dialect</span>
                  </label>
                  <input type="text" name="spoken_dialect" 
                     class="input input-bordered rounded-lg w-full @error('spoken_dialect') input-error @enderror"
                     value="{{ old('spoken_dialect', $student->spoken_dialect) }}">
                  @error('spoken_dialect')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Other Spoken Dialect -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Other Spoken Dialect</span>
                  </label>
                  <input type="text" name="other_spoken_dialect" 
                     class="input input-bordered rounded-lg w-full @error('other_spoken_dialect') input-error @enderror"
                     value="{{ old('other_spoken_dialect', $student->other_spoken_dialect) }}">
                  @error('other_spoken_dialect')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Religion -->
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-medium">Religion</span>
                  </label>
                  <input type="text" name="religion" 
                     class="input input-bordered rounded-lg w-full @error('religion') input-error @enderror"
                     value="{{ old('religion', $student->religion) }}">
                  @error('religion')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Address (full width) -->
               <div class="form-control w-full md:col-span-2">
                  <label class="label">
                     <span class="label-text font-medium">Address</span>
                  </label>
                  <textarea name="address" rows="2"
                     class="textarea textarea-bordered rounded-lg w-full @error('address') textarea-error @enderror">{{ old('address', $student->address) }}</textarea>
                  @error('address')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>
         </div>
      </div>

      <!-- Guardian Information Card -->
      @if($student->guardians->count() > 0)
      <div class="card bg-base-100 shadow-md rounded-lg mb-5">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Guardian Information</h2>
            </div>

            @foreach($student->guardians as $index => $guardian)
            <div class="p-4 bg-base-200 rounded-lg {{ !$loop->last ? 'mb-4' : '' }}">
               <h3 class="font-semibold mb-4 text-lg">Guardian {{$index + 1}}</h3>
               <input type="hidden" name="guardians[{{ $index }}][id]" value="{{ $guardian->id }}">
               
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Guardian First Name -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">First Name <span class="text-error">*</span></span>
                     </label>
                     <input type="text" name="guardians[{{ $index }}][first_name]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.first_name") input-error @enderror"
                        value="{{ old("guardians.{$index}.first_name", $guardian->first_name) }}" required>
                     @error("guardians.{$index}.first_name")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Middle Name -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">Middle Name</span>
                     </label>
                     <input type="text" name="guardians[{{ $index }}][middle_name]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.middle_name") input-error @enderror"
                        value="{{ old("guardians.{$index}.middle_name", $guardian->middle_name) }}">
                     @error("guardians.{$index}.middle_name")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Last Name -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">Last Name <span class="text-error">*</span></span>
                     </label>
                     <input type="text" name="guardians[{{ $index }}][last_name]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.last_name") input-error @enderror"
                        value="{{ old("guardians.{$index}.last_name", $guardian->last_name) }}" required>
                     @error("guardians.{$index}.last_name")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Relationship -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">Relationship <span class="text-error">*</span></span>
                     </label>
                     <input type="text" name="guardians[{{ $index }}][relationship]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.relationship") input-error @enderror"
                        value="{{ old("guardians.{$index}.relationship", $guardian->relationship) }}" required>
                     @error("guardians.{$index}.relationship")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Contact Number -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">Contact Number <span class="text-error">*</span></span>
                     </label>
                     <input type="text" name="guardians[{{ $index }}][contact_number]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.contact_number") input-error @enderror"
                        value="{{ old("guardians.{$index}.contact_number", $guardian->contact_number) }}" required>
                     @error("guardians.{$index}.contact_number")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Email -->
                  <div class="form-control w-full">
                     <label class="label">
                        <span class="label-text font-medium">Email</span>
                     </label>
                     <input type="email" name="guardians[{{ $index }}][email]" 
                        class="input input-bordered rounded-lg w-full @error("guardians.{$index}.email") input-error @enderror"
                        value="{{ old("guardians.{$index}.email", $guardian->email) }}">
                     @error("guardians.{$index}.email")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Guardian Address -->
                  <div class="form-control w-full md:col-span-2">
                     <label class="label">
                        <span class="label-text font-medium">Address</span>
                     </label>
                     <textarea name="guardians[{{ $index }}][address]" rows="2"
                        class="textarea textarea-bordered rounded-lg w-full @error("guardians.{$index}.address") textarea-error @enderror">{{ old("guardians.{$index}.address", $guardian->address) }}</textarea>
                     @error("guardians.{$index}.address")
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>
               </div>
            </div>
            @endforeach
         </div>
      </div>
      @endif

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3">
         <a href="{{ route('students.student-profile', $student->id) }}" class="btn btn-ghost rounded-lg">
            Cancel
         </a>
         <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Update Profile
         </button>
      </div>
   </form>
</div>
@endsection
