@extends('layout.layout')
@section('title', 'Student Profile')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{ route('students.index') }}">Student Management</a></li>
         <li><a>Students</a></li>
         <li class="text-blue-600 font-semibold">Profile</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Profile</h1>
   </div>

   <!-- Student Header Card -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-6">
         <div class="flex items-center gap-4">
            <div class="avatar placeholder">
               <div class="bg-blue-600 flex justify-center items-center text-blue-600-content rounded-full w-20">
                  <span class="text-3xl text-white font-semibold">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1)
                     }}</span>
               </div>
            </div>
            <div class="flex-1">
               <h2 class="text-2xl font-bold">
                  {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }} {{
                  $student->extension_name }}
               </h2>
               <p class="text-sm text-gray-500">LRN: {{ $student->learner_reference_number }}</p>
               <div class="flex gap-2 mt-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#F0F4FF] text-blue-600 border border-blue-100">{{ ucfirst($student->gender) }}</span>
                  @if($student->birthdate)
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#F0F4FF] text-blue-600 border border-blue-100">
                     Age: {{ \Carbon\Carbon::parse($student->birthdate)->age }} years old
                  </span>
                  @endif
               </div>
            </div>
            {{-- Edit Button: Admin Only --}}
            @if(auth()->user()->canEditStudents())
            <div class="flex gap-2">
               <a href="{{ route('students.edit', $student->id) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit Profile
               </a>
            </div>
            @endif
         </div>
      </div>
   </div>

   <div class="grid grid-cols-3 gap-5">
      <div class="card bg-base-100 shadow-md col-span-2 rounded-lg">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-4">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Personal Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Full Name</span>
                  </label>
                  <p class="pl-2">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }} {{
                     $student->extension_name }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Learner Reference Number</span>
                  </label>
                  <p class="pl-2">{{ $student->learner_reference_number }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Gender</span>
                  </label>
                  <p class="pl-2">{{ ucfirst($student->gender) }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Birthdate</span>
                  </label>
                  <p class="pl-2">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('F d,
                     Y') : 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Birthplace</span>
                  </label>
                  <p class="pl-2">{{ $student->birthplace ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Nationality</span>
                  </label>
                  <p class="pl-2">{{ $student->nationality ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Religion</span>
                  </label>
                  <p class="pl-2">{{ $student->religion ?? 'N/A' }}</p>
               </div>

               <div class="form-control md:col-span-2">
                  <label class="label">
                     <span class="label-text font-semibold">Address</span>
                  </label>
                  <p class="pl-2">{{ $student->address ?? 'N/A' }}</p>
               </div>
            </div>
         </div>
      </div>

      <!-- Current Enrollment -->
      @if($latestEnrollment)
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-4">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Current Enrollment</h2>
            </div>

            <div class="space-y-4">
               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Academic Year</span>
                  </label>
                  <p class="pl-2">{{ $latestEnrollment->academicYear->year_name }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Grade Level</span>
                  </label>
                  <p class="pl-2">{{ $latestEnrollment->gradeLevel->grade_name }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Section</span>
                  </label>
                  <p class="pl-2">{{ $latestEnrollment->section->section_name ?? 'N/A' }}</p>
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Status</span>
                  </label>
                  <p class="pl-2">
                     <span class="badge badge-success badge-soft badge-sm">{{
                        ucfirst($latestEnrollment->enrollment_status)
                        }}</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
      @endif
   </div>

   <div class="grid grid-cols-1 gap-5">
      <!-- Guardian Information -->
      @if($student->guardians->count() > 0)
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-4">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Guardian Information</h2>
            </div>

            @foreach($student->guardians as $guardian)
            <div class="p-4 bg-base-200 rounded-lg {{ ! $loop->last ? 'mb-4' : '' }}">
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Name</span>
                     </label>
                     <p class="pl-2">{{ $guardian->first_name }} {{ $guardian->middle_name }} {{ $guardian->last_name
                        }}</p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Relation</span>
                     </label>
                     <p class="pl-2">
                        <span class="">{{ $guardian->relation }}</span>
                     </p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Contact Number</span>
                     </label>
                     <p class="pl-2">{{ $guardian->contact_number }}</p>
                  </div>

                  <div class="form-control">
                     <label class="label">
                        <span class="label-text font-semibold">Email</span>
                     </label>
                     <p class="pl-2">{{ $guardian->email ?? 'N/A' }}</p>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
      </div>
      @endif

      <!-- Documents Section -->
      <div class="card bg-base-100 shadow-md rounded-lg">
         <div class="card-body p-6">
            <div class="flex items-center justify-between mb-4">
               <div class="flex items-center gap-3">
                  <div class="w-1 h-8 bg-blue-600 rounded"></div>
                  <h2 class="text-xl font-semibold">Documents</h2>
               </div>
               <div class="flex items-center gap-2">
                  <button type="button" onclick="document.getElementById('upload_modal').showModal()" 
                     class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm rounded-lg">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                     </svg>
                     Upload Document
                  </button>
               </div>
            </div>

            @if($documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
               @foreach($documents as $document)
               <div class="card bg-base-200 shadow-sm rounded-lg">
                  <div class="card-body p-4">
                     <div class="flex items-start gap-3">
                        @php
                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $iconClass = 'fi-sr-file';
                        $iconColor = 'text-gray-500';

                        if (in_array($extension, ['pdf'])) {
                        $iconClass = 'fi-sr-file-pdf';
                        $iconColor = 'text-red-500';
                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $iconClass = 'fi-sr-file-image';
                        $iconColor = 'text-blue-500';
                        } elseif (in_array($extension, ['doc', 'docx'])) {
                        $iconClass = 'fi-sr-file-word';
                        $iconColor = 'text-blue-700';
                        }
                        @endphp
                        <i class="fi {{ $iconClass }} text-3xl {{ $iconColor }}"></i>

                        <div class="flex-1 min-w-0">
                           <p class="font-semibold text-sm truncate">{{ $document->document_name }}</p>
                           <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ',
                              $document->document_type) }}</p>
                           <p class="text-xs text-gray-400 mt-1">
                              {{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}
                           </p>
                        </div>
                     </div>

                     <div class="flex gap-2 mt-3">
                        <a href="{{ route('students.view-document', [$student->id, $document->id]) }}" target="_blank"
                           class="btn btn-xs bg-blue-600 hover:bg-blue-700 text-white flex-1 rounded-lg">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                           </svg>
                           View
                        </a>
                        <a href="{{ route('students.view-document', [$student->id, $document->id]) }}"
                           download="{{ $document->document_name }}" class="btn btn-xs btn-outline flex-1 rounded-lg">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                           </svg>
                           Download
                        </a>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            @else
            <div class="alert alert-info">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  class="stroke-current shrink-0 w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>
               <div>
                  <h3 class="font-bold">No Documents</h3>
                  <div class="text-xs">No documents have been uploaded for this student yet.</div>
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>

<!-- Upload Document Modal -->
<dialog id="upload_modal" class="modal">
   <div class="modal-box">
      <form method="dialog">
         <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg mb-4">Upload Document</h3>
      
      <form id="uploadDocumentForm" enctype="multipart/form-data">
         @csrf
         <div class="space-y-4">
            <!-- Document Type -->
            <div class="form-control w-full">
               <label class="label">
                  <span class="label-text font-semibold">Document Type <span class="text-error">*</span></span>
               </label>
               <select name="document_type" id="document_type" required class="select select-bordered w-full rounded-lg">
                  <option value="">Select Document Type</option>
                  <option value="Birth Certificate">Birth Certificate</option>
                  <option value="Report Card">Report Card</option>
                  <option value="Transfer Credential">Transfer Credential</option>
                  <option value="Medical Certificate">Medical Certificate</option>
                  <option value="ID Photo">ID Photo (2x2)</option>
                  <option value="Good Moral">Good Moral Certificate</option>
                  <option value="Other">Other</option>
               </select>
               <span class="text-error text-sm hidden" id="document_type_error"></span>
            </div>

            <!-- File Upload -->
            <div class="form-control w-full">
               <label class="label">
                  <span class="label-text font-semibold">Select File <span class="text-error">*</span></span>
               </label>
               <input type="file" name="document_file" id="document_file" required accept=".pdf,.jpg,.jpeg,.png"
                  class="file-input file-input-bordered w-full rounded-lg" />
               <label class="label">
                  <span class="label-text-alt text-sm">Allowed formats: PDF, JPG, PNG (Max: 5MB)</span>
               </label>
               <span class="text-error text-sm hidden" id="document_file_error"></span>
            </div>

            <!-- Progress Bar -->
            <div id="upload_progress" class="hidden">
               <progress class="progress progress-primary w-full" value="0" max="100" id="progress_bar"></progress>
               <p class="text-sm text-center mt-2" id="progress_text">Uploading...</p>
            </div>

            <!-- Alert Messages -->
            <div id="upload_alert" class="alert hidden">
               <span id="upload_message"></span>
            </div>

            <!-- Actions -->
            <div class="modal-action">
               <button type="button" onclick="document.getElementById('upload_modal').close()" class="btn btn-ghost rounded-lg">
                  Cancel
               </button>
               <button type="submit" id="upload_btn" class="btn bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  Upload
               </button>
            </div>
         </div>
      </form>
   </div>
</dialog>

<!-- Hidden inputs for JavaScript -->
<input type="hidden" id="upload_url" value="{{ route('students.upload-document', $student->id) }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{ asset('js/student-profile.js') }}"></script>
@endsection