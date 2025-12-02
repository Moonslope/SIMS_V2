@extends('layout.layout')
@section('title', 'All Students')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a>All Students</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">List of All Students</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('students.index') }}" method="GET" class="w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80 rounded-lg">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search by name, LRN..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('students.index') }}" class="btn btn-xs btn-circle btn-ghost rounded-lg"
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
               <th>Birthdate</th>

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
                  @if($student->birthdate)
                  <div class="flex flex-col">
                     <span class="font-medium">{{ \Carbon\Carbon::parse($student->birthdate)->format('M d, Y') }}</span>
                  </div>
                  @else
                  <span class="text-gray-400">N/A</span>
                  @endif
               </td>

               <td class="text-center">
                  <a href="{{ route('students.student-profile', $student->id) }}"
                     class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-base-300 rounded-lg"
                     data-tip="View Profile">
                     <i class="fi fi-sr-eye text-[18px] pt-1"></i>
                  </a>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="6" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-graduation-cap text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No students found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No students available</p>
                        <p class="text-sm mt-1">Start by registering new students</p>
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