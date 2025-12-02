@extends('layout.portal')
@section('title', 'My Profile')
@section('content')

<div class="p-5">
   <!-- Page Header -->
   <div class="my-8">
      <h1 class="text-4xl font-bold text-primary mb-2 text-center">My Profile</h1>
   </div>

   <!-- Profile Header Card -->
   <div class="card bg-base-100 shadow-md mb-6 rounded-lg">
      <div class="card-body">
         <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
               <div class="avatar placeholder">
                  <div class="bg-primary text-white flex justify-center items-center rounded-full w-20 h-20">
                     <span class="text-3xl font-bold">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{
                        strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                     </span>
                  </div>
               </div>
               <div class="text-center sm:text-left">
                  <h2 class="text-2xl font-bold text-primary">
                     {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}
                  </h2>
                  <p class="text-gray-600">Student</p>
                  <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
               </div>
            </div>
            <div class="w-full sm:w-auto">
               <a href="{{ route('students.change-password') }}"
                  class="btn bg-primary text-white btn-sm sm:btn-sm w-full sm:w-auto rounded-lg">
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
            <div class="w-1 h-8 bg-primary rounded"></div>
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
            <div class="w-1 h-8 bg-primary rounded"></div>
            <h2 class="text-xl font-semibold">Guardian Information</h2>
         </div>

         @foreach($guardians as $guardian)
         <div class="mb-6 last:mb-0">
            <h3 class="text-lg font-semibold text-primary mb-3">
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

   <!-- Document Upload Card -->
   <div class="card bg-base-100 shadow-md mb-6">
      <div class="card-body">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-primary rounded"></div>
            <h2 class="text-xl font-semibold">Upload Documents</h2>
         </div>

         @if(session('success'))
         <div role="alert" class="alert alert-success mb-4">
            <i class="fi fi-sr-check-circle"></i>
            <span>{{ session('success') }}</span>
         </div>
         @endif

         @if(session('error'))
         <div role="alert" class="alert alert-error mb-4">
            <i class="fi fi-sr-cross-circle"></i>
            <span>{{ session('error') }}</span>
         </div>
         @endif

         <form action="{{ route('student.documents.upload') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <div class="flex flex-col lg:flex-row gap-5">
               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-semibold">Document Type *</span>
                  </label>
                  <select name="document_type" id="document_type" required
                     class="select select-bordered w-full rounded-lg">
                     <option value="">Select Document Type</option>
                     <option value="Birth Certificate">Birth Certificate</option>
                     <option value="Report Card">Report Card</option>
                     <option value="Transfer Credential">Transfer Credential</option>
                     <option value="Medical Certificate">Medical Certificate</option>
                     <option value="ID Photo">ID Photo (2x2)</option>
                     <option value="Other">Other</option>
                  </select>
                  @error('document_type')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control w-full">
                  <label class="label">
                     <span class="label-text font-semibold">Select File *</span>
                  </label>
                  <input type="file" name="document_file" id="document_file" required accept=".pdf,.jpg,.jpeg,. png"
                     class="file-input file-input-bordered w-full rounded-lg" />
                  <label class="label">
                     <span class="label-text-alt text-xs md:text-sm">Allowed formats: PDF, JPG, PNG (Max: 5MB)</span>
                  </label>
                  @error('document_file')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

            </div>

            <div class="flex justify-end">
               <button type="submit" class="btn btn-sm bg-primary text-white rounded-lg">
                  <i class="fi fi-sr-upload"></i>
                  Upload Document
               </button>
            </div>

         </form>
      </div>
   </div>

   <!-- Uploaded Documents Card -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-primary rounded"></div>
            <h2 class="text-xl font-semibold">My Documents</h2>
         </div>

         @if($documents && $documents->count() > 0)
         <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
               <thead>
                  <tr>
                     <th>Document Type</th>
                     <th>File Name</th>
                     <th class="hidden sm:table-cell">Upload Date</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($documents as $document)
                  <tr>
                     <td>
                        <div class="flex items-center gap-2">
                           <i class="fi fi-sr-document text-primary"></i>
                           <span class="font-medium text-sm">{{ $document->document_type }}</span>
                        </div>
                     </td>
                     <td class="text-sm text-gray-600 max-w-[150px] truncate">
                        {{ $document->document_name ?? basename($document->file_path) }}
                     </td>
                     <td class="text-sm text-gray-600 hidden sm:table-cell">
                        {{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}
                     </td>
                     <td>
                        <div class="flex gap-2">
                           <a href="{{ route('student.documents.download', $document->id) }}"
                              class="btn btn-sm btn-ghost text-primary rounded-lg" title="Download">
                              <i class="fi fi-sr-download"></i>
                           </a>
                           <form action="{{ route('student.documents.delete', $document->id) }}" method="POST"
                              class="inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit"
                                 onclick="return confirm('Are you sure you want to delete this document?')"
                                 class="btn btn-sm btn-ghost text-error rounded-lg" title="Delete">
                                 <i class="fi fi-sr-trash"></i>
                              </button>
                           </form>
                        </div>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         @else
         <div class="text-center py-12">
            <i class="fi fi-sr-folder-open text-[80px] text-gray-300"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2 mt-4">No Documents Yet</h3>
            <p class="text-gray-500">Upload your documents to keep them organized. </p>
         </div>
         @endif
      </div>
   </div>
</div>

@endsection