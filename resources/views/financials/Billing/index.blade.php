@extends('layout.layout')
@section('title', 'Billing')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Financial</a></li>
         <li><a href="{{route('billings.index')}}">Billing</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Billing</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('billings.index') }}" method="GET" id="searchForm" class="w-full sm:w-auto">
               <label class="input input-sm input-bordered rounded-lg flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search billings..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('billings.index') }}" class="btn btn-xs btn-circle btn-ghost rounded-lg"
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
   <div class="overflow-x-auto rounded-xl">
      <table class="table table-md ">
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>LRN</th>
               <th>Name</th>
               <th>Program</th>
               <th>Grade Level</th>
               <th>Total Amount</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($billings as $billing)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($billings->currentPage() - 1) * $billings->perPage() + $loop->iteration }}
               </th>
               <td>
                  <span class="text-sm">{{$billing->enrollment->student->learner_reference_number}}</span>
               </td>
               <td class="font-semibold">
                  {{$billing->enrollment->student->first_name . ' ' . $billing->enrollment->student->middle_name . ' ' .
                  $billing->enrollment->student->last_name}}
               </td>
               <td>{{$billing->enrollment->programType->program_name}}</td>
               <td>{{$billing->enrollment->gradeLevel->grade_name}}</td>
               <td>₱{{ number_format($billing->total_amount, 2) }}</td>
               <td>
                  @if($billing->status === 'paid')
                  <span class="badge badge-soft badge-success badge-sm">Paid</span>
                  @elseif($billing->status === 'partial')
                  <span class="badge badge-soft badge-warning badge-sm">Partial</span>
                  @else
                  <span class="badge badge-soft badge-neutral badge-sm">Pending</span>
                  @endif
               </td>

               <td class="w-35">
                  <div class="flex gap-2">
                     <a href="{{route('billings.show', $billing->id)}}"
                        class="btn rounded-lg btn-soft text-[#271AD2]  bg-primary-content btn-xs tooltip"
                        data-tip="View Payment History">
                        <i class="fi fi-sr-eye text-lg pt-1"></i>
                     </a>

                     @if($billing->status !== 'paid')
                     <a href="{{route('billings.edit', $billing->id)}}"
                        class="btn rounded-lg btn-soft text-[#271AD2]  bg-primary-content btn-xs tooltip"
                        data-tip="Make Payment">
                        <i class="fi fi-sr-expense text-lg pt-1"></i>
                     </a>
                     @endif
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="8" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-receipt text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No billings found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No billings available</p>
                        <p class="text-sm mt-1">Create a new billing to get started</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($billings->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $billings->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection