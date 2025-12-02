let sectionCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    const addSectionBtn = document.getElementById('addSectionBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    
    if (addSectionBtn) {
        addSectionBtn.addEventListener('click', function() {
            const container = document.getElementById('sectionsContainer');
            const sectionItem = createSectionItem(sectionCount);
            container.insertAdjacentHTML('beforeend', sectionItem);
            sectionCount++;
        });

        // Add first section item on page load
        addSectionBtn.click();
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all sections?')) {
                clearAllSections();
            }
        });
    }
});

function createSectionItem(index) {
    let teacherOptions = '<option disabled selected>Select Teacher</option>';
    if (typeof teachers !== 'undefined' && teachers.length > 0) {
        teachers.forEach(teacher => {
            const middleName = teacher.middle_name ? ` ${teacher.middle_name}` : '';
            const fullName = `${teacher.first_name}${middleName} ${teacher.last_name}`.trim();
            teacherOptions += `<option value="${teacher.id}">${fullName}</option>`;
        });
    } else {
        teacherOptions += '<option disabled>No teachers available</option>';
    }

    return `
        <div class="section-item rounded-lg p-4 bg-base-200" data-index="${index}">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold">Section #${index + 1}</h3>
                <button type="button" class="btn btn-xs btn-ghost text-error" onclick="removeSectionItem(${index})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Section Name <span class="text-error">*</span></label>
                    <input name="sections[${index}][section_name]" type="text" placeholder="e.g., Diamond, Sapphire"
                        class="input w-full input-bordered rounded-lg" required />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Teacher <span class="text-error">*</span></label>
                    <select name="sections[${index}][teacher_id]"
                        class="select w-full select-bordered rounded-lg" required>
                        ${teacherOptions}
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Capacity <span class="text-error">*</span></label>
                    <input name="sections[${index}][capacity]" type="number" placeholder="e.g., 30"
                        class="input w-full input-bordered rounded-lg" required min="1" max="100" value="30" />
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="hidden" name="sections[${index}][is_active]" value="0" />
                <input type="checkbox" name="sections[${index}][is_active]" value="1"
                    class="checkbox checkbox-sm" checked />
                <label class="text-sm font-medium">Set as Active Section</label>
            </div>
        </div>
    `;
}

function removeSectionItem(index) {
    const sectionItem = document.querySelector(`.section-item[data-index="${index}"]`);
    if (sectionItem) {
        sectionItem.remove();
        updateSectionNumbers();
    }
}

function updateSectionNumbers() {
    const sectionItems = document.querySelectorAll('.section-item');
    sectionItems.forEach((item, idx) => {
        const heading = item.querySelector('h3');
        if (heading) {
            heading.textContent = `Section #${idx + 1}`;
        }
    });
}

function clearAllSections() {
    const container = document.getElementById('sectionsContainer');
    if (container) {
        container.innerHTML = '';
        sectionCount = 0;
        // Add back one section item
        const addSectionBtn = document.getElementById('addSectionBtn');
        if (addSectionBtn) {
            addSectionBtn.click();
        }
    }
}
