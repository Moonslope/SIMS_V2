@extends('layout.portal')
@section('title', 'My Profile')
@section('content')

<div class="p-5">
   <!-- Page Header -->
   <div class="my-8">
      <h1 class="text-4xl font-bold text-blue-600 mb-2 text-center">My Profile</h1>
   </div>

   <!-- Profile Header Card -->
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
               <div class="avatar placeholder">
                  <div class="bg-blue-600 text-white flex justify-center items-center rounded-full w-20 h-20">
                     <span class="text-3xl font-bold">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{
                        strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                     </span>
                  </div>
               </div>
               <div class="text-center sm:text-left">
                  <h2 class="text-2xl font-bold text-blue-600">
                     {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}
                  </h2>
                  <p class="text-gray-600">Student</p>
                  <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
               </div>
            </div>
            <div class="w-full sm:w-auto">
               <a href="{{ route('students.change-password') }}"
                  class="btn bg-blue-600 text-white btn-sm sm:btn-sm w-full sm:w-auto rounded-lg">
                  <i class="fi fi-sr-key"></i>
                  Change Password
               </a>
            </div>
         </div>
      </div>
   </div>

   <!-- Student Information Card -->
   @if($student)
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-blue-600 rounded"></div>
            <h2 class="text-xl font-semibold">Student Information</h2>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Learner Reference Number</span>
               </label>
               <p class="text-gray-800">{{ $student->learner_reference_number }}</p>
            </div>
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Birthdate</span>
               </label>
               <p class="text-gray-800">{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}</p>
            </div>
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Gender</span>
               </label>
               <p class="text-gray-800">{{ ucfirst($student->gender) }}</p>
            </div>
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Address</span>
               </label>
               <p class="text-gray-800">{{ $student->address }}</p>
            </div>
         </div>
      </div>
   </div>
   @endif

   <!-- Guardian Information Card -->
   @if($guardians && $guardians->count() > 0)
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-blue-600 rounded"></div>
            <h2 class="text-xl font-semibold">Guardian Information</h2>
         </div>

         @foreach($guardians as $guardian)
         <div class="mb-6 last:mb-0">
            <h3 class="text-lg font-semibold text-blue-600 mb-3">
               {{ ucfirst($guardian->relationship) }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Full Name</span>
                  </label>
                  <p class="text-gray-800">
                     {{ $guardian->first_name }}
                     {{ $guardian->middle_name ? $guardian->middle_name . ' ' : '' }}
                     {{ $guardian->last_name }}
                  </p>
               </div>
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Contact Number</span>
                  </label>
                  <p class="text-gray-800">{{ $guardian->contact_number }}</p>
               </div>
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Email Address</span>
                  </label>
                  <p class="text-gray-800">{{ $guardian->email ?? 'Not provided' }}</p>
               </div>
               @if($guardian->address)
               <div class="form-control md:col-span-2">
                  <label class="label">
                     <span class="label-text font-semibold">Address</span>
                  </label>
                  <p class="text-gray-800">{{ $guardian->address }}</p>
               </div>
               @endif
            </div>
            @if(!$loop->last)
            <div class="divider"></div>
            @endif
         </div>
         @endforeach
      </div>
   </div>
   @endif

   <!-- Uploaded Documents Card -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-blue-600 rounded"></div>
            <h2 class="text-xl font-semibold">My Documents</h2>
         </div>
         <p class="text-sm text-gray-600 mb-4">These are your uploaded documents managed by school staff.</p>

         @if($documents && $documents->count() > 0)
         <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($documents as $document)
            <div class="flex items-center gap-3 p-3 bg-base-200 rounded-lg">
               <i class="fi fi-sr-document text-blue-600 text-2xl"></i>
               <span class="font-medium">{{ $document->document_type }}</span>
            </div>
            @endforeach
         </div>
         @else
         <div class="text-center py-12">
            <i class="fi fi-sr-folder-open text-[80px] text-gray-300"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2 mt-4">No Documents Yet</h3>
            <p class="text-gray-500">Your documents will appear here once uploaded by school staff.</p>
         </div>
         @endif
      </div>
   </div>
</div>

@endsection