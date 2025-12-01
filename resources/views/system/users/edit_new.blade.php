@extends('layout.layout')
@section('title', 'Edit User')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>System</a></li>
         <li><a href="{{route('users.index')}}">Users</a></li>
         <li><a>Edit User</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#271AD2] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Edit User</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('users.update', $user->id)}}" method="POST">
         @csrf
         @method('PUT')

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="first_name" class="text-sm font-medium">First Name <span
                           class="text-error">*</span></label>
                     <input name="first_name" type="text" placeholder="Enter first name"
                        class="input input-bordered rounded-lg @error('first_name') input-error @enderror"
                        value="{{ old('first_name', $user->first_name) }}" />
                     @error('first_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="middle_name" class="text-sm font-medium">Middle Name</label>
                     <input name="middle_name" type="text" placeholder="Enter middle name"
                        class="input input-bordered rounded-lg @error('middle_name') input-error @enderror"
                        value="{{ old('middle_name', $user->middle_name) }}" />
                     @error('middle_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="last_name" class="text-sm font-medium">Last Name <span
                           class="text-error">*</span></label>
                     <input name="last_name" type="text" placeholder="Enter last name"
                        class="input input-bordered rounded-lg @error('last_name') input-error @enderror"
                        value="{{ old('last_name', $user->last_name) }}" />
                     @error('last_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="email" class="text-sm font-medium">Email <span class="text-error">*</span></label>
                     <input name="email" type="email" placeholder="Enter user's email"
                        class="input w-full input-bordered rounded-lg @error('email') input-error @enderror"
                        value="{{ old('email', $user->email) }}" />
                     @error('email')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="role" class="text-sm font-medium">Role <span class="text-error">*</span></label>
                     <select class="select w-full select-bordered rounded-lg @error('role') select-error @enderror"
                        id="role" name="role">
                        <option disabled>Select a role</option>
                        <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ old('role', $user->role)=='teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="cashier" {{ old('role', $user->role)=='cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="registrar" {{ old('role', $user->role)=='registrar' ? 'selected' : '' }}>Registrar</option>
                     </select>
                     @error('role')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="password" class="text-sm font-medium">Password</label>
                     <input name="password" type="password" placeholder="Enter new password"
                        class="input w-full input-bordered rounded-lg @error('password') input-error @enderror" />
                     @error('password')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="password_confirmation" class="text-sm font-medium">Confirm Password</label>
                     <input name="password_confirmation" type="password" placeholder="Confirm password"
                        class="input w-full input-bordered rounded-lg" />
                  </div>
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('users.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">
                  Update User
               </button>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection