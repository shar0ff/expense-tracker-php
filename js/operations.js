/**
* Event listener for when the DOM content is fully loaded.
* Attaches validation logic to 'add operation' and 'edit operation' forms.
*/

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve form elements
    const addForm = document.getElementById('addOperationForm');
    const editForm = document.getElementById('editOperationForm');

    /**
    * Validates the operation form fields: category, amount, and date.
    * Prevents form submission and displays error messages if validation fails.
    *
    * @param {HTMLFormElement} form - The form element being validated.
    * @param {Event} e - The event object from the form submission.
    */
    const validateOperationForm = (form, e) => {
        // Array to store validation errors
        let errors = [];

        // Retrieve and sanitize input values
        const categoryId = form.category_id.value;
        const amount = form.amount.value.trim();
        const date = form.date.value.trim();

        // Category validation
        if (categoryId === '') {
            errors.push('Please select a category.');
        }

        // Amount validation: must be a positive number
        if (amount === '' || isNaN(amount) || parseFloat(amount) <= 0) {
            errors.push('Amount must be a positive number.');
        }

        // Date validation: must be valid and non-empty
        if (date === '') {
            errors.push('Date is required.');
        } else {
            const dateObj = new Date(date);
            if (isNaN(dateObj.getTime())) {
                errors.push('Invalid date format.');
            }
        }

        // Display errors or allow submission
        if (errors.length > 0) {
            e.preventDefault();
            showError(form, errors);
        } else {
            hideError(form);
        }
    };

    /**
    * Attaches form validation to the 'Add Operation' form if it exists.
    */
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            validateOperationForm(addForm, e);
        });
    }

    /**
    * Attaches form validation to the 'Edit Operation' form if it exists.
    */
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            validateOperationForm(editForm, e);
        });
    }

    /**
    * Displays validation error messages on the form.
    *
    * @param {HTMLFormElement} form - The form element where errors are displayed.
    * @param {Array<string>} errors - An array of error messages to display.
    */
    function showError(form, errors) {
        const errorDiv = form.getElementById('operationError');
        errorDiv.innerHTML = errors.join('<br>');
        errorDiv.style.display = 'block';
    }

    /**
    * Hides the validation error messages from the form.
    *
    * @param {HTMLFormElement} form - The form element whose errors will be hidden.
    */
    function hideError(form) {
        const errorDiv = form.getElementById('operationError');
        errorDiv.style.display = 'none';
    }
});
