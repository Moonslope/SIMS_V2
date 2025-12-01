@extends('layout.layout')
@section('title', 'Create Fee Structure')
@section('content')
<div class="px-5 py-3 flex flex-col gap-4">
   <div class="breadcrumbs text-xs">
      <ul>
         <li><a>Billing</a></li>
         <li><a href="{{route('fee-structures.index')}}">Fee Structure</a></li>
         <li><a href="{{route('fee-structures.create')}}">Create New Fee</a></li>
      </ul>
   </div>

   <div class="rounded-lg bg-[#0F00CD] shadow-lg">
      <h1 class="text-[24px] font-semibold text-base-300 ms-3 p-2">Create New Fee</h1>
   </div>

   <div class="bg-base-100 h-auto rounded-lg p-6 shadow">
      <form action="{{route('fee-structures.store')}}" method="POST">
         @csrf

         <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-8">
               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="fee_name" class="text-sm font-medium">Fee Name <span
                           class="text-error">*</span></label>
                     <input name="fee_name" type="text" placeholder="Type here"
                        class="input w-full input-bordered rounded-lg @error('fee_name') input-error @enderror"
                        value="{{ old('fee_name') }}" />
                     @error('fee_name')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="amount" class="text-sm font-medium">Amount <span class="text-error">*</span></label>
                     <input name="amount" type="numeric" placeholder="Enter Amount"
                        class="input w-full input-bordered rounded-lg @error('amount') input-error @enderror"
                        value="{{ old('amount') }}" />
                     @error('amount')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex flex-col gap-2">
                     <label for="grade_level_id" class="text-sm font-medium">Grade Level <span
                           class="text-error">*</span></label>
                     <select name="grade_level_id"
                        class="select w-full select-bordered rounded-lg @error('grade_level_id') select-error @enderror">
                        <option disabled selected>Select Grade Level</option>
                        @forelse($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id')==$gradeLevel->id ? 'selected' :
                           '' }}>
                           {{ $gradeLevel->grade_name }}
                        </option>
                        @empty
                        <option disabled>No grade levels available</option>
                        @endforelse
                     </select>
                     @error('grade_level_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="flex flex-col gap-2">
                     <label for="program_type_id" class="text-sm font-medium">Program Type <span
                           class="text-error">*</span></label>
                     <select name="program_type_id"
                        class="select w-full select-bordered rounded-lg @error('program_type_id') select-error @enderror">
                        <option disabled selected>Select Program Type</option>
                        @forelse($programTypes as $programType)
                        <option value="{{ $programType->id }}" {{ old('program_type_id')==$programType->id ? 'selected'
                           : '' }}>
                           {{ $programType->program_name }}
                        </option>
                        @empty
                        <option disabled>No program type available</option>
                        @endforelse
                     </select>
                     @error('program_type_id')
                     <div class="text-error text-sm mt-1">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="flex items-center gap-2">
                  <input type="hidden" name="is_active" value="0" />
                  <input type="checkbox" name="is_active" value="1"
                     class="checkbox checkbox-sm @error('is_active') checkbox-error @enderror" {{ old('is_active',
                     isset($feeStructure) ? $feeStructure->is_active : false) ? 'checked' : '' }}
                  />
                  <label class="text-sm font-medium">Set as Active Fee</label>
                  @error('is_active')
                  <div class="text-error text-sm mt-1">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
               <a href="{{route('fee-structures.index')}}" class="btn btn-sm btn-ghost w-35 rounded-lg">Cancel</a>
               <button type="submit" class="btn btn-primary w-35 btn-sm rounded-lg">Save</button>
            </div>
         </div>
      </form>
   </div>

</div>
@endsection