let subjectCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    const addSubjectBtn = document.getElementById('addSubjectBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    
    if (addSubjectBtn) {
        addSubjectBtn.addEventListener('click', function() {
            const container = document.getElementById('subjectsContainer');
            const subjectItem = createSubjectItem(subjectCount);
            container.insertAdjacentHTML('beforeend', subjectItem);
            subjectCount++;
        });

        // Add first subject item on page load
        addSubjectBtn.click();
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all subjects?')) {
                clearAllSubjects();
            }
        });
    }
});

function createSubjectItem(index) {
    return `
        <div class="subject-item rounded-lg p-4 bg-base-200" data-index="${index}">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold">Subject #${index + 1}</h3>
                <button type="button" class="btn btn-xs btn-ghost text-error" onclick="removeSubjectItem(${index})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    
                </button>
            </div>
            
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Subject Name <span class="text-error">*</span></label>
                    <input name="subjects[${index}][subject_name]" type="text" placeholder="Type here"
                        class="input w-full input-bordered rounded-lg" required />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Description</label>
                    <textarea name="subjects[${index}][description]"
                        class="textarea w-full textarea-bordered rounded-lg"
                        placeholder="Write a short description here (Optional)"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="subjects[${index}][is_active]" value="0" />
                    <input type="checkbox" name="subjects[${index}][is_active]" value="1"
                        class="checkbox checkbox-sm" checked />
                    <label class="text-sm font-medium">Set as Active Subject</label>
                </div>
            </div>
        </div>
    `;
}

function removeSubjectItem(index) {
    const subjectItem = document.querySelector(`.subject-item[data-index="${index}"]`);
    if (subjectItem) {
        subjectItem.remove();
        updateSubjectNumbers();
    }
}

function updateSubjectNumbers() {
    const subjectItems = document.querySelectorAll('.subject-item');
    subjectItems.forEach((item, idx) => {
        const heading = item.querySelector('h3');
        if (heading) {
            heading.textContent = `Subject #${idx + 1}`;
        }
    });
}

function clearAllSubjects() {
    const container = document.getElementById('subjectsContainer');
    if (container) {
        container.innerHTML = '';
        subjectCount = 0;
        // Add back one subject item
        const addSubjectBtn = document.getElementById('addSubjectBtn');
        if (addSubjectBtn) {
            addSubjectBtn.click();
        }
    }
}
