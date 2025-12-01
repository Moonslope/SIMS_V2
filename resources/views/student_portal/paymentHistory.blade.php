@extends('layout.portal')
@section('title', 'Payment History')
@section('content')

<div class="px-5 min-h-full">
   <div class="my-12">
      <h1 class="text-4xl font-bold text-[#0F00CD] mb-2 text-center">Payment History</h1>
   </div>

   <div class="card bg-base-100 shadow-lg">
      <div class="card-body">
         <!-- Academic Year Header -->
         <div class="bg-base-200 rounded-lg p-4 mb-2">
            <h2 class="text-lg font-bold text-center text-gray-900 mb-2"></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
               <div>
                  <span class="font-semibold">LRN:</span> {{ $student->learner_reference_number ?? 'N/A' }}
               </div>
               <div>
                  <span class="font-semibold">Student's Name:</span> {{ $student->last_name ?? '' }}, {{
                  $student->first_name ?? '' }}
               </div>
               <div class="text-right">
                  <span class="font-semibold">{{ now()->format('m/d/Y H:i:s') }}</span>
               </div>
            </div>
         </div>

         <div class="divider my-4"></div>

         <!-- Payments Table -->
         @if($payments && $payments->count() > 0)
         <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
               <thead>
                  <tr class="bg-base-300">
                     <th class="font-bold">Acknowledgment Receipt #</th>
                     <th class="font-bold">Description</th>
                     <th class="font-bold">Amount</th>
                     <th class="font-bold">Transaction Date</th>
                     <th class="font-bold">Payment for</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($payments as $payment)
                  <tr class="hover:bg-base-200">
                     <td class=" font-bold">{{ $payment->reference_number ?? 'N/A' }}</td>
                     <td>{{ $payment->description ?? 'Payment' }}</td>
                     <td class=" font-bold ">₱{{ number_format($payment->amount_paid, 2) }}</td>
                     <td class="">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                     </td>
                     <td>
                        SY: {{$payment->billing->enrollment->academicYear->year_name}}
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         @else
         <div class="text-center py-8">
            <div class="flex justify-center mb-4">
               <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
               </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Payment Records Found</h3>
            <p class="text-gray-500">Your payment history will appear here once payments are processed.</p>
         </div>
         @endif
      </div>
   </div>
</div>

@endsection