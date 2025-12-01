@extends('layout.layout')
@section('title', 'Edit Announcement')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a href="{{route('announcements.index')}}">Announcements</a></li>
         <li><a>Edit Announcement</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit Announcement</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-5">
      <form action="{{route('announcements.update', $announcement->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-2">
               <label for="title">Title <span class="text-error">*</span></label>
               <input name="title" type="text" placeholder="e.g., School Picnic Announcement"
                  class="rounded-lg input @error('title') input-error @enderror"
                  value="{{ old('title', $announcement->title) }}" />
               @error('title')
               <div class="text-error text-sm mt-1">{{ $message }}</div>
               @enderror
            </div>

            <div class="flex flex-col gap-2">
               <label for="body">Body <span class="text-error">*</span></label>
               <textarea name="body" class="textarea rounded-lg w-full h-32 @error('body') textarea-error @enderror"
                  placeholder="Write your announcement here...">{{ old('body', $announcement->body) }}</textarea>
               @error('body')
               <div class="text-error text-sm mt-1">{{ $message }}</div>
               @enderror
            </div>

            <div class="flex flex-col gap-2">
               <label for="announcement_date">Announcement Date <span class="text-error">*</span></label>
               <input id="announcement_date" name="announcement_date" type="date"
                  class="rounded-lg input @error('announcement_date') input-error @enderror"
                  value="{{ old('announcement_date', $announcement->announcement_date->format('Y-m-d')) }}" />
               @error('announcement_date')
               <div class="text-error text-sm mt-1">{{ $message }}</div>
               @enderror
            </div>

            <div class="flex justify-end">
               <div class="flex gap-3">
                  <a href="{{route('announcements.index')}}" class="btn btn-sm w-40 rounded-lg">Cancel</a>
                  <button type="submit" class="btn btn-primary btn-sm w-40 rounded-lg">Save Changes</button>
               </div>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection