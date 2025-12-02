@extends('layout.layout')
@section('title', 'Billing Details')
@section('content')
@php
$totalPaid = $billing->billingItems->sum('amount_paid');
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

   <div class="rounded-lg bg-primary shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Bill Details</h1>
   </div>

   <!-- Student Information Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="avatar placeholder">
               <div class="bg-primary flex justify-center items-center text-primary-content rounded-full w-12">
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

   <!-- Billing Items Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-primary rounded"></div>
            <h2 class="text-xl font-semibold">Billing Items</h2>
         </div>

         @if($billing->billingItems->count() > 0)
         <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
               <thead class="bg-base-200">
                  <tr>
                     <th class="text-left">Fee Description</th>
                     <th class="text-right">Amount</th>
                     <th class="text-right">Paid</th>
                     <th class="text-right">Balance</th>
                     <th class="text-center">Status</th>
                     <th class="text-center">Payment Date</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($billing->billingItems as $item)
                  <tr>
                     <td class="font-medium">{{ $item->feeStructure->fee_name }}</td>
                     <td class="text-right">₱{{ number_format($item->amount, 2) }}</td>
                     <td class="text-right">₱{{ number_format($item->amount_paid, 2) }}</td>
                     <td class="text-right">₱{{ number_format($item->amount - $item->amount_paid, 2) }}</td>
                     <td class="text-center">
                        @if($item->status === 'paid')
                        <span class="badge badge-success badge-soft badge-sm">Paid</span>
                        @elseif($item->status === 'partial')
                        <span class="badge badge-warning badge-soft badge-sm">Partial</span>
                        @else
                        <span class="badge badge-error badge-soft badge-sm">Unpaid</span>
                        @endif
                     </td>
                     <td class="text-center text-sm">
                        {{ $item->payment_date ? $item->payment_date->format('M d, Y') : '-' }}
                     </td>
                  </tr>
                  @endforeach
               </tbody>
               <tfoot class="bg-base-200">
                  <tr class="font-bold">
                     <td>Total Amount Due</td>
                     <td class="text-right text-lg">₱{{ number_format($billing->total_amount, 2) }}</td>
                     <td class="text-right">₱{{ number_format($billing->billingItems->sum('amount_paid'), 2) }}</td>
                     <td class="text-right">₱{{ number_format($billing->total_amount -
                        $billing->billingItems->sum('amount_paid'), 2) }}</td>
                     <td colspan="2"></td>
                  </tr>
               </tfoot>
            </table>
         </div>
         @else
         <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               class="stroke-current shrink-0 w-6 h-6">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>No billing items found. </span>
         </div>
         @endif
      </div>
   </div>

   <!-- Payment Section Grid -->
   <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Payment Summary -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-4">
               <div class="w-1 h-8 bg-primary rounded"></div>
               <h2 class="text-xl font-semibold">Payment Summary</h2>
            </div>

            <div class="space-y-3">
               <!-- Total Amount Due -->
               <div class="flex justify-between items-center p-3 bg-base-200 rounded-lg">
                  <span class="font-medium">Total Amount</span>
                  <span class="font-bold text-lg">₱{{ number_format($billing->total_amount, 2) }}</span>
               </div>

               <!-- Total Paid -->
               @if($totalPaid > 0)
               <div class="flex justify-between items-center p-3 bg-base-200 rounded-lg">
                  <span class="font-medium">Total Paid</span>
                  <span class="font-bold">₱{{ number_format($totalPaid, 2) }}</span>
               </div>
               @endif

               <!-- Remaining Balance -->
               <div class="flex justify-between items-center p-3  bg-base-200 rounded-lg">
                  <span class="font-semibold">
                     Remaining Balance
                  </span>
                  <span class="font-bold text-lg {{ $remainingBalance > 0 ? '' : '' }}">
                     ₱{{ number_format($remainingBalance, 2) }}
                  </span>
               </div>

               <!-- Payment Progress -->
               @if($totalPaid > 0)
               <div class="space-y-2 mt-4">
                  <div class="flex justify-between text-xs text-gray-500">
                     <span>{{ number_format(($totalPaid / $billing->total_amount) * 100, 1) }}% Paid</span>
                     <span>{{ number_format(($remainingBalance / $billing->total_amount) * 100, 1) }}% Remaining</span>
                  </div>
                  <progress
                     class="progress {{ $remainingBalance <= 0 ? 'progress-success' : (($totalPaid / $billing->total_amount) >= 0.5 ?   'progress-warning' : 'progress-error') }} w-full"
                     value="{{ $totalPaid }}" max="{{ $billing->total_amount }}">
                  </progress>
               </div>
               @endif
            </div>
         </div>
      </div>

      <!-- Payment Form -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-4">
               <div class="w-1 h-8 bg-primary rounded"></div>
               <h2 class="text-xl font-semibold">Make Payment</h2>
            </div>

            @if($remainingBalance > 0)
            <form action="{{ route('billings.process-payment', $billing) }}" method="POST" class="space-y-6">
               @csrf

               <!-- Official Receipt (OR) Number -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Official Receipt (OR) Number <span class="text-error">*</span>
                     </span>
                  </label>
                  <input type="text" name="reference_number"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('reference_number') input-error @enderror"
                     placeholder="Enter OR number" value="{{ old('reference_number') }}" required>
                  @error('reference_number')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Fee to Pay (Multiple Selection) -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">
                        Select Fees to Pay <span class="text-error">*</span>
                     </span>
                  </label>
                  <div class="space-y-2 rounded-lg p-4 max-h-80 overflow-y-auto">
                     @foreach($billing->billingItems as $item)
                     @php
                     $itemRemaining = $item->amount - $item->amount_paid;
                     @endphp
                     @if($itemRemaining > 0)
                     <div class="flex items-start gap-3 p-3 hover:bg-base-200 rounded-lg">
                        <input type="checkbox" name="billing_items[{{ $item->id }}][selected]" id="item_{{ $item->id }}"
                           class="checkbox checkbox-primary mt-1 billing-item-checkbox" data-item-id="{{ $item->id }}"
                           data-remaining="{{ $itemRemaining }}" data-amount="{{ $item->amount }}"
                           data-paid="{{ $item->amount_paid }}"
                           onchange="toggleAmountInput({{ $item->id }}); calculateTotal()">
                        <div class="flex-1">
                           <label for="item_{{ $item->id }}" class="cursor-pointer">
                              <div class="font-semibold">{{ $item->feeStructure->fee_name }}</div>
                              <div class="text-sm text-gray-500">
                                 Amount: ₱{{ number_format($item->amount, 2) }}
                                 @if($item->status === 'partial')
                                 | Paid: ₱{{ number_format($item->amount_paid, 2) }}
                                 @endif
                                 | Remaining: ₱{{ number_format($itemRemaining, 2) }}
                              </div>
                           </label>
                           <div class="mt-2" id="amount_input_{{ $item->id }}" style="display: none;">
                              <label class="label py-1">
                                 <span class="label-text text-sm">Amount to pay:</span>
                              </label>
                              <input type="number" name="billing_items[{{ $item->id }}][amount]"
                                 id="amount_field_{{ $item->id }}"
                                 class="input input-sm input-bordered rounded-lg w-full amount-input" step="0.01"
                                 min="0.01" max="{{ $itemRemaining }}" value="{{ $itemRemaining }}"
                                 data-item-id="{{ $item->id }}" onchange="calculateTotal()" placeholder="Enter amount"
                                 disabled>
                           </div>
                        </div>
                     </div>
                     @endif
                     @endforeach
                  </div>
                  @error('billing_items')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
                  @error('billing_item_id')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Total Amount to Pay -->
               <div class="form-control w-full">
                  <div class="alert alert-info">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <div>
                        <div class="font-bold">Total Amount to Pay</div>
                        <div class="text-lg font-bold" id="total_amount_display">₱0.00</div>
                     </div>
                  </div>
                  <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
               </div>

               <!-- Quick Amount Buttons -->
               {{-- <div class="grid grid-cols-2 gap-3">
                  <button type="button"
                     onclick="document.querySelector('input[name=amount_paid]').value = {{ number_format($remainingBalance / 2, 2, '.', '') }}"
                     class="btn btn-sm btn-outline rounded-lg">
                     50% Payment
                  </button>
                  <button type="button"
                     onclick="document.querySelector('input[name=amount_paid]'). value = {{ number_format($remainingBalance, 2, '.', '') }}"
                     class="btn btn-sm btn-outline rounded-lg">
                     Full Payment
                  </button>
               </div> --}}

               <!-- Divider -->
               <div class="divider"></div>

               <div class="flex gap-5 justify-center">
                  <!-- Back Button -->
                  <div class="">
                     <a href="{{route('billings.index')}}" class="btn btn-sm btn-ghost w-45 rounded-lg">
                        Back to Billings
                     </a>
                  </div>
                  <!-- Submit Button -->
                  <button type="submit" class="btn btn-primary btn-sm w-45 rounded-lg">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                     </svg>
                     Process Payment
                  </button>
               </div>

            </form>
            @else
            <div class="alert alert-success shadow-sm">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  class="stroke-current shrink-0 w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>
               <div>
                  <h3 class="font-bold">Fully Paid! </h3>
                  <div class="text-xs">This billing has been completely settled.</div>
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>

<script src="{{ asset('js/billing-payment.js') }}"></script>
@endsection