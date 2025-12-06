<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
   <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
   <link rel="icon" type="image/png" href="{{ asset('images/logo-f.png') }}">
   @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])
   @stack('styles')
   <title>@yield('title')</title>
</head>

<body data-theme="light">
   <div class="flex flex-col h-screen">
      <div class="sticky top-0 z-50 bg-base-100 shrink-0 flex justify-between items-center shadow-md">
         <div class="flex items-center py-1.5 ms-5 gap-1">
            <img src="{{ asset('images/logo-f.png') }}" alt="Logo" height="50" width="50">
            <h1 class="text font-bold text-blue-600">Enrollment System</h1>
         </div>

         <div>
            <div class="dropdown dropdown-end me-3">
               <div tabindex="0" role="button" class="btn border-0 bg-base-100 shadow-none m-1 rounded-lg">
                  <div class="flex items-center gap-2">
                     <i class="fi fi-sr-user text-sm"></i>
                     <p class="pb-[3px]">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</p>
                  </div>
               </div>

               <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                  <li><a href="{{route('profile')}}">Profile</a></li>
                  <li><a href="{{route('logout')}}">Logout</a></li>
               </ul>
            </div>
         </div>
      </div>

      <div class="flex flex-1 overflow-hidden">
         <div id="sidebar" class="w-60 bg-base-200 overflow-y-auto scrollbar-thin scrollbar-blue shrink-0">
            <ul class="menu w-full flex flex-col gap-3">
               <li class="ms-0.5">
                  <a href="{{route('dashboard')}}"
                     class="py-2.5 {{ request()->routeIs('dashboard') ? 'active bg-blue-600 text-white' : '' }}">
                     <i
                        class="fi fi-ss-house-chimney text-[18px] pt-1 {{ request()->routeIs('dashboard') ?  'text-white' : 'text-blue-600' }}"></i>
                     <span>Dashboard</span>
                  </a>
               </li>

               <li>
                  <details>
                     <summary>
                        <i class="fi fi-ss-user-graduate text-lg text-blue-600"></i>
                        <span class="mb-1">Student Management</span>
                     </summary>
                     <ul>
                        <li>
                           <a href="{{route('students.index')}}"
                              class="{{ request()->routeIs('students.index') ? 'active bg-blue-600 text-white' : '' }}">
                              Student Profiles
                           </a>
                        </li>
                        <li>
                           <a href="{{route('enrollments.index')}}"
                              class="{{ request()->routeIs('enrollments.index') ? 'active bg-blue-600 text-white' : '' }}">
                              Enrolled Students
                           </a>
                        </li>
                     </ul>
                  </details>
               </li>

               <li>
                  <details>
                     <summary>
                        <i class="fi fi-ss-form text-lg text-blue-600"></i>
                        <span class="mb-1">Enrollment</span>
                     </summary>
                     <ul>
                        <li>
                           <details>
                              <summary>New Student</summary>
                              <ul>
                                 <li>
                                    <a href="{{route('students.registration.step1')}}"
                                       class="{{ request()->routeIs('students.registration.*') ? 'active bg-blue-600 text-white' : '' }}">
                                       Regular
                                    </a>
                                 </li>
                                 <li>
                                    <a href="{{route('students.sped-registration.step1')}}"
                                       class="{{ request()->routeIs('students.sped-registration.*') ? 'active bg-blue-600 text-white' : '' }}">
                                       SPED
                                    </a>
                                 </li>
                              </ul>
                           </details>
                        </li>
                        <li>
                           <a href="{{route('enrollments.re-enrollment')}}"
                              class="{{ request()->routeIs('enrollments.re-enrollment') ? 'active bg-blue-600 text-white' : '' }}">
                              Old Student
                           </a>
                        </li>
                     </ul>
                  </details>
               </li>

               <li>
                  <details>
                     <summary>
                        <i class="fi fi-ss-book-open-cover text-lg text-blue-600"></i>
                        <span class="mb-1">Academic</span>
                     </summary>
                     <ul>
                        <li>
                           <a href="{{route('grade-levels.index')}}"
                              class="{{ request()->routeIs('grade-levels.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Grade Levels
                           </a>
                        </li>
                        <li>
                           <a href="{{route('program-types.index')}}"
                              class="{{ request()->routeIs('program-types.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Program Types
                           </a>
                        </li>
                        <li>
                           <a href="{{route('sections.index')}}"
                              class="{{ request()->routeIs('sections.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Sections
                           </a>
                        </li>
                        <li>
                           <a href="{{route('subjects.index')}}"
                              class="{{ request()->routeIs('subjects.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Subjects
                           </a>
                        </li>
                        <li>
                           <a href="{{route('schedules.index')}}"
                              class="{{ request()->routeIs('schedules.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Schedules
                           </a>
                        </li>
                        <li>
                           <a href="{{route('teachers.index')}}"
                              class="{{ request()->routeIs('teachers.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Teachers
                           </a>
                        </li>
                     </ul>
                  </details>
               </li>

               <li>
                  <details>
                     <summary>
                        <i class="fi fi-ss-calculator-money text-lg text-blue-600"></i>
                        <span class="mb-1">Financial</span>
                     </summary>
                     <ul>
                        <li>
                           <a href="{{route('fee-structures.index')}}"
                              class="{{ request()->routeIs('fee-structures.*') ?  'active bg-blue-600 text-white' : '' }}">
                              Fee Structure
                           </a>
                        </li>
                        <li>
                           <a href="{{route('billings.index')}}"
                              class="{{ request()->routeIs('billings.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Billing
                           </a>
                        </li>
                        <li>
                           <a href="{{route('payments.index')}}"
                              class="{{ request()->routeIs('payments.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Payments
                           </a>
                        </li>
                     </ul>
                  </details>
               </li>

               <li>
                  <details>
                     <summary>
                        <i class="fi fi-ss-tools text-lg text-blue-600"></i>
                        <span>System</span>
                     </summary>
                     <ul>
                        <li>
                           <a href="{{route('users.index')}}"
                              class="{{ request()->routeIs('users.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Users
                           </a>
                        </li>
                        <li>
                           <a href="{{route('academic-years.index')}}"
                              class="{{ request()->routeIs('academic-years.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Academic Years
                           </a>
                        </li>
                        <li>
                           <a href="{{route('announcements.index')}}"
                              class="{{ request()->routeIs('announcements.*') ? 'active bg-blue-600 text-white' : '' }}">
                              Announcement
                           </a>
                        </li>
                     </ul>
                  </details>
               </li>

               <li class="">
                  <a href="{{route('activity-logs.index')}}"
                     class="py-2.5 {{ request()->routeIs('activity-logs.*') ? 'active bg-blue-600 text-white' : '' }}">
                     <i
                        class="fi fi-ss-book-alt text-[18px] pt-1 {{ request()->routeIs('activity-logs.*') ?  'text-white' : 'text-blue-600' }}"></i>
                     <span>Activity Logs</span>
                  </a>
               </li>
            </ul>
         </div>

         <!-- Main Content -->
         <div id="mainContent" class="flex-1 bg-base-200 overflow-y-auto scrollbar-thin scrollbar-purple">
            @yield('content')
         </div>
      </div>
   </div>

   @stack('scripts')
</body>

</html>