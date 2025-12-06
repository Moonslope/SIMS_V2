// Re-enrollment page JavaScript
// Dynamic student search and section filtering

// Dynamic student search with debouncing
let searchTimeout;

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('studentSearchInput');
    const searchResults = document.getElementById('searchResults');
    const searchSpinner = document.getElementById('searchSpinner');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchSpinner.classList.add('hidden');
                return;
            }

            // Show loading
            searchSpinner.classList.remove('hidden');

            // Debounce search
            searchTimeout = setTimeout(() => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const searchUrl = document.getElementById('searchStudentsUrl').value;

                fetch(searchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ query: query })
                })
                    .then(response => response.json())
                    .then(data => {
                        searchSpinner.classList.add('hidden');

                        if (data.students && data.students.length > 0) {
                            let html = '<div class="divider"></div><div class="space-y-2 max-h-[500px] overflow-y-auto">';
                            html += `<p class="text-sm font-medium text-gray-600 mb-3">${data.students.length} ${data.students.length === 1 ? 'student' : 'students'} found</p>`;

                            data.students.forEach(student => {
                                const isEligible = student.is_eligible;
                                const statusMessage = student.status_message;

                                html += `
                                <button type="button" class="card bg-base-200 hover:bg-base-300 w-full text-left transition-colors cursor-pointer"
                                    onclick="selectThisStudent(${student.id}, '${student.name}', '${student.lrn}', ${isEligible}, '${statusMessage}', ${student.program_type_id || 'null'}, ${student.academic_year_id || 'null'}, ${student.grade_level_id || 'null'})">
                                    <div class="card-body p-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <p class="font-semibold">${student.name}</p>
                                                <p class="text-xs text-gray-500">LRN: ${student.lrn}</p>
                                            </div>
                                            <div class="ms-10">
                                                <span class="badge ${isEligible ? 'badge-success' : 'badge-error'} badge-sm">
                                                    ${isEligible ? 'Eligible' : 'Not Eligible'}
                                                </span>
                                            </div>
                                        </div>
                                        ${!isEligible ? `<p class="text-xs text-error mt-2">${statusMessage}</p>` : ''}
                                    </div>
                                </button>
                            `;
                            });

                            html += '</div>';
                            searchResults.innerHTML = html;
                        } else {
                            searchResults.innerHTML = `
                            <div class="alert alert-neutral mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>No students found matching "${query}"</span>
                            </div>
                        `;
                        }
                    })
                    .catch(error => {
                        searchSpinner.classList.add('hidden');
                        searchResults.innerHTML = `
                        <div class="alert alert-error mt-4">
                            <span>Error searching students. Please try again.</span>
                        </div>
                    `;
                        console.error('Search error:', error);
                    });
            }, 300); // 300ms debounce
        });
    }

    // Dynamic section filtering by grade level
    initializeSectionFiltering();
});

// Select student function
function selectThisStudent(id, name, lrn, isEligible, statusMsg, programTypeId, academicYearId, previousGradeId = null) {
    // Show student info
    document.getElementById('studentIdInput').value = id;
    document.getElementById('selectedStudentName').textContent = name;
    document.getElementById('selectedStudentLRN').textContent = lrn;

    document.getElementById('studentInfo').classList.remove('hidden');

    // Update status badge
    let badge = document.getElementById('statusBadge');
    let statusMsgEl = document.getElementById('statusMessage');

    if (isEligible) {
        badge.className = 'badge badge-sm badge-success';
        badge.textContent = 'Eligible';
        statusMsgEl.classList.add('hidden');
    } else {
        badge.className = 'badge badge-sm badge-error';
        badge.textContent = 'Not Eligible';
        statusMsgEl.textContent = statusMsg;
        statusMsgEl.classList.remove('hidden');
    }

    // Pre-fill program type
    if (programTypeId) {
        document.getElementById('programTypeSelect').value = programTypeId;
    }

    // Store previous grade level
    if (previousGradeId) {
        document.getElementById('previousGradeLevel').value = previousGradeId;
    }

    // Enable/Disable submit button
    document.getElementById('submitBtn').disabled = !isEligible;
}

// Form submission handler to check for grade repeat
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="re-enrollment/create"]');

    if (form) {
        form.addEventListener('submit', function (e) {
            const previousGrade = document.getElementById('previousGradeLevel').value;
            const selectedGrade = document.getElementById('gradeLevelSelect').value;
            const gradeLevelSelect = document.getElementById('gradeLevelSelect');
            const selectedOption = gradeLevelSelect.options[gradeLevelSelect.selectedIndex];
            const gradeName = selectedOption.text;

            // Check if same grade level
            if (previousGrade && selectedGrade && previousGrade === selectedGrade) {
                e.preventDefault();

                // Update modal with grade name
                document.getElementById('repeatGradeName').textContent = gradeName;

                // Show confirmation modal
                const modal = document.getElementById('grade_repeat_modal');
                modal.showModal();

                // Handle confirmation
                document.getElementById('confirmRepeatBtn').onclick = function () {
                    modal.close();
                    // Submit form without checking again
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                };
            }
        });
    }
});

// Initialize section filtering
function initializeSectionFiltering() {
    const gradeLevelSelect = document.getElementById('gradeLevelSelect');
    const sectionSelect = document.getElementById('sectionSelect');
    const academicYearSelect = document.getElementById('academicYearSelect');
    const capacityIndicator = document.getElementById('capacityIndicator');
    const capacityBadge = document.getElementById('capacityBadge');
    const capacityText = document.getElementById('capacityText');
    const capacityStatus = document.getElementById('capacityStatus');
    const sectionsUrl = document.getElementById('sectionsUrl').value;

    // When grade level changes, fetch sections
    gradeLevelSelect.addEventListener('change', function () {
        const gradeLevelId = this.value;
        const academicYearId = academicYearSelect.value;

        if (!gradeLevelId || !academicYearId) {
            sectionSelect.innerHTML = '<option value="">Select Grade Level and School Year First</option>';
            capacityIndicator.classList.add('hidden');
            return;
        }

        // Show loading
        sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
        sectionSelect.disabled = true;
        capacityIndicator.classList.add('hidden');

        // Fetch sections via AJAX
        fetch(`${sectionsUrl}?grade_level_id=${gradeLevelId}&academic_year_id=${academicYearId}`)
            .then(response => response.json())
            .then(data => {
                sectionSelect.innerHTML = '<option value="">Select Section</option>';

                if (data.length === 0) {
                    sectionSelect.innerHTML += '<option value="">No sections available</option>';
                } else {
                    data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.display;
                        option.dataset.capacity = section.capacity;
                        option.dataset.enrolled = section.enrolled;
                        option.dataset.available = section.available;
                        option.dataset.status = section.status;
                        option.dataset.color = section.color;

                        if (section.status === 'full') {
                            option.disabled = true;
                            option.textContent += ' - FULL';
                        }

                        sectionSelect.appendChild(option);
                    });
                }

                sectionSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching sections:', error);
                sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                sectionSelect.disabled = false;
            });
    });

    // Also trigger when academic year changes
    academicYearSelect.addEventListener('change', function () {
        if (gradeLevelSelect.value) {
            gradeLevelSelect.dispatchEvent(new Event('change'));
        }
    });

    // When section changes, show capacity indicator
    sectionSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        if (!selectedOption.value) {
            capacityIndicator.classList.add('hidden');
            return;
        }

        const capacity = selectedOption.dataset.capacity;
        const enrolled = selectedOption.dataset.enrolled;
        const available = selectedOption.dataset.available;
        const status = selectedOption.dataset.status;
        const color = selectedOption.dataset.color;

        // Update status text
        if (status === 'full') {
            capacityStatus.textContent = 'Section Full';
        } else if (status === 'almost-full') {
            capacityStatus.textContent = 'Almost Full - Limited Slots';
        } else {
            capacityStatus.textContent = 'Section Available';
        }

        // Update badge
        capacityBadge.className = `badge badge-sm badge-${color} mr-2`;
        capacityBadge.textContent = status === 'full' ? 'FULL' : status === 'almost-full' ? 'Almost Full' : 'Available';

        // Update text
        capacityText.innerHTML = `<strong>${enrolled}/${capacity}</strong> enrolled • <strong>${available}</strong> slots remaining`;

        // Update alert color
        const alertDiv = capacityIndicator;
        alertDiv.className = `alert alert-${color === 'error' ? 'error' : color === 'warning' ? 'warning' : 'success'}`;

        // Show indicator
        capacityIndicator.classList.remove('hidden');
    });
}
