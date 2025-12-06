<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
   <link rel='stylesheet'
      href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
   <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
   <link rel="icon" type="image/png" href="{{ asset('images/logo-f.png') }}">
   <title>Student Portal - Login</title>
   <style>
      /* Styles now handled by DaisyUI theme */
   </style>
</head>

<body>
   <div class="flex min-h-screen">

      <div class="hidden md:flex md:w-1/2 bg-primary relative">
         <div class="flex flex-col justify-center items-center w-full h-full px-8 py-8">
            <div class="text-center text-white max-w-lg w-full">

               <div class="flex xl:flex-row gap-5 justify-center items-center">
                  <img src="{{ asset('images/logo-f.png') }}" alt="Logo" class="h-20 w-auto lg:h-24">


                  <div>
                     <h1 class="text-xl text-start lg:text-3xl font-bold mb-1 drop-shadow-lg">Euro-Asia Emil's</h1>
                     <h2 class="text-xl lg:text-3xl font-bold drop-shadow-lg opacity-90">Early Child Development</h2>
                  </div>

               </div>

               <p class="text-sm lg:text-base opacity-80 pt-6 px-4">
                  View your balances, schedules, payment history and stay connected with your educational progress
               </p>

               <div class="mt-8 space-y-3 text-left max-w-sm mx-auto">
                  <div
                     class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3 border border-white/10 hover:bg-opacity-20 transition">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-bullhorn text-xl text-white"></i>
                     </div>
                     <div>
                        <h3 class="font-semibold text-sm lg:text-base">Announcements</h3>
                        <p class="text-xs opacity-80">Stay updated with school news.</p>
                     </div>
                  </div>

                  <div
                     class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3 border border-white/10 hover:bg-opacity-20 transition">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-wallet text-xl text-white"></i>
                     </div>
                     <div>
                        <h3 class="font-semibold text-sm lg:text-base">View Balance</h3>
                        <p class="text-xs opacity-80">Check your account balance</p>
                     </div>
                  </div>

                  <div
                     class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3 border border-white/10 hover:bg-opacity-20 transition">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-receipt text-xl text-white"></i>
                     </div>
                     <div>
                        <h3 class="font-semibold text-sm lg:text-base">Payment History</h3>
                        <p class="text-xs opacity-80">Track all your transactions</p>
                     </div>
                  </div>

                  <div
                     class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3 border border-white/10 hover:bg-opacity-20 transition">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-calendar text-xl text-white"></i>
                     </div>
                     <div>
                        <h3 class="font-semibold text-sm lg:text-base">View Schedules</h3>
                        <p class="text-xs opacity-80">Stay organized with class schedules</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="w-full md:w-1/2 flex items-center justify-center p-6 bg-gray-50 overflow-y-auto">
         <div class="login-container w-full max-w-md mt-10">

            <div class="md:hidden text-center mb-6">
               <img src="{{ asset('images/logo-f.png') }}" height="100" width="100" alt="Logo" class="mx-auto mb-3">
               <h1 class="text-2xl font-bold text-primary mb-1">Student Portal</h1>
               <p class="text-sm text-gray-500">Sign in to continue</p>
            </div>

            @if(session('success'))
            <div class="alert alert-success mb-4 shadow-lg rounded-xl">
               <div class="flex items-center gap-2">
                  <i class="fi fi-sr-check-circle text-white"></i>
                  <span class="text-white">{{ session('success') }}</span>
               </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error mb-4 shadow-lg rounded-xl">
               <div class="flex items-center gap-2">
                  <i class="fi fi-sr-exclamation-circle text-white"></i>
                  <span class="text-white">{{ session('error') }}</span>
               </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST"
               class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
               <div class="p-8">
                  @csrf

                  <div class="mb-8">
                     <h2 class="text-2xl font-bold text-gray-800 mb-1">Sign In</h2>
                     <p class="text-gray-500 text-sm">Enter your credentials to access your account</p>
                  </div>

                  <div class="space-y-5">
                     <div class="form-control input-group">
                        <label class="label pt-0">
                           <span class="label-text font-bold text-gray-700 text-xs uppercase tracking-wide">Email
                              Address</span>
                        </label>
                        <div class="relative">
                           <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                              <i class="fi fi-sr-envelope text-gray-400 text-base"></i>
                           </div>
                           <input name="email" type="email" placeholder="Enter your email"
                              class="input input-bordered w-full pl-10 h-11 rounded-xl border-gray-300 focus:outline-none transition-all duration-300 text-sm bg-gray-50 focus:bg-white"
                              value="{{ old('email') }}" required />
                        </div>
                        @error('email')
                        <span class="text-error text-xs mt-1 ml-1">{{ $message }}</span>
                        @enderror
                     </div>

                     <div class="form-control input-group">
                        <label class="label pt-0">
                           <span
                              class="label-text font-bold text-gray-700 text-xs uppercase tracking-wide">Password</span>
                        </label>
                        <div class="relative">
                           <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                              <i class="fi fi-sr-lock text-gray-400 text-base"></i>
                           </div>
                           <input name="password" type="password" placeholder="Enter your password" id="password"
                              class="input input-bordered w-full pl-10 pr-10 h-11 rounded-xl border-gray-300 focus:outline-none transition-all duration-300 text-sm bg-gray-50 focus:bg-white"
                              required />
                           <button type="button" id="togglePassword"
                              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                              <i id="eyeIcon" class="fi fi-sr-eye text-base"></i>
                              <i id="eyeSlashIcon" class="fi fi-sr-eye-crossed text-base hidden"></i>
                           </button>
                        </div>
                        @error('password')
                        <span class="text-error text-xs mt-1 ml-1">{{ $message }}</span>
                        @enderror
                     </div>

                     <div class="form-control mt-6">
                        <button type="submit"
                           class="btn h-12 rounded-xl w-full bg-primary hover:bg-blue-800 text-white font-bold border-none shadow-lg hover:shadow-xl transition-all duration-300">
                           Sign In
                           <i class="fi fi-sr-sign-in-alt text-base pt-1 ml-1"></i>
                        </button>
                     </div>

                     <div class="text-center pt-4 border-t border-gray-100 mt-2">
                        <p class="text-xs text-gray-500 flex items-center justify-center gap-2">
                           <i class="fi fi-sr-info text-primary opacity-70"></i>
                           <span>Need help? Contact the school administration.</span>
                        </p>
                     </div>
                  </div>
               </div>
            </form>

            <div class="text-center mt-6 text-gray-400 text-xs">
               <p>&copy; {{ date('Y') }} Euro-Asia Emil's ECD. All rights reserved.</p>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.tailwindcss.com"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
   </script>
</body>

</html>