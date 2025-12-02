@extends('layout.layout')
@section('title', 'Sections')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academic</a></li>
         <li><a href="{{route('sections.index')}}">Sections</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Sections</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('sections.index') }}" method="GET"
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
                     placeholder="Search sections..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('sections.index', ['academic_year' => request('academic_year'), 'grade_level' => request('grade_level')]) }}"
                     class="btn btn-xs btn-circle btn-ghost" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="academic_year" class="select select-sm select-bordered w-full sm:w-48"
                  onchange="this.form.submit()">
                  <option value="all">All Academic Years</option>
                  @foreach($academicYears as $academicYear)
                  <option value="{{ $academicYear->id }}" {{ request('academic_year')==$academicYear->id ? 'selected' :
                     '' }}>
                     {{ $academicYear->year_name }}
                  </option>
                  @endforeach
               </select>

               <select name="grade_level" class="select select-sm select-bordered w-full sm:w-48"
                  onchange="this.form.submit()">
                  <option value="all">All Grade Levels</option>
                  @foreach($gradeLevels as $gradeLevel)
                  <option value="{{ $gradeLevel->id }}" {{ request('grade_level')==$gradeLevel->id ? 'selected' : '' }}>
                     {{ $gradeLevel->grade_name }}
                  </option>
                  @endforeach
               </select>
            </form>

            <a href="{{route('sections.create')}}"
               class="btn bg-primary text-base-300 btn-sm rounded-lg hover:bg-primary-focus w-full sm:w-auto gap-2">
               <i class="fi fi-sr-plus-small text-lg pt-1"></i>
               <span>Create New</span>
            </a>
         </div>
      </div>
   </div>

   <div class="overflow-x-auto rounded-lg">
      <table class="table">
         <!-- head -->
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>Section Name</th>
               <th>Academic Year</th>
               <th>Grade Level</th>
               <th>Capacity</th>
               <th>Teacher</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>

         <tbody>
            @forelse ($sections as $section)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($sections->currentPage() - 1) * $sections->perPage() + $loop->iteration }}
               </th>
               <td>{{$section->section_name}}</td>
               <td>{{$section->academicYear->year_name ?? 'N/A'}}</td>
               <td>{{$section->gradeLevel->grade_name}}</td>
               <td>{{$section->capacity}}</td>
               <td>{{$section->teacher->first_name . ' ' . $section->teacher->middle_name . ' ' .
                  $section->teacher->last_name }}</td>
               <td>
                  <span
                     class="{{ $section->is_active ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $section->is_active ? 'Active' : 'Inactive' }}
                  </span>
               </td>
               <td class="w-25">
                  <div class="flex gap-2">
                     <a href="{{route('sections.edit', $section->id)}}"
                        class="btn btn-soft px-1 text-primary bg-primary-content btn-xs tooltip hover:bg-primary hover:text-base-300"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="8" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-users-alt text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No sections found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No sections available</p>
                        <p class="text-sm mt-1">Create a new section to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($sections->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $sections->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>

<x-success-alert />
@endsection