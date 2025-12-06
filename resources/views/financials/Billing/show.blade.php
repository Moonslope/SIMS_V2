@extends('layout.layout')
@section('title', 'Billing Details')
@section('content')
@php
$totalPaid = $billing->payments->sum('amount_paid');
$remainingBalance = $billing->total_amount - $totalPaid;
@endphp

<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Financial</a></li>
         <li><a href="{{route('billings.index')}}">Billing</a></li>
         <li><a>Bill Details</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-blue-600 shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Bill Details</h1>
   </div>

   <!-- Student Information Card -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="avatar placeholder">
               <div class="bg-blue-600 flex justify-center items-center text-blue-600-content rounded-full w-12">
                  <span class="text-xl">
                     {{ substr($billing->enrollment->student->first_name, 0, 1) }}{{
                     substr($billing->enrollment->student->last_name, 0, 1) }}
                  </span>
               </div>
            </div>
            <div>
               <h3 class="font-semibold text-lg">
                  {{ $billing->enrollment->student->last_name }},
                  {{ $billing->enrollment->student->first_name }}
                  {{ $billing->enrollment->student->middle_name }}
               </h3>
               <p class="text-sm text-gray-500">LRN: {{ $billing->enrollment->student->learner_reference_number }}</p>
            </div>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Academic Year</span>
               </label>
               <p class="pl-2">{{ $billing->enrollment->academicYear->year_name }}</p>
            </div>

            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Program</span>
               </label>
               <p class="pl-2">{{ $billing->enrollment->programType->program_name }}</p>
            </div>

            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Grade Level</span>
               </label>
               <p class="pl-2">{{ $billing->enrollment->gradeLevel->grade_name }}</p>
            </div>
         </div>
      </div>
   </div>

   <!-- Payment History Card -->
   @if($billing->payments->count() > 0)
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
               <div class="w-1 h-8 bg-blue-600 rounded"></div>
               <h2 class="text-xl font-semibold">Payment History</h2>
            </div>
            <span class="badge badge badge-info badge-lg">
               {{ $billing->payments->count() }} {{ $billing->payments->count() === 1 ? 'Payment' : 'Payments' }}
            </span>
         </div>

         <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
               <thead class="bg-base-200">
                  <tr>
                     <th class="text-left">#</th>
                     <th class="text-left">Payment Date</th>
                     <th class="text-left">Reference No.</th>
                     <th class="text-right">Amount Paid</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($billing->payments as $index => $payment)
                  <tr>
                     <td class="font-medium">{{ $index + 1 }}</td>
                     <td>
                        <div class="flex flex-col">
                           <span class="font-medium">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')
                              }}</span>
                           <span class="text-xs text-gray-500">{{
                              \Carbon\Carbon::parse($payment->payment_date)->format('h:i A') }}</span>
                        </div>
                     </td>
                     <td>
                        <span class="text-sm">{{ $payment->reference_number }}</span>
                     </td>
                     <td class="text-right">
                        <span class="font-bold">₱{{ number_format($payment->amount_paid, 2) }}</span>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   @else
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               class="stroke-current shrink-0 w-6 h-6">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h. 01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
               <h3 class="font-bold">No Payment History</h3>
               <div class="text-xs">No payments have been made for this billing yet.</div>
            </div>
         </div>
      </div>
   </div>
   @endif

   <!-- Back Button -->
   <div class="flex justify-end">
      <a href="{{route('billings.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">
         Back to Billings
      </a>
   </div>
</div>
@endsection