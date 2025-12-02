let guardianCount = 1;

document.addEventListener('DOMContentLoaded', function() {
    const addGuardianBtn = document.getElementById('addGuardianBtn');
    const clearAllBtn = document.getElementById('clearAllGuardiansBtn');
    const guardiansContainer = document.getElementById('guardiansContainer');

    if (addGuardianBtn) {
        addGuardianBtn.addEventListener('click', function() {
            addGuardianItem();
        });
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove all guardians? This action cannot be undone.')) {
                guardiansContainer.innerHTML = '';
                guardianCount = 1;
                addGuardianItem(); // Add at least one guardian
            }
        });
    }

    // If no guardians on load, add first one
    if (guardiansContainer && guardiansContainer.children.length === 0) {
        addGuardianItem();
    }
});

function addGuardianItem() {
    const guardiansContainer = document.getElementById('guardiansContainer');
    const guardianNumber = guardianCount++;
    
    const guardianCard = document.createElement('div');
    guardianCard.className = 'card bg-base-100 border border-base-300 shadow-sm guardian-item';
    guardianCard.dataset.guardianNumber = guardianNumber;
    
    guardianCard.innerHTML = `
        <div class="card-body p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Guardian #<span class="guardian-number">${guardianNumber}</span></h3>
                <button type="button" class="btn btn-sm btn-ghost btn-circle remove-guardian-btn" onclick="removeGuardian(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            ${guardianNumber === 1 ? `
            <div class="alert alert-info mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm">The first guardian's email will be used for parent portal account creation.</span>
            </div>
            ` : ''}
            
            <!-- Guardian Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">First Name <span class="text-error">*</span></span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][first_name]" type="text"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="Enter first name" required>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Middle Name</span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][middle_name]" type="text"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="Enter middle name">
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Last Name <span class="text-error">*</span></span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][last_name]" type="text"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="Enter last name" required>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Relationship <span class="text-error">*</span></span>
                    </label>
                    <select name="guardians[${guardianNumber - 1}][relation]"
                        class="select select-bordered select-sm rounded-lg w-full focus:outline-none focus:border-primary" required>
                        <option value="" disabled selected>Select relationship</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Guardian</option>
                    </select>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Contact Number <span class="text-error">*</span></span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][contact_number]" type="tel"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="09XX XXX XXXX" required>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Email ${guardianNumber === 1 ? '<span class="text-error">*</span>' : ''}</span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][email]" type="email"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="guardian@example.com" ${guardianNumber === 1 ? 'required' : ''}>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-medium">Address <span class="text-error">*</span></span>
                    </label>
                    <input name="guardians[${guardianNumber - 1}][address]" type="text"
                        class="input input-bordered input-sm rounded-lg w-full focus:outline-none focus:border-primary"
                        placeholder="Complete address" required>
                </div>
            </div>
        </div>
    `;
    
    guardiansContainer.appendChild(guardianCard);
}

function removeGuardian(button) {
    const guardiansContainer = document.getElementById('guardiansContainer');
    const guardianCard = button.closest('.guardian-item');
    
    // Prevent removing if only one guardian left
    if (guardiansContainer.children.length <= 1) {
        alert('At least one guardian is required.');
        return;
    }
    
    guardianCard.remove();
    updateGuardianNumbers();
}

function updateGuardianNumbers() {
    const guardianItems = document.querySelectorAll('.guardian-item');
    guardianItems.forEach((item, index) => {
        const numberSpan = item.querySelector('.guardian-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
        
        // Update all input names
        const inputs = item.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name && name.startsWith('guardians[')) {
                const newName = name.replace(/guardians\[\d+\]/, `guardians[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}
