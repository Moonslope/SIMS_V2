@extends('layout.layout')
@section('title', 'Enrollment System Dashboard')
@section('content')
<div class="p-6 space-y-6">
   <div class="flex justify-between items-center">
      <div class="space-y-3">
         <h1 class="text-3xl font-bold text-gray-900 mb-3">Dashboard Overview</h1>
         {{-- <p class="text-sm text-gray-500">Welcome back! Here's your dashboard overview</p> --}}
         @if($currentYear)
         <p class="text-sm text-gray-500 mt-1">Current Academic Year: <span class="font-semibold">{{
               $currentYear->year_name }}</span></p>
         @endif
      </div>
      <div class="text-sm text-gray-500">
         {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
      </div>
   </div>

   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Total Students -->
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#271AD2]">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm font-medium text-gray-600">Total Students</p>
               <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStudents) }}</p>
               @if($previousTotalStudents > 0)
               <div class="flex items-center mt-2">
                  @if($studentChange > 0)
                  <i class="fi fi-sr-arrow-trend-up text-green-600 text-sm mr-1"></i>
                  <span class="text-xs text-green-600 font-semibold">+{{ number_format($studentChange) }} ({{
                     $changePercentage }}%)</span>
                  @elseif($studentChange < 0) <i class="fi fi-sr-arrow-trend-down text-red-600 text-sm mr-1"></i>
                     <span class="text-xs text-red-600 font-semibold">{{ number_format($studentChange) }} ({{
                        $changePercentage }}%)</span>
                     @else
                     <span class="text-xs text-gray-600">No change</span>
                     @endif
                     <span class="text-xs text-gray-500 ml-1">from last year</span>
               </div>
               @endif
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
               <i class="fi fi-sr-users-alt text-blue-600 text-xl"></i>
            </div>
         </div>
      </div>

      <!-- Regular Students -->
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#271AD2]">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm font-medium text-gray-600">Regular Students</p>
               <p class="text-2xl font-bold text-gray-900">{{ number_format($regularStudents) }}</p>
               @if($totalStudents > 0)
               <p class="text-xs text-gray-500 mt-2">{{ round(($regularStudents / $totalStudents) * 100, 1) }}% of total
               </p>
               @endif
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
               <i class="fi fi-sr-users text-blue-600 text-xl"></i>
            </div>
         </div>
      </div>

      <!-- SPED Students -->
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#271AD2]">
         <div class="flex items-center justify-between">
            <div>
               <p class="text-sm font-medium text-gray-600">SPED Students</p>
               <p class="text-2xl font-bold text-gray-900">{{ number_format($spedStudents) }}</p>
               @if($totalStudents > 0)
               <p class="text-xs text-gray-500 mt-2">{{ round(($spedStudents / $totalStudents) * 100, 1) }}% of total
               </p>
               @endif
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
               <i class="fi fi-ss-user-gear text-blue-600 text-xl"></i>
            </div>
         </div>
      </div>
   </div>

   <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow-md p-6 col-span-2">
         <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
         <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <div>
               <a href="{{ route('students.registration.step1') }}"
                  class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                  <div class="p-2 bg-blue-100 rounded-lg mr-4">
                     <i class="fi fi-ss-user-add text-blue-600"></i>
                  </div>
                  <div>
                     <p class="font-medium text-gray-900">Register Regular Student</p>
                     <p class="text-sm text-gray-600">Start new enrollment process</p>
                  </div>
               </a>
            </div>

            <div>
               <a href="{{ route('students.sped-registration.step1') }}"
                  class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                  <div class="p-2 bg-blue-100 rounded-lg mr-4">
                     <i class="fi fi-ss-user-gear text-blue-600"></i>
                  </div>
                  <div>
                     <p class="font-medium text-gray-900">Register SPED Student</p>
                     <p class="text-sm text-gray-600">Special education enrollment</p>
                  </div>
               </a>
            </div>

            <div>
               <a href="{{ route('students.index') }}"
                  class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                  <div class="p-2 bg-blue-100 rounded-lg mr-4">
                     <i class="fi fi-ss-users text-blue-600"></i>
                  </div>
                  <div>
                     <p class="font-medium text-gray-900">View All Students</p>
                     <p class="text-sm text-gray-600">Manage student records</p>
                  </div>
               </a>
            </div>
         </div>
      </div>

      <!-- Grade Level Distribution -->
      <div class="bg-white rounded-lg shadow-md p-6 col-span-3">
         <h3 class="text-lg font-semibold text-gray-900 mb-6">Grade Level Distribution</h3>
         @if($currentYear)
         <p class="text-sm text-gray-600 mb-4">Student count per grade level for {{ $currentYear->year_name }}</p>
         @endif
         <div class="space-y-4">
            @if(!$currentYear)
            <div class="text-center py-8 text-gray-500">
               <i class="fi fi-sr-exclamation text-4xl mb-2"></i>
               <p>No active academic year found</p>
               <p class="text-xs mt-2">Please create an academic year to view enrollment data</p>
            </div>
            @else
            @php
            $maxStudents = is_array($gradeLevelDistribution) || $gradeLevelDistribution->isEmpty() ? 1 :
            ($gradeLevelDistribution->max('student_count') ?: 1);
            $colors = [
            'bg-[#0F00CD]', 'bg-blue-600', 'bg-blue-500', 'bg-blue-700',
            'bg-[#1E13A0]', 'bg-blue-800', 'bg-blue-400', 'bg-[#3B2FE8]',
            'bg-blue-900', 'bg-[#4D3FF5]', 'bg-blue-300', 'bg-[#5A4CF7]'
            ];
            @endphp
            @forelse($gradeLevelDistribution as $index => $grade)
            <div class="space-y-1">
               <div class="flex items-center justify-between">
                  <span class="text-sm font-medium text-gray-700">{{ $grade->grade_name }}</span>
                  <span class="text-sm font-medium text-gray-900">{{ $grade->student_count }} student{{
                     $grade->student_count !== 1 ? 's' : '' }}</span>
               </div>
               <div class="flex items-center space-x-3">
                  <div class="flex-1 bg-gray-200 rounded-full h-3">
                     <div class="{{ $colors[$index % count($colors)] }} h-3 rounded-full transition-all duration-500"
                        style="width: {{ $maxStudents > 0 ? ($grade->student_count / $maxStudents) * 100 : 0 }}%">
                     </div>
                  </div>
               </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
               <i class="fi fi-sr-chart-histogram text-4xl mb-2"></i>
               <p>No enrollment data available</p>
            </div>
            @endforelse
            @endif
         </div>
      </div>
   </div>

   <!-- Recent Activity -->
   <div class="bg-white rounded-lg shadow-md p-6">
      <div class="flex justify-between items-center mb-6">
         <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
         <a href="{{ route('activity-logs.index') }}" class="text-sm text-[#0F00CD] hover:text-[#0A0090]">View All</a>
      </div>
      <div class="overflow-x-auto">
         <table class="table table-xs">
            <thead>
               <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">User</th>
                  <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Action</th>
                  <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Date</th>
               </tr>
            </thead>
            <tbody>
               @forelse($recentActivities as $activity)
               <tr class="border-b border-gray-100 hover:bg-gray-50">
                  <td class="py-3 px-2 text-sm text-gray-900">
                     @if($activity->user)
                     {{ trim($activity->user->first_name . ' ' . ($activity->user->middle_name ?
                     $activity->user->middle_name . ' ' : '') . $activity->user->last_name) }}
                     @else
                     <span class="text-gray-400">System</span>
                     @endif
                  </td>
                  <td class="py-3 px-2 text-sm text-gray-700">
                     {{ $activity->action_description }}
                  </td>
                  <td class="py-3 px-2 text-sm text-gray-500">
                     {{ $activity->log_date->format('M d, Y g:i A') }}
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="3" class="text-center py-8 text-gray-500">
                     <i class="fi fi-sr-time-past text-4xl mb-2"></i>
                     <p>No recent activity</p>
                  </td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection