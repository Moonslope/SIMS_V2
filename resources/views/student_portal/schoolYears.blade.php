@extends('layout.portal')
@section('title', 'Select School Year')
@section('content')

<div class="p-5">
   <!-- Page Header -->
   <div class="my-8">
      <h1 class="text-4xl font-bold text-blue-600 mb-2 text-center">Select School Year</h1>
      <p class="text-gray-600 text-center">Choose a school year to view your enrollment details</p>
   </div>

   <!-- Info Alert -->
   <div role="alert" class="alert bg-[#d1ecf1] rounded-lg mb-6">
      <i class="fi fi-sr-info text-blue-600"></i>
      <span class="text-sm">Select a school year to view your class schedule and billing information for that
         period.</span>
   </div>

   <!-- School Years Grid -->
   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      @forelse ($academicYears as $year)
      <a href="{{ route('students.switchSchoolYear', $year->id) }}"
         class="card bg-base-100 shadow-md rounded-lg hover:shadow-xl transition-all duration-200 cursor-pointer hover:scale-105 group border-2 border-transparent hover:border-blue-600">
         <div class="card-body text-center">

            <!-- Year Name -->
            <h2 class="card-title text-blue-600 justify-center text-2xl font-bold mb-2">
               {{ $year->year_name }}
            </h2>

            <!-- Date Range -->
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-1">

               <span>{{ $year->start_date->format('M d, Y') }} - {{ $year->end_date->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-4">

               <span></span>
            </div>

            <!-- View Button -->
            <div class="card-actions justify-center">
               <button class="btn btn-sm bg-blue-600 text-white border-none group-hover:bg-opacity-90 rounded-lg">
                  View Details
               </button>
            </div>
         </div>
      </a>
      @empty
      <!-- Empty State -->
      <div class="col-span-full card bg-base-100 shadow rounded-lg">
         <div class="card-body text-center py-12">
            <i class="fi fi-sr-calendar-exclamation text-[80px] text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Enrollments Found</h3>
            <p class="text-gray-500">You don't have any enrollment records yet.</p>
            <div class="card-actions justify-center mt-4">
               <a href="{{ route('students.dashboard') }}" class="btn btn-sm bg-blue-600 text-white rounded-lg">
                  <i class="fi fi-sr-home pt-1"></i>
                  Go to Dashboard
               </a>
            </div>
         </div>
      </div>
      @endforelse
   </div>

   <!-- Back to Dashboard -->
   <div class="text-center">
      <a href="{{ route('students.dashboard') }}" class="btn btn-ghost btn-sm text-blue-600 rounded-lg">
         <i class="fi fi-sr-arrow-left pt-1"></i>
         Back to Dashboard
      </a>
   </div>
</div>

@endsection