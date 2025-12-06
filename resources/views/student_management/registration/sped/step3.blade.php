@extends('layout.layout')
@section('title', 'SPED Registration - Step 3: Documents')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Enrollment</a></li>
         <li><a>New Student</a></li>
         <li><a>SPED</a></li>
         <li class="text-blue-600 font-semibold">Documents</li>
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
         <li class="step">Review</li>
      </ul>
   </div>

   <form action="{{ route('students.sped-registration.store-step3') }}" method="POST" enctype="multipart/form-data"
      class="space-y-6">
      @csrf

      <!-- Required Documents Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Required Documents</h2>
            </div>

            <div class="space-y-6">
               <!-- Birth Certificate -->
               <div class="card bg-base-200">
                  <div class="card-body p-4">
                     <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.  org/2000/svg" class="h-6 w-6 text-blue-600 flex-shrink-0 mt-1"
                           fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="flex-1">
                           <h3 class="font-semibold text-base mb-2">Birth Certificate <span class="text-error">*</span></h3>
                           <input type="file" name="documents[birth_certificate]"
                              class="file-input file-input-bordered file-input-sm w-full rounded-lg @error('documents. birth_certificate') file-input-error @enderror"
                              accept=".pdf,.jpg,.jpeg,.png">
                           <p class="text-xs text-gray-500 mt-2">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                           @error('documents.birth_certificate')
                           <p class="text-error text-sm mt-1">{{ $message }}</p>
                           @enderror
                        </div>
                     </div>
                  </div>
               </div>


               <!-- Medical Certificate (SPED Specific) -->
               <div class="card bg-warning/10 border-2 border-warning">
                  <div class="card-body p-4">
                     <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3. org/2000/svg" class="h-6 w-6 text-warning flex-shrink-0 mt-1"
                           fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <div class="flex-1">
                           <div class="flex items-center gap-2 mb-2">
                              <h3 class="font-semibold text-base">Certificate of Diagnosis <span class="text-error">*</span></h3>
                              <span class="badge badge-warning badge-sm">Required for SPED</span>
                           </div>
                           <input type="file" name="documents[certificate_of_diagnosis]"
                              class="file-input file-input-bordered file-input-sm w-full rounded-lg @error('documents.certificate_of_diagnosis') file-input-error @enderror"
                              accept=".pdf,.jpg,.jpeg,.png">
                           <p class="text-xs text-gray-600 mt-2 font-medium">Official diagnosis from a licensed medical professional</p>
                           @error('documents.certificate_of_diagnosis')
                           <p class="text-error text-sm mt-1">{{ $message }}</p>
                           @enderror
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>


      <!-- Additional Documents Card -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-6">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Additional Documents</h2>
               </div>
               <span class="badge badge-ghost badge-sm">Optional</span>
            </div>

            <div id="additional-docs" class="space-y-3">
               <div class="flex gap-2">
                  <input type="text" name="documents[additional][0][type]" placeholder="Document name"
                     class="input input-bordered input-sm rounded-lg flex-1">
                  <input type="file" name="documents[additional][0][file]"
                     class="file-input file-input-bordered file-input-sm rounded-lg flex-1">
               </div>
            </div>

            <button type="button" onclick="addAdditionalDoc()" class="btn btn-sm btn-outline mt-4 rounded-lg">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
               </svg>
               Add Another Document
            </button>
         </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3">
         <a href="{{ route('students.sped-registration.step2') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
            Previous
         </a>
         <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white w-35 rounded-lg px-6">
            Review
         </button>
      </div>
   </form>
</div>

<script>
   let docCounter = 1;

   function addAdditionalDoc() {
      const container = document.getElementById('additional-docs');
      const newDoc = document.createElement('div');
      newDoc.className = 'flex gap-2';
      newDoc. innerHTML = `
         <input 
            type="text" 
            name="documents[additional][${docCounter}][type]" 
            placeholder="Document name" 
            class="input input-bordered input-sm rounded-lg flex-1">
         <input 
            type="file" 
            name="documents[additional][${docCounter}][file]" 
            class="file-input file-input-bordered file-input-sm rounded-lg flex-1">
         <button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-error btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
         </button>
      `;
      container.appendChild(newDoc);
      docCounter++;
   }
</script>
@endsection