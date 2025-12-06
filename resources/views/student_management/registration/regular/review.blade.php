@extends('layout.layout')
@section('title', 'Student Registration - Review')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Enrollment</a></li>
         <li><a>New Student</a></li>
         <li><a>Regular</a></li>
         <li class="text-blue-600 font-semibold">Review</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - Regular</h1>
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

   <div class="rounded-lg bg-base-100 shadow-md p-8">
      <div class="flex items-center gap-3 mb-6">
         <div class="w-1 h-8 bg-blue-600 rounded"></div>
         <h2 class="text-2xl font-semibold">Review & Confirm Registration</h2>
      </div>

      <div class="space-y-6">
         <!-- Student Information Review -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-4 flex items-center gap-2">
                  Student Information
                  <a href="{{ route('students.registration.step1') }}" class="btn btn-xs btn-ghost ml-auto">
                     Edit
                  </a>
               </h3>
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">LRN:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['learner_reference_number'] ?? 'N/A' }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Full Name:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['first_name'] }} {{ $registrationData['middle_name'] ?? '' }}
                        {{ $registrationData['last_name'] }} {{ $registrationData['extension_name'] ?? '' }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Nickname:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['nickname'] ?? 'N/A' }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Gender:</span>
                     </label>
                     <p class="pl-2">{{ ucfirst($registrationData['gender']) }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Birthdate:</span>
                     </label>
                     <p class="pl-2">{{ \Carbon\Carbon::parse($registrationData['birthdate'])->format('F d, Y') }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Birthplace:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['birthplace'] ?? 'N/A' }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Nationality:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['nationality'] ?? 'N/A' }}</p>
                  </div>
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Religion:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['religion'] ?? 'N/A' }}</p>
                  </div>
                  <div class="form-control md:col-span-2">
                     <label class="label">
                        <span class="label-text font-semibold">Address:</span>
                     </label>
                     <p class="pl-2">{{ $registrationData['address'] ?? 'N/A' }}</p>
                  </div>
               </div>
            </div>
         </div>

         <!-- Guardian Information Review -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg flex items-center gap-2">
                  <h3 class="card-title text-lg mb-4 flex items-center gap-2">
                     Guardian Information
                     <a href="{{ route('students.registration.step2') }}" class="btn btn-xs btn-ghost ml-auto">
                        Edit
                     </a>
                  </h3>
                  @if(isset($registrationData['guardians']) && is_array($registrationData['guardians']))
                  @foreach($registrationData['guardians'] as $index => $guardian)
                  <div class="mb-6 {{ $index > 0 ? 'border-t pt-4' : '' }}">
                     <h4 class="font-semibold text-sm mb-3 text-blue-600">Guardian #{{ $index + 1 }}</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                           <label class="label">
                              <span class="label-text font-semibold">Full Name:</span>
                           </label>
                           <p class="pl-2">{{ $guardian['first_name'] }} {{ $guardian['middle_name'] ?? '' }} {{
                              $guardian['last_name'] }}</p>
                        </div>
                        <div class="form-control">
                           <label class="label">
                              <span class="label-text font-semibold">Relationship:</span>
                           </label>
                           <p class="pl-2">{{ $guardian['relation'] }}</p>
                        </div>
                        <div class="form-control">
                           <label class="label">
                              <span class="label-text font-semibold">Contact Number:</span>
                           </label>
                           <p class="pl-2">{{ $guardian['contact_number'] }}</p>
                        </div>
                        <div class="form-control">
                           <label class="label">
                              <span class="label-text font-semibold">Email:</span>
                           </label>
                           <p class="pl-2">{{ $guardian['email'] ?? 'N/A' }}</p>
                        </div>
                        <div class="form-control md:col-span-2">
                           <label class="label">
                              <span class="label-text font-semibold">Address:</span>
                           </label>
                           <p class="pl-2">{{ $guardian['address'] ?? 'N/A' }}</p>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
            </div>
         </div>

         <!-- Documents Review -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-4 flex items-center gap-2">
                  Submitted Documents
                  <a href="{{ route('students.registration.step3') }}" class="btn btn-xs btn-ghost ml-auto">
                     Edit
                  </a>
               </h3>
               <div class="space-y-2">
                  @if(isset($registrationData['documents']) && count($registrationData['documents']) > 0)
                  @foreach($registrationData['documents'] as $type => $document)
                  @if($type !== 'additional')
                  <div class="flex justify-between items-center p-3 bg-base-100 rounded-lg">
                     <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $type)) }}:</span>
                     <span class="badge badge-success badge-soft">Uploaded</span>
                  </div>
                  @endif
                  @endforeach

                  @if(isset($registrationData['documents']['additional']))
                  @foreach($registrationData['documents']['additional'] as $additional)
                  <div class="flex justify-between items-center p-3 bg-base-100 rounded-lg">
                     <span class="font-medium">{{ $additional['type'] }}:</span>
                     <span class="badge badge-success badge-soft">Uploaded</span>
                  </div>
                  @endforeach
                  @endif
                  @else
                  <div class="alert alert-warning ">
                     <i class="fi fi-ss-triangle-warning text-xl pt-1"></i>
                     <span>No documents uploaded</span>
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>

      <!-- Final Form -->
      <form action="{{ route('students.registration.store-final') }}" method="POST">
         @csrf

         <!-- Divider -->
         <div class="divider my-8"></div>

         <!-- Action Buttons -->
         <div class="flex items-center justify-between">
            <a href="{{ route('students.registration.step3') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
               Previous
            </a>
            <button type="submit" class="btn btn-sm bg-blue-600 text-base-300 hover:bg-blue-700-focus w-48 rounded-lg px-6">
               Proceed to Enrollment
            </button>
         </div>
      </form>
   </div>
</div>
@endsection