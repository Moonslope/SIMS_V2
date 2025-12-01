@extends('layout.layout')
@section('title', 'Payments')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Financial</a></li>
         <li><a>Payments</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Payments</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('payments.index') }}" method="GET"
               class="flex flex-col sm:flex-row gap-2 items-center w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80">
                  <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                     <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                     </g>
                  </svg>
                  <input type="search" name="search" id="searchInput" value="{{ request('search') }}"
                     placeholder="Search payments..." class="grow focus:outline-none" />
                  @if(request('search'))
                  <a href="{{ route('payments.index', ['academic_year' => request('academic_year')]) }}"
                     class="btn btn-xs btn-circle btn-ghost" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="academic_year" class="select select-sm select-bordered w-full sm:w-48"
                  onchange="this.form.submit()">
                  <option value="all">All Academic Years</option>
                  @foreach($academicYears as $academicYear)
                  <option value="{{ $academicYear->id }}" {{ request('academic_year')==$academicYear->id ? 'selected' :
                     '' }}>
                     {{ $academicYear->year_name }}
                  </option>
                  @endforeach
               </select>
            </form>
         </div>
      </div>
   </div>

   <div class="overflow-x-auto rounded-xl">
      <table class="table table-md ">
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>Reference Number</th>
               <th>LRN</th>
               <th>Name</th>
               <th>Amount Paid</th>
               <th>Payment Date</th>
               <th>Academic Year</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($payments as $payment)
            <tr class="hover:bg-base-300">
               <th>
                  {{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
               </th>
               <td class="font-semibold">{{$payment->reference_number}}</td>
               <td>
                  <span class="text-sm">{{$payment->billing->enrollment->student->learner_reference_number}}</span>
               </td>
               <td>{{$payment->billing->enrollment->Student->first_name . ' ' .
                  $payment->billing->enrollment->Student->middle_name . ' ' .
                  $payment->billing->enrollment->Student->last_name}}
               </td>
               <td class="font-semibold">₱{{ number_format($payment->amount_paid, 2) }}</td>
               <td>
                  <div class="flex flex-col">
                     <span class="font-medium">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')
                        }}</span>
                     <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('g:i
                        A') }}</span>
                  </div>
               </td>
               <td>{{$payment->academicYear->year_name}}</td>
            </tr>
            @empty
            <tr>
               <td colspan="7" class="text-center">
                  <div class="flex flex-col items-center gap-2 py-8">
                     <i class="fi fi-sr-credit-card text-4xl text-gray-400"></i>
                     <div class="text-gray-500">
                        @if(request('search'))
                        <p class="font-semibold">No payments found matching "{{ request('search') }}"</p>
                        <p class="text-sm mt-1">Try adjusting your search terms</p>
                        @else
                        <p class="font-semibold">No payments available</p>
                        <p class="text-sm mt-1">Payments will appear here once recorded</p>
                        @endif
                     </div>
                  </div>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>

      @if($payments->hasPages())
      <div class="flex justify-center items-center gap-3 p-4">
         <div>
            {{ $payments->appends(['search' => request('search')])->links('vendor.pagination') }}
         </div>
      </div>
      @endif
   </div>
</div>
<x-success-alert />
@endsection