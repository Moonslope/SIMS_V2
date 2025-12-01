@extends('layout.portal')
@section('title', 'Change Password')
@section('content')

<div class="p-5">
   <!-- Page Header -->
   <div class="my-8">
      <div class="flex items-center justify-center gap-3 mb-2">
         <a href="{{ route('students.profile') }}" class="btn btn-circle btn-ghost text-[#0F00CD]">
            <i class="fi fi-sr-arrow-left text-xl pt-2"></i>
         </a>
         <h1 class="text-4xl font-bold text-[#0F00CD]">Change Password</h1>
      </div>
      <p class="text-gray-600 text-center">Update your account password</p>
   </div>

   <div class="max-w-2xl mx-auto">
      <!-- Success/Error Messages -->
      @if(session('success'))
      <div role="alert" class="alert alert-success mb-6">
         <i class="fi fi-sr-check-circle"></i>
         <span>{{ session('success') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div role="alert" class="alert alert-error mb-6">
         <i class="fi fi-sr-cross-circle"></i>
         <div>
            <h3 class="font-bold">Please fix the following errors:</h3>
            <ul class="list-disc list-inside mt-2">
               @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      </div>
      @endif

      <!-- Change Password Form Card -->
      <div class="card bg-base-100 shadow-md mb-6">
         <div class="card-body">
            <form action="{{ route('students.update-password') }}" method="POST" class="space-y-4">
               @csrf

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Current Password *</span>
                  </label>
                  <input type="password" name="current_password" id="current_password" required
                     placeholder="Enter your current password" class="input input-bordered w-full" />
                  @error('current_password')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">New Password *</span>
                  </label>
                  <input type="password" name="new_password" id="new_password" required minlength="8"
                     placeholder="Enter your new password" class="input input-bordered w-full" />
                  <label class="label">
                     <span class="label-text-alt">Minimum 8 characters</span>
                  </label>
                  @error('new_password')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <div class="form-control">
                  <label class="label">
                     <span class="label-text font-semibold">Confirm New Password *</span>
                  </label>
                  <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                     minlength="8" placeholder="Confirm your new password" class="input input-bordered w-full" />
               </div>

               <div class="divider"></div>

               <div class="flex justify-end sm:flex-row gap-3">
                  <a href="{{ route('students.profile') }}" class="btn btn-sm btn-ghost flex-1 sm:flex-none">
                     Cancel
                  </a>

                  <button type="submit" class="btn btn-sm bg-[#0F00CD] text-white flex-1 sm:flex-none">
                     Change Password
                  </button>

               </div>
            </form>
         </div>
      </div>

      <!-- Password Security Tips Card -->
      <div class="card bg-[#d1ecf1] shadow-md">
         <div class="card-body">
            <h3 class="card-title text-[#0F00CD]">
               <i class="fi fi-sr-info"></i>
               Password Security Tips
            </h3>
            <ul class="space-y-2 text-sm mt-2">
               <li class="flex items-start gap-2">
                  <i class="fi fi-sr-check-circle text-[#0F00CD] mt-0.5"></i>
                  <span>Use at least 8 characters</span>
               </li>
               <li class="flex items-start gap-2">
                  <i class="fi fi-sr-check-circle text-[#0F00CD] mt-0.5"></i>
                  <span>Include uppercase and lowercase letters</span>
               </li>
               <li class="flex items-start gap-2">
                  <i class="fi fi-sr-check-circle text-[#0F00CD] mt-0.5"></i>
                  <span>Add numbers and special characters</span>
               </li>
               <li class="flex items-start gap-2">
                  <i class="fi fi-sr-check-circle text-[#0F00CD] mt-0.5"></i>
                  <span>Don't use common words or personal information</span>
               </li>
               <li class="flex items-start gap-2">
                  <i class="fi fi-sr-check-circle text-[#0F00CD] mt-0.5"></i>
                  <span>Don't share your password with anyone</span>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>

@endsection