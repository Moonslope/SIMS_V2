@extends('layout.layout')
@section('title', 'Activity Logs')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a>Activity Logs</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Activity Logs</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('activity-logs.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search activity logs..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('activity-logs.index') }}" class="btn btn-xs btn-circle btn-ghost"
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
         <!-- head -->
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>User</th>
               <th>Action Description</th>
               <th>Date & Time</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($activityLogs as $log)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($activityLogs->currentPage() - 1) * $activityLogs->perPage() + $loop->iteration }}
               </th>
               <td>
                  @if($log->user)
                  <div class="font-semibold">
                     {{ trim($log->user->first_name . ' ' . ($log->user->middle_name ? $log->user->middle_name . ' ' : '') . $log->user->last_name) }}
                  </div>
                  @else
                  <div class="font-semibold text-gray-400">System</div>
                  @endif
               </td>
               <td>
                  <div class="max-w-md">
                     {{ $log->action_description }}
                  </div>
               </td>
               <td>
                  <div class="flex flex-col">
                     <span class="font-medium">{{ $log->log_date->format('M d, Y') }}</span>
                     <span class="text-xs text-gray-500">{{ $log->log_date->format('g:i A') }}</span>
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="4" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-time-past text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No activity logs found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No activity logs available</p>
                        <p class="text-sm mt-1">Activity logs will appear here once actions are performed</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($activityLogs->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $activityLogs->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
@endsection