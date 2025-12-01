@extends('layout.layout')
@section('title', 'Enrolled Students')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a>Enrolled Students</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg mb-5 flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">List of Enrolled Students</h1>
      <h1 class="text-[20px] font-semibold text-base-300 me-3 p-2">SY {{ $currentAcademicYear->year_name ?? 'N/A' }}
      </h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('enrollments.index') }}" method="GET"
               class="flex flex-col sm:flex-row gap-2 items-center w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search by name..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('enrollments.index', ['academic_year' => request('academic_year')]) }}"
                     class="btn btn-xs btn-circle btn-ghost" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="academic_year" class="select select-sm select-bordered w-full sm:w-35"
                  onchange="this.form.submit()">
                  <option value="all">All Academic Years</option>
                  @foreach($academicYears as $academicYear)
                  <option value="{{ $academicYear->id }}" {{ request('academic_year')==$academicYear->id ? 'selected' :
                     '' }}>
                     {{ $academicYear->year_name }}
                  </option>
                  @endforeach
               </select>

               <select name="grade_level" class="select select-sm select-bordered w-full sm:w-25"
                  onchange="this.form.submit()">
                  <option value="all">All Grade Levels</option>
                  @foreach($gradeLevels as $gradeLevel)
                  <option value="{{ $gradeLevel->id }}" {{ request('grade_level')==$gradeLevel->id ? 'selected' : '' }}>
                     {{ $gradeLevel->grade_name }}
                  </option>
                  @endforeach
               </select>

               <select name="program_type" class="select select-sm select-bordered w-full sm:w-25"
                  onchange="this.form.submit()">
                  <option value="all">All Program Types</option>
                  @foreach($programTypes as $programType)
                  <option value="{{ $programType->id }}" {{ request('program_type')==$programType->id ? 'selected' : ''
                     }}>
                     {{ $programType->program_name }}
                  </option>
                  @endforeach
               </select>
            </form>

            <!-- Find this button and replace it -->
            <!-- Replace the Generate Report button with this -->
            <button onclick="report_modal. showModal()"
               class="btn bg-[#0F00CD] text-base-300 btn-sm w-full sm:w-auto gap-2 rounded-lg hover:bg-[#0D00B0]">
               <svg xmlns="http://www.w3. org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
               </svg>
               <span>Generate Report</span>
            </button>


            <dialog id="report_modal" class="modal">
               <div class="modal-box">
                  <h3 class="font-bold text-lg mb-4">Generate Enrollment Report</h3>
                  <form method="GET" action="{{ route('reports.enrollments') }}" target="_blank">
                     <div class="form-control w-full">
                        <label class="label">
                           <span class="label-text font-semibold">Select Academic Year</span>
                        </label>
                        <select name="academic_year" class="select select-bordered w-full" required>
                           <option value="">Choose Academic Year... </option>
                           <option value="all">All Academic Years</option>
                           @foreach($academicYears as $year)
                           <option value="{{ $year->id }}" {{ request('academic_year')==$year->id ? 'selected' : '' }}>
                              {{ $year->year_name }}
                              @if($year->is_active)
                              <span class="badge badge-success badge-sm">Active</span>
                              @endif
                           </option>
                           @endforeach
                        </select>
                     </div>

                     <!-- Optional: Include current filters -->
                     @if(request('grade_level') && request('grade_level') != 'all')
                     <input type="hidden" name="grade_level" value="{{ request('grade_level') }}">
                     @endif

                     @if(request('program_type') && request('program_type') != 'all')
                     <input type="hidden" name="program_type" value="{{ request('program_type') }}">
                     @endif

                     @if(request('section') && request('section') != 'all')
                     <input type="hidden" name="section" value="{{ request('section') }}">
                     @endif

                     @if(request('search'))
                     <input type="hidden" name="search" value="{{ request('search') }}">
                     @endif

                     <div class="modal-action">
                        <button type="button" onclick="report_modal.close()"
                           class="btn btn-sm btn-ghost">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">
                           Generate Report
                        </button>
                     </div>
                  </form>
               </div>
               <form method="dialog" class="modal-backdrop">
                  <button>close</button>
               </form>
            </dialog>
         </div>
      </div>
   </div>

   <div class="overflow-x-hidden rounded-lg">
      <table class="table">
         <thead class="bg-base-300">
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>Grade Level</th>
               <th>Program Type</th>
               <th>Section</th>
               <th>Academic Year</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($enrollments as $enrollment)
            <tr class="hover:bg-base-300">
               <th>{{ ($enrollments->currentPage() - 1) * $enrollments->perPage() + $loop->iteration }}</th>
               <td class="font-semibold">{{ $enrollment->student->first_name . ' ' . $enrollment->student->middle_name .
                  ' ' .
                  $enrollment->student->last_name }}</td>
               <td>{{ $enrollment->gradeLevel->grade_name ?? 'N/A' }}</td>
               <td>{{ $enrollment->programType->program_name }}</td>
               <td>{{ $enrollment->section->section_name ?? 'N/A' }}</td>
               <td>{{ $enrollment->academicYear->year_name }}</td>
               <td>
                  <span
                     class="{{ $enrollment->enrollment_status ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $enrollment->enrollment_status ? 'Active' : 'Inactive' }}
                  </span>
               </td>
               <td>
                  <div class="flex gap-2">
                     <a href="{{ route('enrollments.show', $enrollment->id) }}"
                        class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-base-300"
                        data-tip="View Details">
                        <i class="fi fi-sr-eye text-[18px] pt-1"></i>
                     </a>
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="8" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-folder-open text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search') || request('academic_year'))
                        <p class="font-semibold">No enrollments found matching your filters</p>
                        <p class="text-sm mt-1">
                           <a href="{{ route('enrollments.index') }}" class="link link-primary">Clear filters</a>
                        </p>
                        @else
                        <p class="font-semibold">No enrollments available</p>
                        <p class="text-sm mt-1">Enrollments will appear here once students are enrolled</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($enrollments->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $enrollments->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
@endsection