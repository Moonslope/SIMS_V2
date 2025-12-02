let feeCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    const addFeeBtn = document.getElementById('addFeeBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    
    if (addFeeBtn) {
        addFeeBtn.addEventListener('click', function() {
            const container = document.getElementById('feesContainer');
            const feeItem = createFeeItem(feeCount);
            container.insertAdjacentHTML('beforeend', feeItem);
            feeCount++;
        });

        // Add first fee item on page load
        addFeeBtn.click();
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all fees?')) {
                clearAllFees();
            }
        });
    }
});

function createFeeItem(index) {
    return `
        <div class="fee-item rounded-lg p-4 bg-base-200" data-index="${index}">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold">Fee #${index + 1}</h3>
                <button type="button" class="btn btn-xs btn-ghost text-error" onclick="removeFeeItem(${index})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Fee Name <span class="text-error">*</span></label>
                    <input name="fees[${index}][fee_name]" type="text" placeholder="Type here"
                        class="input w-full input-bordered rounded-lg" required />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Amount <span class="text-error">*</span></label>
                    <input name="fees[${index}][amount]" type="number" step="0.01" placeholder="Enter Amount"
                        class="input w-full input-bordered rounded-lg" required />
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="hidden" name="fees[${index}][is_active]" value="0" />
                <input type="checkbox" name="fees[${index}][is_active]" value="1"
                    class="checkbox checkbox-sm" checked />
                <label class="text-sm font-medium">Set as Active Fee</label>
            </div>
        </div>
    `;
}

function removeFeeItem(index) {
    const feeItem = document.querySelector(`.fee-item[data-index="${index}"]`);
    if (feeItem) {
        feeItem.remove();
        updateFeeNumbers();
    }
}

function updateFeeNumbers() {
    const feeItems = document.querySelectorAll('.fee-item');
    feeItems.forEach((item, idx) => {
        const heading = item.querySelector('h3');
        if (heading) {
            heading.textContent = `Fee #${idx + 1}`;
        }
    });
}

function clearAllFees() {
    const container = document.getElementById('feesContainer');
    if (container) {
        container.innerHTML = '';
        feeCount = 0;
        // Add back one fee item
        const addFeeBtn = document.getElementById('addFeeBtn');
        if (addFeeBtn) {
            addFeeBtn.click();
        }
    }
}
