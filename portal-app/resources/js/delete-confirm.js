/**
 * Delete Confirmation Module
 *
 * Shows a confirmation dialog when user clicks delete button.
 * Only deletes if user confirms.
 */

/**
 * Initialize delete confirmation on all delete buttons
 */
function initDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('[data-delete-id]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', handleDeleteClick);
    });
}

/**
 * Handle delete button click
 * Shows confirmation dialog before deleting
 */
function handleDeleteClick(event) {
    const button = event.target;
    const itemId = button.dataset.deleteId;
    const itemName = button.dataset.deleteName || 'this item';

    // Show browser's native confirm dialog
    const confirmed = confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`);

    if (confirmed) {
        // Submit delete form
        deleteItem(itemId);
    }
}

/**
 * Delete item by submitting a form
 * Creates a temporary form to send DELETE request
 */
function deleteItem(id) {
    // Create form dynamically
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/repositories/${id}`;

    // Add CSRF token (required by Laravel)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);

    // Add method spoofing for DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    // Submit form
    document.body.appendChild(form);
    form.submit();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initDeleteConfirmation);
