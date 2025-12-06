@extends('layout.layout')
@section('title', 'SPED Registration - Review')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{ route('students.index') }}">Students</a></li>
         <li><a>SPED Registration</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - SPED</h1>
   </div>

   <!-- Progress Bar -->
   <div class="flex justify-center my-4">
      <ul class="steps steps-vertical lg:steps-horizontal text-sm">
         <li class="step step-primary">Student Information</li>
         <li class="step step-primary">Guardian Information</li>
         <li class="step step-primary">Documents</li>
         <li class="step step-primary">Review</li>
      </ul>
   </div>

   <!-- Review Alert -->
   <div class="alert alert-info">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <div>
         <h3 class="font-bold">Review Registration Details</h3>
         <div class="text-xs">Please review all information carefully before submitting. You can go back to edit any
            section.</div>
      </div>
   </div>

   <div class="space-y-6">
      <!-- Student Information Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-4">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Student Information</h2>
               </div>
               <a href="{{ route('students.sped-registration.step1') }}" class="btn btn-xs btn-ghost rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2. 828l8.586-8. 586z" />
                  </svg>
                  Edit
               </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Full Name</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['first_name'] }} {{ $registrationData['middle_name'] }} {{
                     $registrationData['last_name'] }} {{ $registrationData['extension_name'] ?? '' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">LRN</span>
                  </label>
                  <p class="pl-2 font-mono">{{ $registrationData['learner_reference_number'] }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Gender</span>
                  </label>
                  <p class="pl-2">{{ ucfirst($registrationData['gender']) }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Birthdate</span>
                  </label>
                  <p class="pl-2">{{ \Carbon\Carbon::parse($registrationData['birthdate'])->format('F d, Y') }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Birthplace</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['birthplace'] ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Nationality</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['nationality'] ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Religion</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['religion'] ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Nickname</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['nickname'] ?? 'N/A' }}</p>
               </div>

               <div class="form-control md:col-span-2">
                  <label class="label">
                     <span class="label-text font-semibold">Address</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['address'] ?? 'N/A' }}</p>
               </div>
            </div>
         </div>
      </div>

      <!-- SPED Information Card -->
      <div class="card bg-base-100 shadow-md border-2 border-warning">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-4">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-warning rounded"></div>
                  <h2 class="text-xl font-semibold">Special Education Information</h2>
               </div>
               <a href="{{ route('students.sped-registration.step1') }}" class="btn btn-xs btn-ghost rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2. 828l8.586-8. 586z" />
                  </svg>
                  Edit
               </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Type of Disability</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['type_of_disability'] }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Date of Diagnosis</span>
                  </label>
                  <p class="pl-2">{{ \Carbon\Carbon::parse($registrationData['date_of_diagnosis'])->format('F d, Y') }}
                  </p>
               </div>

               <div class="form-control md:col-span-2">
                  <label class="label">
                     <span class="label-text font-semibold">Cause of Disability</span>
                  </label>
                  <p class="pl-2">{{ $registrationData['cause_of_disability'] ?? 'Not specified' }}</p>
               </div>
            </div>
         </div>
      </div>

      <!-- Guardian Information Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-4">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Guardian Information</h2>
               </div>
               <a href="{{ route('students.sped-registration.step2') }}" class="btn btn-xs btn-ghost rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2. 828l8.586-8. 586z" />
                  </svg>
                  Edit
               </a>
            </div>

            @if(isset($registrationData['guardians']) && is_array($registrationData['guardians']))
            @foreach($registrationData['guardians'] as $index => $guardian)
            <div class="{{ $index > 0 ? 'border-t pt-4 mt-4' : '' }}">
               <h4 class="font-semibold text-sm mb-3 text-blue-600">Guardian #{{ $index + 1 }}</h4>
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Full Name</span>
                     </label>
                     <p class="pl-2">{{ $guardian['first_name'] }} {{ $guardian['middle_name'] ?? '' }} {{
                        $guardian['last_name'] }}</p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Relationship</span>
                     </label>
                     <p class="pl-2">
                        <span class="badge badge badge-info badge-sm">{{ $guardian['relation'] }}</span>
                     </p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Contact Number</span>
                     </label>
                     <p class="pl-2">{{ $guardian['contact_number'] }}</p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Email</span>
                     </label>
                     <p class="pl-2">{{ $guardian['email'] ?? 'N/A' }}</p>
                  </div>

                  <div class="form-control md:col-span-2">
                     <label class="label">
                        <span class="label-text font-semibold">Address</span>
                     </label>
                     <p class="pl-2">{{ $guardian['address'] ?? 'N/A' }}</p>
                  </div>
               </div>
            </div>
            @endforeach
            @endif
         </div>
      </div>

      <!-- Documents Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-4">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Uploaded Documents</h2>
               </div>
               <a href="{{ route('students.sped-registration.step3') }}" class="btn btn-xs btn-ghost rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9. 414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit
               </a>
            </div>

            @if(isset($registrationData['documents']) && count($registrationData['documents']) > 0)
            <div class="space-y-2">
               @foreach($registrationData['documents'] as $type => $document)
               @if($type === 'additional')
               @foreach($document as $additional)
               <div class="flex justify-between items-center py-3 px-4 bg-base-200 rounded-lg">
                  <div class="flex items-center gap-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                     </svg>
                     <span class="font-medium">{{ $additional['type'] ?? 'Additional Document' }}</span>
                  </div>
                  <span class="badge badge-success badge-sm">✓ Uploaded</span>
               </div>
               @endforeach
               @else
               <div class="flex justify-between items-center py-3 px-4 bg-base-200 rounded-lg">
                  <div class="flex items-center gap-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01. 707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                     </svg>
                     <span class="font-medium">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                  </div>
                  <span class="badge badge-success badge-sm">✓ Uploaded</span>
               </div>
               @endif
               @endforeach
            </div>
            @else
            <div class="alert alert-warning">
               <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M12 9v2m0 4h.01m-6. 938 4h13.856c1.54 0 2. 502-1.667 1. 732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
               </svg>
               <span>No documents uploaded yet</span>
            </div>
            @endif
         </div>
      </div>
   </div>

   <!-- Action Buttons -->
   <div class="flex justify-end gap-3 mt-6">
      <a href="{{ route('students.sped-registration.step3') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
         Previous
      </a>
      <form action="{{ route('students.sped-registration.store-final') }}" method="POST">
         @csrf
         <button type="submit" class="btn btn-sm bg-blue-600 text-base-300 hover:bg-blue-700-focus rounded-lg px-6">
            Proceed to Enrollment
         </button>
      </form>
   </div>
</div>
@endsection