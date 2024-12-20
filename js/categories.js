/**
* Event listener for when the DOM content is fully loaded.
* Attaches validation logic to 'add category' and 'edit category' forms.
*/

document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('addCategoryForm');
    const editForm = document.getElementById('editCategoryForm');

    /**
    * Validates the category form fields (name and type).
    * Prevents form submission if validation errors are present.
    *
    * @param {HTMLFormElement} form - The form element to validate.
    * @param {Event} e - The event object from the form submission.
    */

    const validateCategoryForm = (form, e) => {
        let errors = [];

        // Retrieve and sanitize form input
        const name = form.name.value.trim();
        const type = form.type.value;

        // Validation for category name
        if (name === '') {
            errors.push('Category name is required.');
        } else if (name.length > 100) {
            errors.push('Category name must be 100 characters or fewer.');
        }

        // Validation for category type
        if (!['income', 'expense'].includes(type)) {
            errors.push('Invalid category type selected.');
        }

        // If errors exist, prevent form submission and display errors
        if (errors.length > 0) {
            e.preventDefault();
            showError(form, errors);
        } else {
            hideError(form);
        }
    };

    // Attach validation logic to 'Add Category' form if it exists
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            validateCategoryForm(addForm, e);
        });
    }

    // Attach validation logic to 'Edit Category' form if it exists
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            validateCategoryForm(editForm, e);
        });
    }

    /**
    * Displays validation error messages on the form.
    *
    * @param {HTMLFormElement} form - The form element where errors will be displayed.
    * @param {Array<string>} errors - An array of error messages to display.
    */

    function showError(form, errors) {
        const errorDiv = form.getElementById('categoryError');
        errorDiv.innerHTML = errors.join('<br>');
        errorDiv.style.display = 'block';
    }

    /**
    * Hides the error messages on the form.
    *
    * @param {HTMLFormElement} form - The form element whose errors will be hidden.
    */
   
    function hideError(form) {
        const errorDiv = form.getElementById('categoryError');
        errorDiv.style.display = 'none';
    }
});
