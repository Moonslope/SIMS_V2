<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $scheduleId = $this->route('schedule') ? $this->route('schedule')->id : null;

        // For edit mode (single schedule)
        if ($scheduleId) {
            return [
                'grade_level_id' => 'required|exists:grade_levels,id',
                'program_type_id' => 'required|exists:program_types,id',
                'subject_id' => 'required|exists:subjects,id',
                'academic_year_id' => 'required|exists:academic_years,id',
                'day_of_the_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday,Monday to Friday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_active'   => 'required|boolean',
            ];
        }

        // For create mode (multiple schedules)
        return [
            'grade_level_id' => 'required|exists:grade_levels,id',
            'program_type_id' => 'required|exists:program_types,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'schedules' => 'required|array|min:1',
            'schedules.*.subject_id' => 'required|exists:subjects,id',
            'schedules.*.day_of_the_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday,Monday to Friday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_active' => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // For edit mode (single schedule)
        if ($this->route('schedule')) {
            if ($this->day_of_the_week && $this->day_of_the_week !== 'monday_to_friday') {
                $this->merge([
                    'day_of_the_week' => ucfirst(strtolower($this->day_of_the_week))
                ]);
            } elseif ($this->day_of_the_week === 'monday_to_friday') {
                $this->merge([
                    'day_of_the_week' => 'Monday to Friday'
                ]);
            }
            return;
        }

        // For create mode (multiple schedules) - capitalize day of the week
        if ($this->has('schedules')) {
            $schedules = $this->schedules;
            foreach ($schedules as $index => $schedule) {
                if (isset($schedule['day_of_the_week'])) {
                    if ($schedule['day_of_the_week'] === 'monday_to_friday') {
                        $schedules[$index]['day_of_the_week'] = 'Monday to Friday';
                    } else {
                        $schedules[$index]['day_of_the_week'] = ucfirst(strtolower($schedule['day_of_the_week']));
                    }
                }
            }
            $this->merge(['schedules' => $schedules]);
        }
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'grade_level_id.required' => 'Please select a grade level.',
            'grade_level_id.exists' => 'The selected grade level does not exist.',

            'program_type_id.required' => 'Please select a program type.',
            'program_type_id.exists' => 'The selected program type does not exist.',

            'subject_id.required' => 'Please select a subject.',
            'subject_id.exists' => 'The selected subject does not exist.',

            'academic_year_id.required' => 'Please select an academic year.',
            'academic_year_id.exists' => 'The selected academic year does not exist.',

            'day_of_the_week.required' => 'Please select a day of the week.',
            'day_of_the_week.in' => 'The selected day is invalid.',

            'start_time.required' => 'The start time is required.',
            'start_time.date_format' => 'The start time must be in HH:MM format.',

            'end_time.required' => 'The end time is required.',
            'end_time.date_format' => 'The end time must be in HH:MM format.',
            'end_time.after' => 'The end time must be after the start time.',

            'schedules.required' => 'At least one schedule is required.',
            'schedules.array' => 'Invalid schedules format.',
            'schedules.min' => 'At least one schedule must be added.',

            'schedules.*.subject_id.required' => 'Please select a subject.',
            'schedules.*.subject_id.exists' => 'The selected subject does not exist.',

            'schedules.*.day_of_the_week.required' => 'Please select a day of the week.',
            'schedules.*.day_of_the_week.in' => 'The selected day is invalid.',

            'schedules.*.start_time.required' => 'The start time is required.',
            'schedules.*.start_time.date_format' => 'The start time must be in HH:MM format.',

            'schedules.*.end_time.required' => 'The end time is required.',
            'schedules.*.end_time.date_format' => 'The end time must be in HH:MM format.',
            'schedules.*.end_time.after' => 'The end time must be after the start time.',

            'schedules.*.is_active.required' => 'The active status is required.',
            'schedules.*.is_active.boolean' => 'The active status must be true or false.',

            'is_active.required' => 'Please select the status for this schedule.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
