<! DOCTYPE html>
   <html lang="en" data-theme="light">

   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Enrollment Report</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      <style>
         @media print {
            .no-print {
               display: none;
            }

            @page {
               margin: 0. 5in;
               size: letter portrait;
            }

            body {
               -webkit-print-color-adjust: exact;
               print-color-adjust: exact;
            }
         }

         @import url('https://fonts.googleapis. com/css2?family=Inter:wght@400;500;600;700&display=swap');

         body {
            font-family: 'Inter', sans-serif;
         }
      </style>
   </head>

   <body onload="window.print()" class="bg-white">
      <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
         <button onclick="window.print()" class="btn btn-sm btn-neutral">Print</button>
         <button onclick="window.close()" class="btn btn-sm btn-ghost">Close</button>
      </div>

      <div class="max-w-[8in] mx-auto bg-white p-8">
         <!-- Header -->
         <div class="text-center mb-6 pb-4 border-b-2 border-black">
            <div class="flex items-center justify-center gap-4 mb-2">
               <img src="{{ asset('images/logo-f.png') }}" alt="Logo" class="w-12 h-12 rounded-full" />
               <h1 class="text-2xl font-bold">ENROLLMENT REPORT</h1>
            </div>
            <p class="text-xs text-gray-600">{{ now()->format('F d, Y - g:i A') }}</p>
         </div>

         <!-- Filters (if any) -->
         @if($academicYear || $gradeLevel || $programType)
         <div class="mb-4 p-3 bg-gray-50 border border-gray-300 text-xs">
            @if($academicYear)<span class="font-semibold">SCHOOL YEAR:</span> {{ $academicYear->year_name }} @endif
            @if($gradeLevel)| <span class="font-semibold">Grade:</span> {{ $gradeLevel->grade_name }} @endif
            @if($programType)| <span class="font-semibold">Program:</span> {{ $programType->program_name }} @endif
         </div>
         @endif

         <!-- Summary -->
         <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="text-center border-2 border-black p-4">
               <div class="text-4xl font-bold">{{ $totalEnrollments }}</div>
               <div class="text-xs font-semibold uppercase mt-1">Total Enrollments</div>
            </div>
            <div class="text-center border-2 border-black p-4">
               <div class="text-4xl font-bold">{{ $maleCount }}</div>
               <div class="text-xs font-semibold uppercase mt-1">Male</div>
            </div>
            <div class="text-center border-2 border-black p-4">
               <div class="text-4xl font-bold">{{ $femaleCount }}</div>
               <div class="text-xs font-semibold uppercase mt-1">Female</div>
            </div>
         </div>

         <!-- Table -->
         @if($byGradeLevel->isNotEmpty())
         <table class="w-full border-2 border-black text-sm">
            <thead>
               <tr class="bg-black text-white">
                  <th class="p-2 text-left text-xs uppercase">Grade Level</th>
                  <th class="p-2 text-center text-xs uppercase">Students</th>
                  <th class="p-2 text-center text-xs uppercase">%</th>
               </tr>
            </thead>
            <tbody>
               @foreach($byGradeLevel as $data)
               <tr class="border-b border-gray-300">
                  <td class="p-2 font-medium">{{ $data['name'] }}</td>
                  <td class="p-2 text-center font-bold">{{ $data['count'] }}</td>
                  <td class="p-2 text-center">{{ $totalEnrollments > 0 ? number_format(($data['count'] /
                     $totalEnrollments) * 100, 1) : 0 }}%</td>
               </tr>
               @endforeach
               <tr class="bg-gray-200 font-bold border-t-2 border-black">
                  <td class="p-2">TOTAL</td>
                  <td class="p-2 text-center">{{ $totalEnrollments }}</td>
                  <td class="p-2 text-center">100%</td>
               </tr>
            </tbody>
         </table>
         @endif

         <!-- Signatures -->
         <div class="grid grid-cols-2 gap-8 mt-12">
            <div class="text-center">
               <div class="border-t border-black pt-1 mt-10">
                  <p class="text-xs font-semibold">Prepared By</p>
               </div>
            </div>
            <div class="text-center">
               <div class="border-t border-black pt-1 mt-10">
                  <p class="text-xs font-semibold">Reviewed By</p>
               </div>
            </div>
         </div>

         <!-- Footer -->
         <div class="mt-8 pt-3 border-t border-gray-300 text-center text-xs text-gray-500">
            <p>Computer-generated report | © {{ now()->year }}</p>
         </div>
      </div>
   </body>

   </html>