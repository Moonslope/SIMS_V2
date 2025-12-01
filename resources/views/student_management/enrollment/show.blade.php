@extends('layout.layout')
@section('title', 'Statement of Account')

{{-- Link to external print stylesheet --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/print-statement.css')}}">
@endpush

@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs no-print">
      <ul>
         <li><a>Student Management</a></li>
         <li><a href="{{route('enrollments.index')}}">Enrollments</a></li>
         <li>Statement of Account</li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg flex justify-between items-center no-print">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Statement of Account</h1>
   </div>

   <!-- Print Header -->
   <div class="print-only" style="display: none;">
      <div class="flex items-start gap-4 mb-4">
         <div class="flex-shrink-0">
            <img src="{{ asset('images/logo-f.png') }}" alt="School Logo" class="print-logo"
               style="width: 100px; height: 100px;">
         </div>

         <div class="flex-grow text-center">
            <h1 class="text-xl font-bold uppercase" style="margin-bottom: 4px;">EURO-ASIA EMIL'S EARLY CHILD DEVELOPMENT
            </h1>
            <p class="text-xs" style="margin: 2px 0;">Blk 19 Lot 149 Malubay St., San Antonio Village, Matina, Davao
               City</p>
            <p class="text-xs" style="margin: 2px 0;">Contact: (082) 331-3779</p>
            <p class="text-xs" style="margin: 2px 0;">Email: <span
                  style="text-decoration: underline;">euroaisadevt@gmail.com</span></p>
            <p class="text-xs font-semibold" style="margin: 2px 0;">SCHOOL LRN: 466151</p>
            <h2 class="text-lg font-bold uppercase mt-3" style="text-decoration: underline;">STATEMENT OF ACCOUNT</h2>
         </div>
      </div>
   </div>

   <!-- Student Information Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="avatar placeholder no-print">
               <div class="bg-primary flex justify-center items-center text-primary-content rounded-full w-12">
                  <span class="text-xl">{{ substr($enrollment->student->first_name, 0, 1) }}{{
                     substr($enrollment->student->last_name, 0, 1) }}</span>
               </div>
            </div>
            <div>
               <h3 class="font-semibold text-lg">
                  {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }} {{
                  $enrollment->student->middle_name }}
               </h3>
               <p class="text-sm text-gray-500">LRN: {{ $enrollment->student->learner_reference_number }}</p>
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
                  <span class="label-text font-semibold">Status</span>
               </label>
               <div class="pl-2">
                  <span
                     class="badge {{ $enrollment->enrollment_status === 'enrolled' ?  'badge-success badge-soft badge-sm' : 'badge-warning badge-soft badge-sm' }}">
                     {{ ucfirst($enrollment->enrollment_status) }}
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Class Schedule Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-[#271AD2] rounded no-print"></div>
            <h2 class="text-xl font-semibold">Class Schedule</h2>
         </div>

         <div class="overflow-x-auto">
            <table class="table table-zebra w-full print-table">
               <thead class="bg-base-200">
                  <tr>
                     <th class="text-center">Time</th>
                     <th class="text-center">Minutes</th>
                     <th class="text-center">Monday</th>
                     <th class="text-center">Tuesday</th>
                     <th class="text-center">Wednesday</th>
                     <th class="text-center">Thursday</th>
                     <th class="text-center">Friday</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($scheduleData as $data)
                  <tr>
                     <td class="font-medium text-center">{{ $data['time_slot'] }}</td>
                     <td class="text-center">{{ $data['minutes'] }}</td>
                     <td class="text-center">{{ $data['subjects']['Monday'] ?: '—' }}</td>
                     <td class="text-center">{{ $data['subjects']['Tuesday'] ?: '—' }}</td>
                     <td class="text-center">{{ $data['subjects']['Wednesday'] ?: '—' }}</td>
                     <td class="text-center">{{ $data['subjects']['Thursday'] ?: '—' }}</td>
                     <td class="text-center">{{ $data['subjects']['Friday'] ?: '—' }}</td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="7" class="text-center text-gray-500">No schedule available</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <!-- Print Footer (Only visible when printing) -->
   <div class="print-only" style="display: none;">
      <hr style="border-top: 2px solid #000; margin-top: 16px; margin-bottom: 8px;">
      <div class="text-xs" style="line-height: 1.4;">
         <p style="margin: 4px 0;"><strong>NOTE:</strong> This is a computer-generated statement of account for School
            Year {{ $enrollment->academicYear->year_name }}. </p>
         <p style="margin: 4px 0;">Please feel free to let us know if there is/are query (ies) regarding with the
            records. Kindly disregard notice if payment has already been made. Thank you and we look forward to being of
            service to you.</p>
      </div>
   </div>

   <!-- Billing Information Card -->
   <div class="card bg-base-100 shadow-md">
      <div class="card-body p-6">
         <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-[#271AD2] rounded no-print"></div>
            <h2 class="text-xl font-semibold">Billing Information</h2>
         </div>

         @if($billing)
         <div class="overflow-x-auto">
            <table class="table table-zebra w-full print-table">
               <thead class="bg-base-200">
                  <tr>
                     <th class="text-left">Fee Description</th>
                     <th class="text-right">Amount</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($billing->billingItems as $item)
                  <tr>
                     <td class="font-medium">{{ $item->feeStructure->fee_name }}</td>
                     <td class="text-right">₱{{ number_format($item->amount, 2) }}</td>
                  </tr>
                  @endforeach
                  <tr class="font-bold bg-base-200">
                     <td>Total Amount Due</td>
                     <td class="text-right text-lg">₱{{ number_format($billing->total_amount, 2) }}</td>
                  </tr>
               </tbody>
            </table>
         </div>

         <div class="divider my-6 no-print"></div>

         <!-- Payment Summary -->
         <div class="flex justify-start mt-6">
            <div class="w-full md:w-1/2 lg:w-1/3">
               <div class="space-y-3">
                  <!-- Total Amount Due -->
                  <div class="flex justify-between items-center p-3 bg-base-200 rounded-lg">
                     <span class="font-medium">Total Amount Due</span>
                     <span class="font-bold text-lg">₱{{ number_format($billing->total_amount, 2) }}</span>
                  </div>

                  <!-- Total Paid -->
                  @if($totalPaid > 0)
                  <div class="flex justify-between items-center p-3 bg-success/10 rounded-lg border border-success/20">
                     <span class="font-medium text-success">Total Paid</span>
                     <span class="font-bold text-success">₱{{ number_format($totalPaid, 2) }}</span>
                  </div>
                  @endif

                  <!-- Remaining Balance -->
                  <div
                     class="flex justify-between items-center p-3 {{ ($billing->total_amount - $totalPaid) > 0 ?  'bg-error/10 border-error/20' : 'bg-success/10 border-success/20' }} rounded-lg border">
                     <span
                        class="font-semibold {{ ($billing->total_amount - $totalPaid) > 0 ? 'text-error' : 'text-success' }}">
                        Remaining Balance
                     </span>
                     <span
                        class="font-bold text-xl {{ ($billing->total_amount - $totalPaid) > 0 ? 'text-error' : 'text-success' }}">
                        ₱{{ number_format($billing->total_amount - $totalPaid, 2) }}
                     </span>
                  </div>

                  <!-- Payment Progress -->
                  @if($totalPaid > 0)
                  <div class="space-y-2 mt-4 no-print">
                     <div class="flex justify-between text-xs text-gray-500">
                        <span>{{ number_format(($totalPaid / $billing->total_amount) * 100, 1) }}% Paid</span>
                        <span>{{ number_format((($billing->total_amount - $totalPaid) / $billing->total_amount) * 100,
                           1) }}% Remaining</span>
                     </div>
                     <progress
                        class="progress {{ ($totalPaid / $billing->total_amount) >= 1 ? 'progress-success' : (($totalPaid / $billing->total_amount) >= 0.5 ? 'progress-warning' : 'progress-error') }} w-full"
                        value="{{ $totalPaid }}" max="{{ $billing->total_amount }}">
                     </progress>
                  </div>
                  @endif
               </div>
            </div>
         </div>

         @else
         <div class="alert alert-warning shadow-sm">
            <svg xmlns="http://www.w3. org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
               viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1. 333-2.694-1. 333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
               <h3 class="font-bold">No Billing Information</h3>
               <div class="text-sm">No billing information found for this enrollment.</div>
            </div>
         </div>
         @endif

         <!-- Action Buttons (Hidden on Print) -->
         <div class="flex justify-end gap-3 mt-6 no-print">
            <a href="{{route('enrollments.index')}}" class="btn btn-sm btn-ghost w-45 rounded-lg">
               Close
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-primary w-45 rounded-lg px-6">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
               </svg>
               Print Statement
            </button>
         </div>
      </div>
   </div>

   <!-- Print Footer -->
   <div class="print-only" style="display: none;">
      <hr class="mt-6 mb-4">
      <div class="text-center text-xs text-gray-600">
         <p>This is a computer-generated document. No signature required.</p>
         <p>For inquiries, please contact the Registrar's Office.</p>
         <p class="mt-2">Printed on: {{ now()->format('F d, Y h:i A') }}</p>
      </div>
   </div>
</div>
@endsection