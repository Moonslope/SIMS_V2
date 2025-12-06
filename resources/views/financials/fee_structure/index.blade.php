@extends('layout.layout')
@section('title', 'Fee Structure')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Financials</a></li>
         <li class="text-blue-600 font-semibold">Fee Structure</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Fee Structure</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('fee-structures.index') }}" method="GET"
               class="flex flex-col sm:flex-row gap-2 items-center w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80 rounded-lg">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search fee structures..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('fee-structures.index', ['grade_level' => request('grade_level'), 'program_type' => request('program_type')]) }}"
                     class="btn btn-xs btn-circle btn-ghost rounded-lg" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="program_type" class="select select-sm select-bordered w-full sm:w-48 rounded-lg"
                  onchange="this.form.submit()">
                  <option value="all">All Program Types</option>
                  @foreach($programTypes as $programType)
                  <option value="{{ $programType->id }}" {{ request('program_type')==$programType->id ? 'selected' : ''
                     }}>
                     {{ $programType->program_name }}
                  </option>
                  @endforeach
               </select>

               <select name="grade_level" class="select select-sm select-bordered w-full sm:w-48 rounded-lg"
                  onchange="this.form.submit()">
                  <option value="all">All Grade Levels</option>
                  @foreach($gradeLevels as $gradeLevel)
                  <option value="{{ $gradeLevel->id }}" {{ request('grade_level')==$gradeLevel->id ? 'selected' : '' }}>
                     {{ $gradeLevel->grade_name }}
                  </option>
                  @endforeach
               </select>
            </form>

            <a href="{{route('fee-structures.create')}}"
               class="btn bg-blue-600 text-base-300 btn-sm rounded-lg hover:bg-blue-700-focus w-full sm:w-auto gap-2">
               <i class="fi fi-sr-plus-small text-lg pt-1"></i>
               <span>Create New</span>
            </a>
         </div>
      </div>
   </div>

   <div class="overflow-x-auto rounded-xl">
      <table class="table table-md ">
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>Fee Name</th>
               <th>Amount</th>
               <th>Program Type</th>
               <th>Grade Level</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($feeStructures as $feeStructure)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($feeStructures->currentPage() - 1) * $feeStructures->perPage() + $loop->iteration }}
               </th>
               <td>{{$feeStructure->fee_name}}</td>
               <td>₱{{ number_format($feeStructure->amount, 2) }}</td>
               <td>{{$feeStructure->programType->program_name}}</td>
               <td>{{ $feeStructure->gradeLevel->grade_name ?? 'N/A' }}</td>
               <td>
                  <span
                     class="{{ $feeStructure->is_active ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                  </span>
               </td>

               <td>
                  <div class="flex gap-2">
                     <a href="{{ route('fee-structures.edit', $feeStructure->id) }}"
                        class="btn btn-soft px-1 text-blue-600 bg-blue-600-content btn-xs tooltip hover:bg-blue-700 hover:text-base-300 rounded-lg"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="7" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-money text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No fee structures found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No fee structures available</p>
                        <p class="text-sm mt-1">Create a new fee structure to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($feeStructures->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $feeStructures->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection