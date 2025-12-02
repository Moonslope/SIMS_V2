let scheduleCount = 0;
let allSubjects = [];

document.addEventListener('DOMContentLoaded', function() {
    const addScheduleBtn = document.getElementById('addScheduleBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const gradeLevelSelect = document.getElementById('grade_level_id');
    
    // Get subjects from global scope if available
    if (typeof window.subjectsData !== 'undefined') {
        allSubjects = window.subjectsData;
    }
    
    if (addScheduleBtn) {
        addScheduleBtn.addEventListener('click', function() {
            const container = document.getElementById('schedulesContainer');
            const scheduleItem = createScheduleItem(scheduleCount);
            container.insertAdjacentHTML('beforeend', scheduleItem);
            scheduleCount++;
        });

        // Add first schedule item on page load
        addScheduleBtn.click();
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all schedules?')) {
                clearAllSchedules();
            }
        });
    }

    // Filter subjects when grade level changes
    if (gradeLevelSelect) {
        gradeLevelSelect.addEventListener('change', function() {
            updateSubjectDropdowns();
        });
    }
});

function updateSubjectDropdowns() {
    const selectedGradeLevel = document.getElementById('grade_level_id').value;
    
    // Update all existing schedule subject dropdowns
    document.querySelectorAll('select[name^="schedules"][name$="[subject_id]"]').forEach(dropdown => {
        const currentValue = dropdown.value;
        dropdown.innerHTML = getFilteredSubjectOptions(selectedGradeLevel, currentValue);
    });
}

function getFilteredSubjectOptions(gradeLevelId, selectedValue = null) {
    let options = '<option disabled selected>Select Subject</option>';
    
    if (!gradeLevelId || gradeLevelId === 'Select Grade Level') {
        options += '<option disabled>Please select a grade level first</option>';
        return options;
    }

    const filteredSubjects = allSubjects.filter(subject => 
        subject.grade_level_id == gradeLevelId && subject.is_active
    );

    if (filteredSubjects.length > 0) {
        filteredSubjects.forEach(subject => {
            const selected = selectedValue && selectedValue == subject.id ? 'selected' : '';
            options += `<option value="${subject.id}" ${selected}>${subject.subject_name}</option>`;
        });
    } else {
        options += '<option disabled>No subjects available for this grade level</option>';
    }

    return options;
}

function createScheduleItem(index) {
    const selectedGradeLevel = document.getElementById('grade_level_id').value;
    let subjectOptions = '';
    
    if (!selectedGradeLevel || selectedGradeLevel === 'Select Grade Level') {
        subjectOptions = '<option disabled selected>Please select a grade level first</option>';
    } else if (typeof allSubjects !== 'undefined' && allSubjects.length > 0) {
        const filteredSubjects = allSubjects.filter(subject => 
            subject.grade_level_id == selectedGradeLevel && subject.is_active
        );
        
        subjectOptions = '<option disabled selected>Select Subject</option>';
        if (filteredSubjects.length > 0) {
            filteredSubjects.forEach(subject => {
                subjectOptions += `<option value="${subject.id}">${subject.subject_name}</option>`;
            });
        } else {
            subjectOptions += '<option disabled>No subjects available for this grade level</option>';
        }
    } else {
        subjectOptions = '<option disabled selected>Select Subject</option><option disabled>No subjects available</option>';
    }

    return `
        <div class="schedule-item rounded-lg p-4 bg-base-200" data-index="${index}">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold">Schedule #${index + 1}</h3>
                <button type="button" class="btn btn-xs btn-ghost text-error" onclick="removeScheduleItem(${index})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Subject <span class="text-error">*</span></label>
                    <select name="schedules[${index}][subject_id]"
                        class="select w-full select-bordered rounded-lg" required>
                        ${subjectOptions}
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Day of Week <span class="text-error">*</span></label>
                    <select name="schedules[${index}][day_of_the_week]"
                        class="select w-full select-bordered rounded-lg" required>
                        <option disabled selected>Select Day</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="monday_to_friday">Monday to Friday</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Start Time <span class="text-error">*</span></label>
                    <input name="schedules[${index}][start_time]" type="time"
                        class="input w-full input-bordered rounded-lg" required min="06:00" max="20:00" value="08:00" />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">End Time <span class="text-error">*</span></label>
                    <input name="schedules[${index}][end_time]" type="time"
                        class="input w-full input-bordered rounded-lg" required min="06:00" max="20:00" value="09:00" />
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="hidden" name="schedules[${index}][is_active]" value="0" />
                <input type="checkbox" name="schedules[${index}][is_active]" value="1"
                    class="checkbox checkbox-sm" checked />
                <label class="text-sm font-medium">Set as Active Schedule</label>
            </div>
        </div>
    `;
}

function removeScheduleItem(index) {
    const scheduleItem = document.querySelector(`.schedule-item[data-index="${index}"]`);
    if (scheduleItem) {
        scheduleItem.remove();
        updateScheduleNumbers();
    }
}

function updateScheduleNumbers() {
    const scheduleItems = document.querySelectorAll('.schedule-item');
    scheduleItems.forEach((item, idx) => {
        const heading = item.querySelector('h3');
        if (heading) {
            heading.textContent = `Schedule #${idx + 1}`;
        }
    });
}

function clearAllSchedules() {
    const container = document.getElementById('schedulesContainer');
    if (container) {
        container.innerHTML = '';
        scheduleCount = 0;
        // Add back one schedule item
        const addScheduleBtn = document.getElementById('addScheduleBtn');
        if (addScheduleBtn) {
            addScheduleBtn.click();
        }
    }
}
