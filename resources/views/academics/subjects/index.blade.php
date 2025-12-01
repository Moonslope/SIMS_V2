@extends('layout.layout')
@section('title', 'Subjects')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Academic</a></li>
         <li><a href="{{route('subjects.index')}}">Subjects</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Subjects</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('subjects.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 items-center w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search subjects..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('subjects.index', ['grade_level' => request('grade_level')]) }}" class="btn btn-xs btn-circle btn-ghost" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="grade_level" class="select select-sm select-bordered w-full sm:w-48" onchange="this.form.submit()">
                  <option value="all">All Grade Levels</option>
                  @foreach($gradeLevels as $gradeLevel)
                  <option value="{{ $gradeLevel->id }}" {{ request('grade_level')==$gradeLevel->id ? 'selected' : '' }}>
                     {{ $gradeLevel->grade_name }}
                  </option>
                  @endforeach
               </select>
            </form>

            <a href="{{route('subjects.create')}}"
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
               <th>Subject Name</th>
               <th>Grade Level</th>
               <th>Description</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>

         <tbody>
            @forelse ($subjects as $subject)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($subjects->currentPage() - 1) * $subjects->perPage() + $loop->iteration }}
               </th>
               <td>{{$subject->subject_name}}</td>
               <td>{{$subject->gradeLevel->grade_name}}</td>
               <td>{{$subject->description}}</td>
               <td>
                  <span
                     class="{{ $subject->is_active ? 'badge badge-soft badge-success badge-sm' : 'badge badge-soft badge-neutral badge-sm' }}">
                     {{ $subject->is_active ? 'Active' : 'Inactive' }}
                  </span>
               </td>

               <td class="w-38">
                  <div class="flex gap-2">
                     <a href="{{route('subjects.edit', $subject->id)}}"
                        class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-base-300"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>

                     <button
                        class="btn btn-soft px-1 text-[#0F00CD] bg-primary-content btn-xs tooltip hover:bg-[#0F00CD] hover:text-white"
                        data-tip="Delete"
                        onclick="document.getElementById('delete_modal_{{ $subject->id }}').showModal()">
                        <i class="fi fi-sr-trash text-[18px] pt-1"></i>
                     </button>
                  </div>

               </td>
            </tr>

            <dialog id="delete_modal_{{ $subject->id }}" class="modal">
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

                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST">
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
                     <i class="fi fi-sr-book-alt text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No subjects found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No subjects available</p>
                        <p class="text-sm mt-1">Create a new subject to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($subjects->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $subjects->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
@endsection