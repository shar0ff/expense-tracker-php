/**
* Event listener for when the DOM content is fully loaded.
* Adds validation logic to the "Edit User" form to ensure role selection and password rules are met.
*/

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve the "Edit User" form
    const editForm = document.getElementById('editUserForm');

    /**
    * Validates the "Edit User" form fields: role selection and new password.
    * Ensures the role is valid and password meets length requirements if provided.
    *
    * @param {Event} e - The form submission event object.
    */
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            // Array to store validation errors
            let errors = [];

            // Retrieve input values
            const role = editForm.role.value;
            const newPassword = editForm.new_password.value.trim();

            /**
            * Validation for role selection.
            * Only 'User' or 'Admin' roles are allowed.
            */
            if (!['User', 'Admin'].includes(role)) {
                errors.push('Invalid role selected.');
            }

            /**
            * Validation for new password.
            * If provided, the new password must be at least 8 characters long.
            */
            if (newPassword !== '' && newPassword.length < 8) {
                errors.push('New password must be at least 8 characters long.');
            }

            // Display errors or allow form submission
            if (errors.length > 0) {
                e.preventDefault();
                showError(editForm, errors);
            } else {
                hideError(editForm);
            }
        });
    }

    /**
    * Displays validation error messages in the form.
    *
    * @param {HTMLFormElement} form - The form element where errors will be displayed.
    * @param {Array<string>} errors - An array of error messages to display.
    */
    function showError(form, errors) {
        const errorDiv = form.getElementById('userError');
        errorDiv.innerHTML = errors.join('<br>');
        errorDiv.style.display = 'block';
    }

    /**
    * Hides the validation error messages in the form.
    *
    * @param {HTMLFormElement} form - The form element whose error messages will be hidden.
    */
    function hideError(form) {
        const errorDiv = form.getElementById('userError');
        errorDiv.style.display = 'none';
    }
});
