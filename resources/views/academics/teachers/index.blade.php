@extends('layout.layout')
@section('title', 'Teachers')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academics</a></li>
         <li class="text-blue-600 font-semibold">Teachers</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Teachers</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('teachers.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search teachers..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('teachers.index') }}" class="btn btn-xs btn-circle btn-ghost" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>
            </form>

            <a href="{{route('teachers.create')}}"
               class="btn bg-blue-600 text-base-300 btn-sm rounded-lg hover:bg-blue-700-focus w-full sm:w-auto gap-2">
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
               <th>Name</th>
               <th>Contact Number</th>
               <th>Address</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($teachers as $teacher)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($teachers->currentPage() - 1) * $teachers->perPage() + $loop->iteration }}
               </th>
               <td>{{$teacher->first_name . ' ' . $teacher->middle_name . ' ' . $teacher->last_name}}</td>
               <td>{{$teacher->contact_number}}</td>
               <td>{{$teacher->address}}</td>
               <td>
                  <span
                     class="{{ $teacher->is_active ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                  </span>
               </td>
               <td class=" w-38">
                  <div class="flex gap-2">
                     <a href="{{route('teachers.edit', $teacher->id)}}"
                        class="btn btn-soft px-1 text-blue-600 bg-blue-600-content btn-xs tooltip hover:bg-blue-700 hover:text-base-300"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>

                     <button
                        class="btn btn-soft px-1 text-blue-600 bg-blue-600-content btn-xs tooltip hover:bg-blue-700 hover:text-white"
                        data-tip="Delete"
                        onclick="document.getElementById('delete_modal_{{ $teacher->id }}').showModal()">
                        <i class="fi fi-sr-trash text-[18px] pt-1"></i>
                     </button>
                  </div>

               </td>
            </tr>

            <dialog id="delete_modal_{{ $teacher->id }}" class="modal">
               <div class="modal-box">
                  <div class="flex justify-center items-center flex-col gap-4">
                     <i class="fi fi-sr-triangle-warning text-[60px] text-error"></i>
                     <h3 class=" text-lg text-center font-semibold">
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

                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST">
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
               <td colspan="6" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-chalkboard-user text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No teachers found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No teachers available</p>
                        <p class="text-sm mt-1">Create a new teacher to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($teachers->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $teachers->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection