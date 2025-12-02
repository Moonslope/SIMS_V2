<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Collection Report</title>
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   <style>
      @media print {
         .no-print {
            display: none;
         }

         @page {
            margin: 0.5in;
            size: letter portrait;
         }

         body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color: black !important;
         }

         * {
            color: black !important;
         }
      }

      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

      body {
         font-family: 'Inter', sans-serif;
      }

      .report-header {
         text-align: center;
         margin-bottom: 20px;
         border-bottom: 2px solid #000;
         padding-bottom: 15px;
      }

      .report-header img {
         width: 80px;
         height: 80px;
         display: inline-block;
         vertical-align: middle;
         margin-bottom: 10px;
      }

      .report-header h1 {
         font-size: 18px;
         font-weight: bold;
         text-transform: uppercase;
         margin: 5px 0;
         color: black !important;
      }

      .report-header p {
         font-size: 11px;
         margin: 2px 0;
         color: black !important;
      }

      .report-title {
         text-align: center;
         font-size: 16px;
         font-weight: bold;
         text-transform: uppercase;
         margin: 20px 0;
         text-decoration: underline;
         color: black !important;
      }

      .print-student-info {
         margin: 15px 0;
      }

      .print-student-info table {
         width: 100%;
         border-collapse: collapse;
      }

      .print-student-info td {
         padding: 5px 10px;
         font-size: 11pt;
         color: black !important;
      }

      .print-student-info td.label {
         font-weight: 600;
         width: 20%;
         color: black !important;
      }

      .section-header {
         font-size: 14px;
         font-weight: bold;
         margin: 20px 0 10px 0;
         padding-bottom: 5px;
         border-bottom: 2px solid #000;
         text-transform: uppercase;
         color: black !important;
      }

      .report-table {
         width: 100%;
         border-collapse: collapse;
         margin: 20px 0;
         border: 2px solid #000;
      }

      .report-table thead {
         background-color: #000;
      }

      .report-table th {
         padding: 8px;
         text-align: left;
         font-size: 10px;
         font-weight: 600;
         text-transform: uppercase;
         color: white !important;
         background-color: #000;
         border: 1px solid #000;
      }

      .report-table td {
         padding: 6px 8px;
         border: 1px solid #000;
         font-size: 10pt;
         color: black !important;
      }

      .report-table .total-row {
         background-color: #e5e5e5;
         font-weight: bold;
         border-top: 2px solid #000;
      }

      .signatures {
         display: grid;
         grid-template-columns: repeat(2, 1fr);
         gap: 40px;
         margin-top: 50px;
      }

      .signature-line {
         text-align: center;
         border-top: 2px solid #000;
         padding-top: 5px;
         margin-top: 40px;
      }

      .signature-line p {
         font-size: 11px;
         font-weight: 600;
         text-transform: uppercase;
         color: black !important;
      }

      .report-footer {
         margin-top: 30px;
         padding-top: 10px;
         border-top: 1px solid #000;
         text-align: center;
         font-size: 10px;
         color: black !important;
      }

      .filter-info {
         margin: 15px 0;
         padding: 10px;
         background-color: #f5f5f5;
         border: 1px solid #000;
         font-size: 11px;
         color: black !important;
      }
   </style>
</head>

<body onload="window.print()" class="bg-white">
   <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
      <button onclick="window.print()" class="btn btn-sm btn-neutral">Print</button>
      <button onclick="window.close()" class="btn btn-sm btn-ghost">Close</button>
   </div>

   <div class="max-w-3xl mx-auto bg-white p-8">
      <!-- Header -->
      <div class="report-header">
         <img src="{{ asset('images/logo-f.png') }}" alt="School Logo">
         <h1>EURO-ASIA EMIL'S EARLY CHILD DEVELOPMENT</h1>
         <p>Blk 19 Lot 149 Malubay St., San Antonio Village, Matina, Davao City</p>
         <p>Contact: (082) 331-3779 | Email: euroaisadevt@gmail.com</p>
         <p>SCHOOL LRN: 466151</p>
      </div>

      <div class="report-title">PAYMENT COLLECTION REPORT</div>

      <!-- Report Info Table -->
      <div class="print-student-info" style="margin-bottom: 20px;">
         <table>
            <tr>
               <td class="label">Report Period:</td>
               <td>
                  @if($startDate && $endDate)
                  {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} to {{
                  \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
                  @elseif($academicYear)
                  School Year: {{ $academicYear->year_name }}
                  @else
                  All Time
                  @endif
               </td>
               <td class="label">Total Payments:</td>
               <td>{{ $totalPayments }}</td>
            </tr>
            <tr>
               <td class="label">Total Collected:</td>
               <td>₱{{ number_format($totalCollected, 2) }}</td>
               <td class="label">Average Payment:</td>
               <td>₱{{ number_format($averagePayment, 2) }}</td>
            </tr>
         </table>
      </div>

      <!-- Payment Details -->
      <div class="section-header">Payment Details</div>
      @if($payments->isNotEmpty())
      <table class="report-table">
         <thead>
            <tr>
               <th style="text-align: center; width: 8%;">#</th>
               <th style="text-align: left; width: 15%;">Date</th>
               <th style="text-align: left; width: 15%;">Reference No.</th>
               <th style="text-align: left; width: 30%;">Student Name</th>
               <th style="text-align: left; width: 12%;">LRN</th>
               <th style="text-align: right; width: 20%;">Amount</th>
            </tr>
         </thead>
         <tbody>
            @foreach($payments as $index => $payment)
            <tr>
               <td style="text-align: center;">{{ $index + 1 }}</td>
               <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
               <td style="font-size: 9pt;">{{ $payment->reference_number }}</td>
               <td style="font-weight: 500;">{{ $payment->billing->enrollment->student->last_name }}, {{
                  $payment->billing->enrollment->student->first_name }}</td>
               <td>{{ $payment->billing->enrollment->student->learner_reference_number }}</td>
               <td style="text-align: right; font-weight: bold;">₱{{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
               <td colspan="5" style="text-align: right;">TOTAL COLLECTED</td>
               <td style="text-align: right;">₱{{ number_format($totalCollected, 2) }}</td>
            </tr>
         </tbody>
      </table>
      @else
      <div style="text-align: center; padding: 40px; border: 2px solid #000;">
         <p style="font-size: 14px; font-weight: 600;">No payments found for the selected period.</p>
      </div>
      @endif

      <!-- Signatures -->
      <div class="signatures">
         <div>
            <div class="signature-line">
               <p>Prepared By</p>
            </div>
         </div>
         <div>
            <div class="signature-line">
               <p>Verified By</p>
            </div>
         </div>
      </div>

      <!-- Footer -->
      <div class="report-footer">
         <p><strong>NOTE:</strong> This is a computer-generated payment collection report.</p>
         <p>Printed on: {{ now()->format('F d, Y h:i A') }}</p>
      </div>
   </div>
</body>

</html>