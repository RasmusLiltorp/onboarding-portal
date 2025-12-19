/**
 * Form Validation Module
 *
 * Client-side form validation for repository forms.
 * Validates before submission and shows error messages.
 */

/**
 * Initialize validation on all forms with class 'form'
 * Called when DOM is ready
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.form');

    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);

        // Add real-time validation on input blur
        const inputs = form.querySelectorAll('.form__input, .form__textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
        });
    });
}

/**
 * Handle form submission
 * Prevents submission if validation fails
 */
function handleFormSubmit(event) {
    const form = event.target;
    const inputs = form.querySelectorAll('.form__input, .form__textarea');
    let isValid = true;

    // Validate all fields
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    // Block submission if invalid
    if (!isValid) {
        event.preventDefault();
    }
}

/**
 * Validate a single input field
 * Returns true if valid, false if invalid
 */
function validateField(input) {
    const value = input.value.trim();
    const fieldName = input.name;
    let error = null;

    // Check required fields
    if (input.hasAttribute('required') && value === '') {
        error = 'This field is required';
    }

    // Check URL format
    if (input.type === 'url' && value !== '') {
        if (!isValidUrl(value)) {
            error = 'Please enter a valid URL';
        }
    }

    // Check minimum length for guide
    if (fieldName === 'guide' && value !== '' && value.length < 10) {
        error = 'Guide must be at least 10 characters';
    }

    // Show or clear error
    if (error) {
        showError(input, error);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

/**
 * Check if string is a valid URL
 */
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

/**
 * Display error message below input
 */
function showError(input, message) {
    // Add error class to input
    input.classList.add('form__input--error');

    // Find or create error element
    let errorElement = input.parentElement.querySelector('.form__error');
    if (!errorElement) {
        errorElement = document.createElement('span');
        errorElement.className = 'form__error';
        input.parentElement.appendChild(errorElement);
    }

    errorElement.textContent = message;
}

/**
 * Remove error message from input
 */
function clearError(input) {
    input.classList.remove('form__input--error');

    const errorElement = input.parentElement.querySelector('.form__error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initFormValidation);
