@extends('layout.portal')
@section('title', 'Announcements')
@section('content')

<div class="px-5">
   <div class="my-12">
      <h1 class="text-4xl font-bold text-blue-600 mb-2 text-center">Announcements</h1>
      <p class="text-gray-600 text-center">Stay updated with the latest school announcements</p>
   </div>

   <div class="flex flex-col gap-4">
      @forelse ($announcements as $announcement)
      <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
         <div class="card-body">
            <!-- Header -->
            <div class="flex justify-between items-start gap-4">
               <div class="flex-1">
                  <h2 class="card-title text-blue-600 mb-2">{{ $announcement->title }}</h2>
                  <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 text-sm text-gray-600">
                     <div class="flex items-center gap-1">
                        <i class="fi fi-sr-calendar text-blue-600"></i>
                        <span>{{ $announcement->announcement_date->format('M d, Y') }}</span>
                     </div>
                     <div class="flex items-center gap-1">
                        <i class="fi fi-sr-user text-blue-600"></i>
                        <span>{{ $announcement->publisher->first_name ?? 'Admin' }} {{
                           $announcement->publisher->last_name ?? '' }}</span>
                     </div>
                     <div class="flex items-center gap-1">
                        <i class="fi fi-sr-clock text-blue-600"></i>
                        <span>{{ $announcement->created_at->diffForHumans() }}</span>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Body -->
            <p class="text-gray-700 mt-4 leading-relaxed">
               {{ Str::limit($announcement->body, 200) }}
            </p>

            <!-- View More Link -->
            <div class="card-actions justify-end mt-4">
               <button class="btn bg-blue-600 btn-sm text-white"
                  onclick="document.getElementById('announcement_modal_{{ $announcement->id }}').showModal()">
                  View Full Announcement
               </button>
            </div>
         </div>
      </div>

      <!-- Modal for Full Announcement -->
      <dialog id="announcement_modal_{{ $announcement->id }}" class="modal">
         <div class="modal-box w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
               <h3 class="font-bold text-lg text-blue-600">{{ $announcement->title }}</h3>
               <form method="dialog">
                  <button class="btn btn-sm btn-circle btn-ghost">✕</button>
               </form>
            </div>

            <!-- Announcement Details -->
            <div class="divider"></div>

            <div class="flex flex-col gap-3 mb-4 text-sm text-gray-600">
               <div class="flex justify-between">
                  <span><strong>Date:</strong></span>
                  <span>{{ $announcement->announcement_date->format('M d, Y') }}</span>
               </div>
               <div class="flex justify-between">
                  <span><strong>Published By:</strong></span>
                  <span>{{ $announcement->publisher->first_name ?? 'Admin' }} {{ $announcement->publisher->last_name ??
                     '' }}</span>
               </div>
               <div class="flex justify-between">
                  <span><strong>Posted On:</strong></span>
                  <span>{{ $announcement->created_at->format('M d, Y \a\t g:i A') }} ({{
                     $announcement->created_at->diffForHumans() }})</span>
               </div>
            </div>

            <div class="divider"></div>

            <!-- Full Body -->
            <div class="mb-6">
               <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $announcement->body }}</p>
            </div>

            <!-- Action -->
            <div class="modal-action">
               <form method="dialog">
                  <button class="btn">Close</button>
               </form>
            </div>
         </div>
         <form method="dialog" class="modal-backdrop">
            <button>close</button>
         </form>
      </dialog>
      @empty
      <!-- Empty State -->
      <div class="card bg-base-100 shadow">
         <div class="card-body text-center py-12">
            <i class="fi fi-sr-bell-ring text-[60px] text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Announcements Yet</h3>
            <p class="text-gray-500">There are no announcements at the moment. Check back soon!</p>
         </div>
      </div>
      @endforelse

      <!-- Pagination -->
      @if($announcements->hasPages())
      <div class="mt-6 flex justify-center mb-6">
         {{ $announcements->links() }}
      </div>
      @endif
   </div>
</div>

@endsection