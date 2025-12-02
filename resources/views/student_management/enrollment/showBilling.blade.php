@extends('layout.layout')
@section('title', 'Student Billing')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{ route('enrollments.index') }}">Enrollments</a></li>
         <li>Billing</li>
      </ul>
   </div>

   <div class="rounded-lg bg-primary shadow-lg flex justify-between items-center">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Student Billing</h1>
   </div>

   <!-- Student Information Card -->
   <div class="card bg-base-100 shadow-md rounded-lg">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="avatar placeholder">
               <div class="bg-primary flex justify-center items-center text-primary-content rounded-full w-12">
                  @if($enrollment->student)
                  <span class="text-xl">{{ substr($enrollment->student->first_name, 0, 1) }}{{
                     substr($enrollment->student->last_name, 0, 1) }}</span>
                  @else
                  <span class="text-xl">?</span>
                  @endif
               </div>
            </div>
            <div>
               <h3 class="font-semibold text-lg">
                  @if($enrollment->student)
                  {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }} {{
                  $enrollment->student->middle_name }}
                  @else
                  <span class="text-error">Student Deleted</span>
                  @endif
               </h3>
               <p class="text-sm text-gray-500">LRN: {{ $enrollment->student->learner_reference_number ?? 'N/A' }}</p>
            </div>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">School Year</span>
               </label>
               <p class="pl-2">{{ $enrollment->academicYear->year_name }}</p>
            </div>

            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Program</span>
               </label>
               <p class="pl-2">{{ $enrollment->programType->program_name }}</p>
            </div>

            <div class="form-control">
               <label class="label">
                  <span class="label-text font-semibold">Grade Level</span>
               </label>
               <p class="pl-2">{{ $enrollment->gradeLevel->grade_name ?? 'N/A' }}</p>
            </div>
         </div>
      </div>
   </div>

   @if($billing)
   <!-- Main Billing Content -->
   <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column: Billing Statement & Payment Form (2/3 width) -->
      <div class="lg:col-span-2 space-y-6">
         <!-- Billing Statement -->
         <div class="card bg-base-100 shadow-md rounded-lg">
            <div class="card-body p-6">
               <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center gap-3">
                     <div class="w-1 h-8 bg-primary rounded"></div>
                     <h2 class="text-xl font-semibold">Billing Statement</h2>
                  </div>
                  <button class="btn btn-sm btn-outline btn-primary rounded-lg" onclick="window.print()">
                     <svg xmlns="http://www.w3. org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                     </svg>
                     Print
                  </button>
               </div>

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
                        @foreach ($billing->billingItems as $item)
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
                        <tr class="font-bold bg-base-200">
                           <td>Total Amount Due</td>
                           <td class="text-right text-lg">₱{{ number_format($billing->total_amount, 2) }}</td>
                           <td class="text-right">₱{{ number_format($billing->billingItems->sum('amount_paid'), 2) }}
                           </td>
                           <td class="text-right">₱{{ number_format($billing->total_amount -
                              $billing->billingItems->sum('amount_paid'), 2) }}</td>
                           <td colspan="2"></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>

         <!-- Record Payment Form -->
         @if(($billing->total_amount - $totalPaid) > 0)
         <div class="card bg-base-100 shadow-md rounded-lg">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-4">
                  <div class="w-1 h-8 bg-primary rounded"></div>
                  <h2 class="text-xl font-semibold">Record Payment</h2>
               </div>

               <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                  @csrf
                  <input type="hidden" name="billing_id" value="{{ $billing->id }}">
                  <input type="hidden" name="academic_year_id" value="{{ $enrollment->academic_year_id }}">
                  <input type="hidden" name="processedBy" value="{{ Auth::user()->id }}">

                  <!-- Amount Paid -->
                  <div class="form-control w-full">
                     <label class="label mb-2">
                        <span class="label-text font-medium text-base">
                           Amount <span class="text-error">*</span>
                        </span>
                        {{-- <span class="label-text-alt text-gray-500">Max: ₱{{ number_format($billing->total_amount -
                           $totalPaid, 2) }}</span> --}}
                     </label>
                     <input type="number" name="amount_paid"
                        class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('amount_paid') input-error @enderror"
                        step="0.01" min="0.01" max="{{ $billing->total_amount - $totalPaid }}" placeholder="0.00"
                        required>
                     @error('amount_paid')
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Description -->
                  <div class="form-control w-full">
                     <label class="label mb-2">
                        <span class="label-text font-medium text-base">Payment Description</span>
                        <span class="label-text-alt text-gray-500">(Optional)</span>
                     </label>
                     <textarea name="description" rows="3"
                        class="textarea textarea-bordered rounded-lg w-full focus:outline-none focus:border-primary @error('description') textarea-error @enderror"
                        placeholder="Add payment notes... "></textarea>
                     @error('description')
                     <label class="label">
                        <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                     </label>
                     @enderror
                  </div>

                  <!-- Divider -->
                  <div class="divider"></div>

                  <!-- Action Buttons -->
                  <div class="flex items-center justify-end gap-3">
                     <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
                        Cancel
                     </a>
                     <button type="submit" class="btn btn-sm btn-primary rounded-lg px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Process Payment
                     </button>
                  </div>
               </form>
            </div>
         </div>
         @elseif($billing && $billing->total_amount > 0)
         <div class="alert alert-success shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               class="stroke-current shrink-0 w-6 h-6">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
               <h3 class="font-bold">Fully Paid</h3>
               <div class="text-xs">This billing statement has been fully paid.</div>
            </div>
         </div>
         @endif
      </div>

      <!-- Right Column: Payment Summary (1/3 width) -->
      <div class="lg:col-span-1">
         <div class="card bg-base-100 shadow-md rounded-lg sticky top-4">
            <div class="card-body p-6">
               <div class="flex items-center gap-3 mb-4">
                  <div class="w-1 h-8 bg-primary rounded"></div>
                  <h2 class="text-xl font-semibold">Payment Summary</h2>
               </div>

               <div class="space-y-4">
                  @if($billing && $billing->total_amount > 0)
                  <!-- Total Amount Due -->
                  <div class="flex justify-between items-center p-3 bg-base-200 rounded-lg">
                     <span class="font-medium text-sm">Total Amount Due</span>
                     <span class="font-bold text-lg">₱{{ number_format($billing->total_amount, 2) }}</span>
                  </div>

                  <!-- Total Paid -->
                  @if($totalPaid > 0)
                  <div class="flex justify-between items-center p-3 bg-success/10 rounded-lg border border-success/20">
                     <span class="font-medium text-sm text-success">Total Paid</span>
                     <span class="font-bold text-lg text-success">₱{{ number_format($totalPaid, 2) }}</span>
                  </div>
                  @endif

                  <!-- Remaining Balance -->
                  <div
                     class="flex justify-between items-center p-3 {{ ($billing->total_amount - $totalPaid) > 0 ? 'bg-error/10 border-error/20' : 'bg-success/10 border-success/20' }} rounded-lg border">
                     <span
                        class="font-medium text-sm {{ ($billing->total_amount - $totalPaid) > 0 ? 'text-error' : 'text-success' }}">
                        Remaining Balance
                     </span>
                     <span
                        class="font-bold text-xl {{ ($billing->total_amount - $totalPaid) > 0 ? 'text-error' : 'text-success' }}">
                        ₱{{ number_format($billing->total_amount - $totalPaid, 2) }}
                     </span>
                  </div>

                  <!-- Payment Progress -->
                  <div class="divider text-xs">Payment Progress</div>

                  <div class="space-y-2">
                     <div class="flex justify-between text-sm">
                        <span>{{ number_format(($totalPaid / $billing->total_amount) * 100, 1) }}% Paid</span>
                        <span>{{ number_format((($billing->total_amount - $totalPaid) / $billing->total_amount) * 100,
                           1) }}% Remaining</span>
                     </div>
                     <progress
                        class="progress {{ ($totalPaid / $billing->total_amount) >= 1 ? 'progress-success' : (($totalPaid / $billing->total_amount) >= 0.5 ? 'progress-warning' : 'progress-error') }} w-full"
                        value="{{ $totalPaid }}" max="{{ $billing->total_amount }}">
                     </progress>
                  </div>

                  <!-- Payment Status Badge -->
                  <div class="alert {{ $billing->status === 'paid' ?  'alert-success' : 'alert-warning' }} p-3">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <span class="text-xs font-medium">Status: {{ ucfirst($billing->status) }}</span>
                  </div>
                  @else
                  <!-- No Fee Structure Warning -->
                  <div class="alert alert-warning p-4">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                     </svg>
                     <div>
                        <h3 class="font-bold">No Fee Structure Set</h3>
                        <div class="text-xs">Please set up a fee for the billing of this enrollment.
                        </div>
                     </div>
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>

   @else
   <!-- No Billing Alert -->
   <div class="alert alert-warning shadow-md">
      <svg xmlns="http://www. w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3. 34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <div>
         <h3 class="font-bold">No Billing Information</h3>
         <div class="text-sm">No billing information found for this enrollment.</div>
      </div>
   </div>
   @endif
</div>

<style>
   @media print {

      .btn,
      .breadcrumbs,
      form,
      .divider {
         display: none ! important;
      }

      .card {
         box-shadow: none !important;
         border: 1px solid #ddd;
      }
   }
</style>
@endsection