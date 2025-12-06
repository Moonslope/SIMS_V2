@extends('layout.layout')
@section('title', 'Archived Students')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{ route('students.index') }}">Student Management</a></li>
         <li><a>Archived Students</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Archived Students</h1>
      <a href="{{ route('students.index') }}" class="btn btn-sm btn-ghost text-base-300 me-3 rounded-lg">
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
         </svg>
         Back to Active Students
      </a>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('students.archived') }}" method="GET" class="w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80 rounded-lg">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search archived students..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('students.archived') }}" class="btn btn-xs btn-circle btn-ghost rounded-lg"
                     title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>
            </form>
         </div>
      </div>
   </div>

   <div class="overflow-x-auto rounded-lg">
      <table class="table">
         <thead class="bg-base-300">
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>LRN</th>
               <th>Sex</th>
               <th>Archived Date</th>
               <th class="text-center">Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($students as $student)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
               </th>
               <td>
                  <span>{{$student->first_name . ' ' . $student->middle_name . ' ' .
                     $student->last_name }}</span>
               </td>
               <td>
                  <span class="text-sm">{{$student->learner_reference_number}}</span>
               </td>
               <td>
                  <span class="badge badge-ghost badge-sm">{{ ucfirst($student->gender) }}</span>
               </td>
               <td>
                  <span class="text-sm">{{ $student->deleted_at->format('M d, Y h:i A') }}</span>
               </td>
               <td class="text-center">
                  <div class="flex gap-2 justify-center">
                     <form action="{{ route('students.restore', $student->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to restore this student?');">
                        @csrf
                        <button type="submit"
                           class="btn btn-soft px-1 text-blue-600 bg-blue-600-content btn-xs tooltip hover:bg-blue-700 hover:text-base-300 rounded-lg"
                           data-tip="Restore">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                           </svg>
                        </button>
                     </form>

                     <form action="{{ route('students.force-delete', $student->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this student? This action cannot be undone!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                           class="btn btn-soft px-1 text-blue-600 bg-blue-600-content btn-xs tooltip hover:bg-blue-700 hover:text-base-300 rounded-lg"
                           data-tip="Delete">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                           </svg>
                        </button>
                     </form>
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="6" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-archive text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No archived students found matching your search</p>
                        <p class="text-sm mt-1">
                           <a href="{{ route('students.archived') }}" class="link link-primary">Clear search</a>
                        </p>
                        @else
                        <p class="font-semibold">No archived students</p>
                        <p class="text-sm mt-1">Archived students will appear here</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($students->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $students->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection