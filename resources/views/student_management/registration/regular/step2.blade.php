@extends('layout.layout')
@section('title', 'Student Registration - Step 2: Guardian Information')
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
         <li class="step step-primary">Guardian Information</li>
         <li class="step">Documents</li>
         <li class="step">Review</li>
      </ul>
   </div>

   <div class="rounded-lg bg-base-100 shadow-md p-8">
      <div class="flex items-center justify-between mb-6">
         <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
            <h2 class="text-2xl font-semibold">Guardian/Parent Information</h2>
         </div>
         <div class="flex gap-2">
            <button type="button" id="addGuardianBtn" class="btn btn-sm btn-primary rounded-lg">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
               </svg>
               Add Guardian
            </button>
            <button type="button" id="clearAllGuardiansBtn" class="btn btn-sm btn-ghost rounded-lg">
               Clear All
            </button>
         </div>
      </div>

      <form action="{{ route('students.registration.store-step2') }}" method="POST" class="space-y-6">
         @csrf

         <!-- Guardians Container -->
         <div id="guardiansContainer" class="space-y-4">
            <!-- Guardian cards will be inserted here by JavaScript -->
         </div>

         <!-- Divider -->
         <div class="divider"></div>

         <!-- Action Buttons -->
         <div class="flex items-center justify-between">
            <a href="{{ route('students.registration.step1') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
               Previous
            </a>
            <button type="submit" class="btn btn-sm btn-primary w-40 rounded-lg px-6">
               Next
            </button>
         </div>
      </form>
   </div>
</div>

<script src="{{ asset('js/guardian-create.js') }}"></script>
@endsection