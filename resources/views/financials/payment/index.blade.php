@extends('layout.layout')
@section('title', 'Payments')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Financials</a></li>
         <li class="text-blue-600 font-semibold">Payments</li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Payments</h1>
   </div>

   <!-- Search Section -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-4">
         <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('payments.index') }}" method="GET"
               class="flex flex-col sm:flex-row gap-2 items-center w-full sm:w-auto" id="searchForm">
               <label class="input input-sm input-bordered flex items-center gap-2 w-full sm:w-80 rounded-lg">
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
                     class="btn btn-xs btn-circle btn-ghost rounded-lg" title="Clear search">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M6 18L18 6M6 6l12 12" />
                     </svg>
                  </a>
                  @endif
               </label>

               <select name="academic_year" class="select select-sm select-bordered w-full sm:w-48 rounded-lg"
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

            <button onclick="payment_report_modal.showModal()"
               class="btn bg-blue-600 text-base-300 btn-sm w-full sm:w-auto gap-2 rounded-lg hover:bg-blue-700-focus">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
               </svg>
               <span>Generate Report</span>
            </button>

            <dialog id="payment_report_modal" class="modal">
               <div class="modal-box">
                  <h3 class="font-bold text-lg mb-4">Generate Payment Collection Report</h3>
                  <form method="GET" action="{{ route('reports.payments') }}" target="_blank">
                     <div class="flex flex-col gap-4">
                        <div class="form-control w-full">
                           <label class="label">
                              <span class="label-text font-semibold">Report Type</span>
                           </label>
                           <select name="report_type" class="select select-bordered w-full rounded-lg" id="reportType"
                              onchange="toggleDateFields()">
                              <option value="academic_year">By Academic Year</option>
                              <option value="date_range">By Date Range</option>
                           </select>
                        </div>

                        <div class="form-control w-full" id="academicYearField">
                           <label class="label">
                              <span class="label-text font-semibold">Select Academic Year</span>
                           </label>
                           <select name="academic_year" class="select select-bordered w-full rounded-lg">
                              <option value="">Choose Academic Year...</option>
                              <option value="all">All Academic Years</option>
                              @foreach($academicYears as $year)
                              <option value="{{ $year->id }}" {{ request('academic_year')==$year->id ? 'selected' : ''
                                 }}>
                                 {{ $year->year_name }}
                                 @if($year->is_active)
                                 <span class="badge badge-success badge-soft badge-sm">Active</span>
                                 @endif
                              </option>
                              @endforeach
                           </select>
                        </div>

                        <div id="dateRangeFields" style="display: none;">
                           <div class="form-control w-full">
                              <label class="label">
                                 <span class="label-text font-semibold">Start Date</span>
                              </label>
                              <input type="date" name="start_date" class="input input-bordered w-full">
                           </div>

                           <div class="form-control w-full mt-3">
                              <label class="label">
                                 <span class="label-text font-semibold">End Date</span>
                              </label>
                              <input type="date" name="end_date" class="input input-bordered w-full">
                           </div>
                        </div>
                     </div>

                     <div class="modal-action">
                        <button type="button" onclick="payment_report_modal.close()"
                           class="btn btn-sm btn-ghost rounded-lg">Cancel</button>
                        <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                           Generate Report
                        </button>
                     </div>
                  </form>
               </div>
               <form method="dialog" class="modal-backdrop">
                  <button>close</button>
               </form>
            </dialog>
         </div>
      </div>
   </div>

   <script src="{{ asset('js/payment-index.js') }}"></script>

   <div class="overflow-x-auto rounded-xl">
      <table class="table table-md ">
         <thead class="bg-base-300">
            <tr>
               <th></th>
               <th>Reference Number</th>
               <th>LRN</th>
               <th>Name</th>
               <th>Purpose</th>
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
               <td>{{$payment->reference_number}}</td>
               <td>
                  <span class="text-sm">{{$payment->billing->enrollment->student->learner_reference_number}}</span>
               </td>
               <td>{{$payment->billing->enrollment->Student->first_name . ' ' .
                  $payment->billing->enrollment->Student->middle_name . ' ' .
                  $payment->billing->enrollment->Student->last_name}}
               </td>
               <td>
                  <span class="text-sm">{{ $payment->billingItem ? $payment->billingItem->feeStructure->fee_name :
                     ($payment->purpose ?? 'N/A') }}</span>
               </td>
               <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
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
               <td colspan="8" class="text-center">
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