@extends('layout.layout')
@section('title', 'Users')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a href="{{route('users.index')}}">Users</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Users</h1>
   </div>

   <!-- Improved Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('users.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered rounded-lg flex items-center gap-2 w-full sm:w-80">
                  <i class="fi fi-rr-search text-md pt-1"></i>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search by name, email, or role..." class="input w-full" />
                  @if(request('search'))
                  <a href="{{ route('users.index') }}" class="btn btn-xs btn-circle btn-ghost rounded-lg"
                     title="Clear search">
                     <i class="fi fi-rr-cross-circle text-md"></i>
                  </a>
                  @endif
               </label>
            </form>

            <a href="{{route('users.create')}}"
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
               <th>Name</th>
               <th>Email</th>
               <th>Role</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($users as $user)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
               </th>
               <td>
                  <div>
                     {{$user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name}}
                  </div>
               </td>
               <td>{{$user->email}}</td>
               <td>
                  <span class="badge badge-ghost badge-sm">{{$user->role}}</span>
               </td>
               <td class=" w-38">
                  <div class="flex gap-2">
                     <a href="{{route('users.edit', $user->id)}}"
                        class="btn btn-soft px-1 text-primary bg-primary-content btn-xs tooltip hover:bg-primary hover:text-base-300 rounded-lg"
                        data-tip="Edit Details">
                        <i class="fi fi-sr-pen-square text-[18px] pt-1"></i>
                     </a>

                     <button
                        class="btn btn-soft px-1 text-primary bg-primary-content btn-xs tooltip hover:bg-primary hover:text-white rounded-lg"
                        data-tip="Delete" onclick="document.getElementById('delete_modal_{{ $user->id }}').showModal()">
                        <i class="fi fi-sr-trash text-[18px] pt-1"></i>
                     </button>
                  </div>

               </td>
            </tr>

            <dialog id="delete_modal_{{ $user->id }}" class="modal">
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
                           <button class="btn btn-soft bg-base-300 w-50 btn-sm rounded-lg">Cancel</button>
                        </form>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="w-50 btn btn-error btn-sm rounded-lg">
                              Yes, Delete
                           </button>
                        </form>
                     </div>
                  </div>
               </div>
            </dialog>
            @empty
            <tr>
               <td colspan="5" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-user text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No users found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No users available</p>
                        <p class="text-sm mt-1">Create a new user to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($users->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $users->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection