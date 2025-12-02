@extends('layout.layout')
@section('title', 'Student Registration - Step 3: Documents')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Registration</a></li>
         <li><a>Enrollment Form</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Registration - Regular</h1>
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

   <div class="rounded-lg bg-base-100 shadow-md p-8">
      <div class="flex items-center gap-3 mb-6">
         <div class="w-1 h-8 bg-primary rounded"></div>
         <h2 class="text-2xl font-semibold">Required Documents</h2>
      </div>

      <form action="{{ route('students.registration.store-step3') }}" method="POST" enctype="multipart/form-data"
         class="space-y-6">
         @csrf

         <!-- Birth Certificate -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-3">
                  <svg xmlns="http://www.w3. org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Birth Certificate <span class="text-error">*</span>
               </h3>
               <div class="form-control w-full">
                  <input type="file" name="documents[birth_certificate]" id="birth_certificate"
                     class="file-input file-input-bordered w-full @error('documents. birth_certificate') file-input-error @enderror"
                     accept=".pdf,.jpg,.jpeg,.png">
                  <label class="label">
                     <span class="label-text-alt text-gray-500">PDF, JPG, PNG - Max: 5MB</span>
                  </label>
                  @error('documents.birth_certificate')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>
         </div>

         <!-- Report Card -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                  </svg>
                  Report Card (Previous School)
               </h3>
               <div class="form-control w-full">
                  <input type="file" name="documents[report_card]" id="report_card"
                     class="file-input file-input-bordered w-full @error('documents.report_card') file-input-error @enderror"
                     accept=".pdf,.jpg,.jpeg,.png">
                  <label class="label">
                     <span class="label-text-alt text-gray-500">PDF, JPG, PNG - Max: 5MB (For transferees)</span>
                  </label>
                  @error('documents.report_card')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>
         </div>

         <!-- ID Photo -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  2x2 ID Photo
               </h3>
               <div class="form-control w-full">
                  <input type="file" name="documents[id_photo_2x2]" id="id_photo_2x2"
                     class="file-input file-input-bordered w-full @error('documents.id_photo_2x2') file-input-error @enderror"
                     accept=".jpg,. jpeg,. png">
                  <label class="label">
                     <span class="label-text-alt text-gray-500">JPG, PNG only - Recent photo with white
                        background</span>
                  </label>
                  @error('documents.id_photo_2x2')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>
            </div>
         </div>

         <!-- Additional Documents -->
         <div class="card bg-base-200 shadow-sm">
            <div class="card-body p-6">
               <h3 class="card-title text-lg mb-3">
                  <svg xmlns="http://www.w3. org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Additional Documents (Optional)
               </h3>
               <div id="additional-docs" class="space-y-3">
                  <!-- Dynamic docs will be added here -->
               </div>
               <button type="button" onclick="addAdditionalDoc()" class="btn btn-sm btn-outline btn-primary mt-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Add Another Document
               </button>
            </div>
         </div>

         <!-- Divider -->
         <div class="divider"></div>

         <!-- Action Buttons -->
         <div class="flex items-center justify-between">
            <a href="{{ route('students.registration.step2') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
               Previous
            </a>
            <button type="submit" class="btn btn-sm btn-primary w-35 rounded-lg px-6">
               Review
            </button>
         </div>
      </form>
   </div>
</div>

<script>
   let docCounter = 1;

   function addAdditionalDoc() {
       const container = document.getElementById('additional-docs');
       const newDoc = document.createElement('div');
       newDoc.className = 'flex gap-3 items-start';
       newDoc.innerHTML = `
           <input type="text" name="documents[additional][${docCounter}][type]" placeholder="Document Type (e.g., Medical Certificate)" 
                  class="input input-bordered flex-1 rounded-lg">
           <input type="file" name="documents[additional][${docCounter}][file]" 
                  class="file-input file-input-bordered flex-1 rounded-lg">
           <button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-error btn-square">
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