@extends('layout.layout')
@section('title', 'Academic Years')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a>Academic Years</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Academic Years</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('academic-years.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <i class="fi fi-rr-search text-md pt-1"></i>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search academic year..." class="input" />
                  @if(request('search'))
                  <a href="{{ route('academic-years.index') }}" class="btn btn-xs btn-circle btn-ghost"
                     title="Clear search">
                  </a>
                  @endif
               </label>
            </form>

            <a href="{{route('academic-years.create')}}"
               class="btn bg-[#0F00CD] text-base-300 btn-sm rounded-lg hover:bg-[#0D00B0] w-full sm:w-auto gap-2">
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
               <th>Year Name</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th>Status</th>
               <th>Description</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($academicYears as $academicYear)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($academicYears->currentPage() - 1) * $academicYears->perPage() + $loop->iteration }}
               </th>
               <td>{{$academicYear->year_name}}</td>
               <td>{{$academicYear->start_date->format('M d, Y')}}</td>
               <td>{{$academicYear->end_date->format('M d, Y')}}</td>
               <td>
                  <span
                     class="{{ $academicYear->is_active ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $academicYear->is_active ? 'Active' : 'Inactive' }}
                  </span>
               </td>
               <td>{{ $academicYear->description}}</td>
               <td class="w-38">
                  <div class="flex gap-2">
                     <a href="{{route('academic-years.edit', $academicYear->id)}}"
                        class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-base-300"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>

                     <button
                        class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-white"
                        data-tip="Delete"
                        onclick="document.getElementById('delete_modal_{{ $academicYear->id }}').showModal()">
                        <i class="fi fi-sr-trash text-[18px] pt-1"></i>
                     </button>
                  </div>
               </td>
            </tr>

            <dialog id="delete_modal_{{ $academicYear->id }}" class="modal">
               <div class="modal-box">
                  <div class="flex justify-center items-center flex-col gap-4">
                     <i class="fi fi-sr-triangle-warning text-[60px] text-error"></i>
                     <h3 class="text-lg text-center font-semibold">
                        Are you sure you want to permanently delete this record?
                     </h3>
                     <p class="py-3 font-normal text-center pt-3 text-gray-600 text-sm">
                        This action cannot be undone. The record will be permanently removed from the system.
                     </p>
                  </div>

                  <hr class="text-gray-300">

                  <div class="modal-action flex justify-center">
                     <div class="flex gap-2">
                        <form method="dialog">
                           <button class="btn btn-soft bg-base-300 w-50 btn-sm">Cancel</button>
                        </form>

                        <form action="{{ route('academic-years.destroy', $academicYear->id) }}" method="POST">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="w-50 btn btn-error btn-sm">
                              Yes, Delete
                           </button>
                        </form>
                     </div>
                  </div>
               </div>
            </dialog>
            @empty
            <tr>
               <td colspan="7" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-calendar text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No academic years found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No academic years available</p>
                        <p class="text-sm mt-1">Create a new academic year to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($academicYears->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $academicYears->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>

@endsection