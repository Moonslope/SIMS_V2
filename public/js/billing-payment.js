function toggleAmountInput(itemId) {
    const checkbox = document.getElementById('item_' + itemId);
    const amountDiv = document.getElementById('amount_input_' + itemId);
    const amountField = document.getElementById('amount_field_' + itemId);
    
    if (checkbox.checked) {
        amountDiv.style.display = 'block';
        amountField.disabled = false;
    } else {
        amountDiv.style.display = 'none';
        amountField.disabled = true;
    }
}

function calculateTotal() {
    let total = 0;
    const checkboxes = document.querySelectorAll('.billing-item-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
        const itemId = checkbox.dataset.itemId;
        const amountInput = document.getElementById('amount_field_' + itemId);
        if (amountInput && amountInput.value && !amountInput.disabled) {
            total += parseFloat(amountInput.value);
        }
    });
    
    document.getElementById('total_amount_display').textContent = '₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('total_amount_hidden').value = total.toFixed(2);
}
