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

   <title>Student Portal - Login</title>
   <style>
      .input-group:focus-within label {
         color: #0F00CD;
      }

      .input-group input:focus {
         border-color: #0F00CD;
         box-shadow: 0 0 0 3px rgba(15, 0, 205, 0.1);
      }

      .gradient-bg {
         background: linear-gradient(135deg, #0F00CD 0%, #271AD2 50%, #4338ca 100%);
      }
   </style>
</head>

<body>
   <div class="flex min-h-screen">
      <!-- Left Side - Branding -->
      <div class="hidden lg:flex lg:w-1/2 gradient-bg relative">
         <div class="flex flex-col justify-center items-center w-full px-8 py-8">
            <div class="text-center text-white max-w-lg">
               <div class="flex gap-5 justify-center items-center">
                  <img src="{{ asset('images/logo-f.png') }}" alt="Logo" height="100" width="120">

                  <div>
                     <h1 class="text-3xl font-bold mb-4 drop-shadow-lg text-start">Euro-Asia Emil's</h1>
                     <h2 class="text-3xl font-bold mb-4 drop-shadow-lg text-start">Early Child Development</h2>
                  </div>
               </div>

               <p class="text-base opacity-80 pt-5">
                  View your balances, schedules, payment history and stay connected with your educational
                  progress
               </p>

               <div class="mt-8 space-y-3">
                  <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-wallet text-xl text-white"></i>
                     </div>
                     <div class="text-left">
                        <h3 class="font-semibold text-base">Announcements</h3>
                        <p class="text-xs opacity-80">Stay updated with the latest school announcements.</p>
                     </div>
                  </div>

                  <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-wallet text-xl text-white"></i>
                     </div>
                     <div class="text-left">
                        <h3 class="font-semibold text-base">View Balance</h3>
                        <p class="text-xs opacity-80">Check your account balance</p>
                     </div>
                  </div>

                  <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-receipt text-xl text-white"></i>
                     </div>
                     <div class="text-left">
                        <h3 class="font-semibold text-base">Payment History</h3>
                        <p class="text-xs opacity-80">Track all your transactions</p>
                     </div>
                  </div>

                  <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-3">
                     <div
                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fi fi-sr-calendar text-xl text-white"></i>
                     </div>
                     <div class="text-left">
                        <h3 class="font-semibold text-base">View Schedules</h3>
                        <p class="text-xs opacity-80">Stay organized with class schedules</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Right Side - Login Form -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-gray-50 overflow-y-auto">
         <div class="login-container w-full max-w-md">

            <div class="lg:hidden text-center mb-6">
               <h1 class="text-2xl font-bold text-gray-800 mb-1">Student Portal</h1>
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

            <!-- Login Card -->
            <form action="{{ route('login') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden">
               <div class="p-6">
                  @csrf

                  <div class="mb-6">
                     <h2 class="text-2xl font-bold text-gray-800 mb-1">Sign In</h2>
                     <p class="text-gray-500 text-sm">Enter your credentials to access your account</p>
                  </div>

                  <div class="space-y-4">
                     <!-- Email Input -->
                     <div class="form-control input-group">
                        <label class="label">
                           <span class="label-text font-semibold text-gray-700 text-sm">Email Address</span>
                        </label>
                        <div class="relative">
                           <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                              <i class="fi fi-sr-envelope text-gray-400 text-base"></i>
                           </div>
                           <input name="email" type="email" placeholder="Enter your email"
                              class="input input-bordered w-full pl-10 h-11 rounded-xl border-2 border-gray-200 focus:outline-none transition-all duration-300 text-sm"
                              value="{{ old('email') }}" required />
                        </div>
                        @error('email')
                        <span class="text-error text-sm mt-1 ml-1">{{ $message }}</span>
                        @enderror
                     </div>

                     <!-- Password Input -->
                     <div class="form-control input-group">
                        <label class="label">
                           <span class="label-text font-semibold text-gray-700 text-sm">Password</span>
                        </label>
                        <div class="relative">
                           <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                              <i class="fi fi-sr-lock text-gray-400 text-base"></i>
                           </div>
                           <input name="password" type="password" placeholder="Enter your password" id="password"
                              class="input input-bordered w-full pl-10 pr-10 h-11 rounded-xl border-2 border-gray-200 focus:outline-none transition-all duration-300 text-sm"
                              required />
                           <button type="button" id="togglePassword"
                              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                              <i id="eyeIcon" class="fi fi-sr-eye text-base"></i>
                              <i id="eyeSlashIcon" class="fi fi-sr-eye-crossed text-base hidden"></i>
                           </button>
                        </div>
                        @error('password')
                        <span class="text-error text-sm mt-1 ml-1">{{ $message }}</span>
                        @enderror
                     </div>

                     <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                           <input type="checkbox" name="remember" class="checkbox checkbox-xs checkbox-primary mr-2">
                           <span class="text-gray-600 text-xs">Remember me</span>
                        </label>
                     </div>

                     <div class="form-control mt-5">
                        <button type="submit"
                           class="btn h-11 rounded-xl w-full bg-gradient-to-r from-[#0F00CD] to-[#271AD2] hover:from-[#271AD2] hover:to-[#0F00CD] text-white font-semibold border-none shadow-lg hover:shadow-xl transition-all duration-300 text-sm">
                           Sign In
                           <i class="fi fi-sr-sign-in-alt text-base pt-1"></i>
                        </button>
                     </div>

                     <div class="text-center pt-3 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-500 flex items-center justify-center gap-2">
                           <i class="fi fi-sr-info text-gray-400 text-xs"></i>
                           <span>Need help? Contact the school administration.</span>
                        </p>
                     </div>
                  </div>
               </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-6 text-gray-500 text-xs">
               <p>&copy; {{ date('Y') }} Euro-Asia Emil's Early Child Development. All rights reserved.</p>
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