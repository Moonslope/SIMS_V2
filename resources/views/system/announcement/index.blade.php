@extends('layout.layout')
@section('title', 'Announcements')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a>Announcements</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Announcements</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('announcements.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2. 5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4. 3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search announcements..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('announcements.index') }}" class="btn btn-xs btn-circle btn-ghost"
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

            <a href="{{route('announcements.create')}}"
               class="btn bg-[#271AD2] text-base-300 btn-sm rounded-lg hover:bg-primary w-full sm:w-auto gap-2">
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
               <th>Title</th>
               <th>Published By</th>
               <th>Announcement Date</th>
               <th>Created At</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($announcements as $announcement)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($announcements->currentPage() - 1) * $announcements->perPage() + $loop->iteration }}
               </th>
               <td>{{$announcement->title}}</td>
               <td>
                  <div>{{$announcement->publisher->first_name ?? 'N/A'}} {{$announcement->publisher->last_name ?? ''}}
                  </div>
               </td>
               <td>
                  <div class="flex flex-col">
                     <span class="font-medium">{{$announcement->announcement_date->format('M d, Y')}}</span>
                     <span class="text-xs text-gray-500">{{$announcement->announcement_date->format('g:i A')}}</span>
                  </div>
               </td>
               <td>
                  <div class="flex flex-col">
                     <span class="font-medium">{{$announcement->created_at->format('M d, Y')}}</span>
                     <span class="text-xs text-gray-500">{{$announcement->created_at->format('g:i A')}}</span>
                  </div>
               </td>
               <td class="w-38">
                  <div class="flex gap-2">
                     <a href="{{route('announcements.edit', $announcement->id)}}"
                        class="btn btn-soft text-[#271AD2] rounded-lg bg-primary-content btn-xs tooltip"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-lg pt-1"></i>
                     </a>

                     <button class="btn btn-soft text-[#271AD2] rounded-lg bg-primary-content btn-xs tooltip"
                        data-tip="Delete"
                        onclick="document.getElementById('delete_modal_{{ $announcement->id }}').showModal()">
                        <i class="fi fi-sr-trash text-lg pt-1"></i>
                     </button>
                  </div>
               </td>
            </tr>

            <dialog id="delete_modal_{{ $announcement->id }}" class="modal">
               <div class="modal-box">
                  <div class="flex justify-center items-center flex-col gap-4">
                     <i class="fi fi-sr-triangle-warning text-[60px] text-error"></i>
                     <h3 class=" text-lg text-center font-semibold">
                        Are you sure you want to permanently delete this announcement?
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

                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST">
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
                     <i class="fi fi-sr-megaphone text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No announcements found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No announcements available</p>
                        <p class="text-sm mt-1">Create a new announcement to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($announcements->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $announcements->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection