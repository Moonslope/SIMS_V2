<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
   <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
   <link rel="icon" type="image/png" href="{{ asset('images/logo-f.png') }}">
   @vite('resources/css/app.css')
   <title>@yield('title')</title>
</head>

<body data-theme="light" class="h-screen overflow-hidden">
   <div class="drawer h-screen">
      <input id="my-drawer" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content flex flex-col h-screen">
         <div class="navbar bg-base-100 w-full shadow sticky top-0 z-50">
            <div class="flex-none lg:hidden">
               <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     class="inline-block h-6 w-6 stroke-current">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                     </path>
                  </svg>
               </label>
            </div>

            <div class="mx-2 flex-1 px-2 flex items-center gap-3">
               <img src="{{ asset('images/logo-f.png') }}" alt="Profile" height="50" width="50">
               <h1 class="text-lg font-bold text-blue-600">Student Portal</h1>
            </div>

            <div class="dropdown dropdown-end me-3">
               <div tabindex="0" role="button" class="btn border-0 bg-base-100 shadow-none m-1 rounded-lg">
                  <div class="flex items-center gap-2 ">
                     <i class="fi fi-sr-user text-sm"></i>
                     <p class="pb-[3px] hidden sm:block">{{Auth::user()->student->first_name}}</p>
                  </div>
               </div>

               <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                  <li><a href="{{route('students.profile')}}">Profile</a></li>
                  <li><a href="{{ route('students.schoolYears') }}">Switch School Year</a></li>
                  <li><a href="{{route('logout')}}">Logout</a></li>
               </ul>
            </div>
         </div>

         <div class="flex flex-1 overflow-hidden">

            <div class="hidden lg:block w-60 bg-base-200 flex-shrink-0 overflow-y-auto">
               <ul class="menu p-4 gap-2">
                  <li class="text-[16px]">
                     <a href="{{route('students.dashboard')}}"
                        class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                        <i
                           class="fi fi-ss-house-chimney me-2 {{ request()->routeIs('students.dashboard') ? 'text-white' : 'text-blue-600' }}"></i>
                        <span>Dashboard</span>
                     </a>
                  </li>

                  <li class="text-[16px]">
                     <a href="{{route('students.announcements')}}"
                        class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.announcements') ? 'bg-blue-600 text-white' : '' }}">
                        <i
                           class="fi fi-sr-bullhorn me-2 {{ request()->routeIs('students.announcements') ? 'text-white' : 'text-blue-600' }}"></i>
                        <span>Announcement</span>
                     </a>
                  </li>

                  <li class="text-[16px]">
                     <a href="{{route('students.classSchedule')}}"
                        class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.classSchedule') ? 'bg-blue-600 text-white' : '' }}">
                        <i
                           class="fi fi-ss-calendar me-2 {{ request()->routeIs('students.classSchedule') ? 'text-white' : 'text-blue-600' }}"></i>
                        <span>Class Schedule</span>
                     </a>
                  </li>

                  <li class="text-[16px]">
                     <a href="{{route('students.paymentHistory')}}"
                        class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.paymentHistory') ? 'bg-blue-600 text-white' : '' }}">
                        <i
                           class="fi fi-sr-receipt me-2 {{ request()->routeIs('students.paymentHistory') ? 'text-white' : 'text-blue-600' }}"></i>
                        <span>Payment History</span>
                     </a>
                  </li>
               </ul>
            </div>

            <div class="flex-1 bg-base-200 overflow-y-auto scrollbar-thin scrollbar-purple">
               @yield('content')
            </div>
         </div>
      </div>

      <div class="drawer-side z-50">
         <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
         <ul class="menu bg-base-200 min-h-full w-60 p-4 gap-2">
            <li class="text-[16px]">
               <a href="{{route('students.dashboard')}}"
                  class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                  <i
                     class="fi fi-ss-house-chimney me-2 {{ request()->routeIs('students.dashboard') ? 'text-white' : 'text-blue-600' }}"></i>
                  <span>Dashboard</span>
               </a>
            </li>

            <li class="text-[16px]">
               <a href="{{route('students.announcements')}}"
                  class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.announcements') ? 'bg-blue-600 text-white' : '' }}">
                  <i
                     class="fi fi-sr-bullhorn me-2 {{ request()->routeIs('students.announcements') ? 'text-white' : 'text-blue-600' }}"></i>
                  <span>Announcement</span>
               </a>
            </li>

            <li class="text-[16px]">
               <a href="{{route('students.classSchedule')}}"
                  class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.classSchedule') ? 'bg-blue-600 text-white' : '' }}">
                  <i
                     class="fi fi-ss-calendar me-2 {{ request()->routeIs('students.classSchedule') ? 'text-white' : 'text-blue-600' }}"></i>
                  <span>Class Schedule</span>
               </a>
            </li>

            <li class="text-[16px]">
               <a href="{{route('students.paymentHistory')}}"
                  class="py-2.5 rounded-lg transition-colors {{ request()->routeIs('students.paymentHistory') ? 'bg-blue-600 text-white' : '' }}">
                  <i
                     class="fi fi-sr-receipt me-2 {{ request()->routeIs('students.paymentHistory') ? 'text-white' : 'text-blue-600' }}"></i>
                  <span>Payment History</span>
               </a>
            </li>
         </ul>
      </div>
   </div>
</body>

</html>