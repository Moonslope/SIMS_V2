@extends('layout.layout')
@section('title', 'My Profile')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
         <li>My Profile</li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">My Profile</h1>
   </div>

   <!-- Success Message -->
   @if(session('success'))
   <div class="alert alert-success shadow-md">
      <svg xmlns="http://www. w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ session('success') }}</span>
   </div>
   @endif

   <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
      @csrf
      @method('PUT')

      <!-- Personal Information Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
         <!-- Profile Info Card -->
         <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-md h-full">
               <div class="card-body p-6">
                  <div class="flex flex-col items-center">
                     <div class="avatar placeholder">
                        <div class="bg-primary flex justify-center items-center text-primary-content rounded-full w-20">
                           <span class="text-3xl">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1)
                              }}</span>
                        </div>
                     </div>
                     <h2 class="text-xl font-bold mt-2 text-center">
                        {{ $user->first_name }} {{ $user->last_name }}
                     </h2>
                     <p class="text-sm text-gray-500">{{ $user->email }}</p>
                     <span class="badge badge-primary badge-sm mt-2">{{ ucfirst($user->role) }}</span>
                  </div>

                  <div class="divider"></div>

                  <div class="space-y-3">
                     <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Edit Profile Form -->
         <div class="lg:col-span-2">
            <!-- Personal Information Card -->
            <div class="card bg-base-100 shadow-md h-full">
               <div class="card-body p-6">
                  <div class="flex items-center gap-3 mb-6">
                     <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
                     <h2 class="text-xl font-semibold">Personal Information</h2>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <!-- First Name -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              First Name <span class="text-error">*</span>
                           </span>
                        </label>
                        <input type="text" name="first_name" data-original="{{ $user->first_name }}"
                           class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary form-input @error('first_name') input-error @enderror"
                           value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                        <label class="label">
                           <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                        </label>
                        @enderror
                     </div>

                     <!-- Middle Name -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">Middle Name</span>
                           <span class="label-text-alt text-gray-500">(Optional)</span>
                        </label>
                        <input type="text" name="middle_name" data-original="{{ $user->middle_name }}"
                           class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary form-input @error('middle_name') input-error @enderror"
                           value="{{ old('middle_name', $user->middle_name) }}">
                        @error('middle_name')
                        <label class="label">
                           <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                        </label>
                        @enderror
                     </div>

                     <!-- Last Name -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              Last Name <span class="text-error">*</span>
                           </span>
                        </label>
                        <input type="text" name="last_name" data-original="{{ $user->last_name }}"
                           class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary form-input @error('last_name') input-error @enderror"
                           value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                        <label class="label">
                           <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                        </label>
                        @enderror
                     </div>

                     <!-- Email -->
                     <div class="form-control w-full">
                        <label class="label mb-2">
                           <span class="label-text font-medium text-base">
                              Email <span class="text-error">*</span>
                           </span>
                        </label>
                        <input type="email" name="email" data-original="{{ $user->email }}"
                           class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary form-input @error('email') input-error @enderror"
                           value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <label class="label">
                           <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                        </label>
                        @enderror
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Change Password Section (Full Width) -->
      <div class="card bg-base-100 shadow-md">
         <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
               <div class="w-1 h-8 bg-[#271AD2] rounded"></div>
               <h2 class="text-xl font-semibold">Change Password</h2>
            </div>

            <div class="alert alert-info mb-6">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  class="stroke-current shrink-0 w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>
               <span class="text-xs">Leave password fields blank if you don't want to change your password.</span>
            </div>

            <!-- Current Password -->
            <div class="form-control w-full mb-6">
               <label class="label mb-2">
                  <span class="label-text font-medium text-base">Current Password</span>
               </label>
               <input type="password" name="current_password" id="current_password"
                  class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary password-input @error('current_password') input-error @enderror"
                  placeholder="Enter current password">
               @error('current_password')
               <label class="label">
                  <span class="label-text-alt text-error text-sm">{{ $message }}</span>
               </label>
               @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <!-- New Password -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">New Password</span>
                  </label>
                  <input type="password" name="new_password" id="new_password"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary password-input @error('new_password') input-error @enderror"
                     placeholder="Enter new password">
                  @error('new_password')
                  <label class="label">
                     <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                  </label>
                  @enderror
               </div>

               <!-- Confirm New Password -->
               <div class="form-control w-full">
                  <label class="label mb-2">
                     <span class="label-text font-medium text-base">Confirm New Password</span>
                  </label>
                  <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                     class="input input-bordered rounded-lg w-full focus:outline-none focus:border-primary password-input"
                     placeholder="Confirm new password">
               </div>
            </div>
         </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 mt-4">
         <a href="{{ route('dashboard') }}" class="btn btn-sm btn-ghost w-35 rounded-lg">
            Cancel
         </a>
         <button type="submit" id="saveBtn" class="btn btn-sm btn-primary w-35 rounded-lg px-6" disabled>
            Save Changes
         </button>
      </div>
   </form>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveBtn');
    const formInputs = document.querySelectorAll('.form-input');
    const passwordInputs = document.querySelectorAll('.password-input');

    function checkForChanges() {
        let hasChanges = false;

        // Check personal info fields
        formInputs.forEach(input => {
            const original = input.getAttribute('data-original') || '';
            const current = input.value || '';
            if (original !== current) {
                hasChanges = true;
            }
        });

        // Check if any password field has value
        passwordInputs.forEach(input => {
            if (input.value.trim() !== '') {
                hasChanges = true;
            }
        });

        // Enable/disable save button
        saveBtn. disabled = !hasChanges;
        
        // Visual feedback
        if (hasChanges) {
            saveBtn.classList.remove('btn-disabled');
        } else {
            saveBtn.classList.add('btn-disabled');
        }
    }

    // Listen to all form inputs
    formInputs. forEach(input => {
        input.addEventListener('input', checkForChanges);
        input.addEventListener('change', checkForChanges);
    });

    passwordInputs.forEach(input => {
        input.addEventListener('input', checkForChanges);
        input.addEventListener('change', checkForChanges);
    });

    // Initial check
    checkForChanges();
});
</script>

<style>
   .btn-disabled {
      opacity: 0.5;
      cursor: not-allowed;
   }
</style>
@endsection