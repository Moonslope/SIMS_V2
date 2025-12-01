<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   @vite('resources/css/app.css')
   <title>Registrar Dashboard</title>
</head>

<body>
   <div class="min-h-screen " data-theme="light">
      <div class="drawer drawer-open">
         <input id="my-drawer-4" type="checkbox" class="drawer-toggle" checked />
         <div class="drawer-content">
            <h1>REGISTRAR DASHBOARD</h1>
            @yield('content')
         </div>

         <div class="drawer-side is-drawer-close:overflow-visible">
            <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="is-drawer-close:w-14 is-drawer-open:w-58 bg-base-200 flex flex-col items-start min-h-full">
               <!-- Sidebar content here -->
               <ul class="menu w-full grow">

                  <!-- list item -->
                  <li>
                     <button class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Homepage">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                           class="size-6 text-[#0F00CD]">
                           <path
                              d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                           <path
                              d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                        </svg>

                        <span class="is-drawer-close:hidden">Dashboard</span>
                     </button>
                  </li>
               </ul>

               <!-- button to open/close drawer -->
               <div class="m-2 is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Open">
                  <label for="my-drawer-4" class="btn btn-ghost btn-circle drawer-button is-drawer-open:rotate-y-180">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round"
                        stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"
                        class="inline-block size-4 my-1.5">
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                        </path>
                        <path d="M9 4v16"></path>
                        <path d="M14 10l2 2l-2 2"></path>
                     </svg>
                  </label>
               </div>

            </div>
         </div>
      </div>
   </div>
</body>

</html>