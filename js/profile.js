/**
* Event listener for when the DOM content is fully loaded.
* Adds validation logic to the profile picture form and change password form.
*/

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve form elements and error display container
    const pictureForm = document.getElementById('profilePictureForm');
    const passwordForm = document.getElementById('changePasswordForm');
    const errorDiv = document.getElementById('profileErrors');

    /**
    * Validates the profile picture upload form.
    * Ensures a file is selected and its format is supported (JPG, PNG, or GIF).
    *
    * @param {Event} e - The form submission event object.
    */
    if (pictureForm) {
        pictureForm.addEventListener('submit', function (e) {
            // Array to store validation errors
            let errors = [];

            // Retrieve the file input field and check for selected files
            const fileInput = pictureForm.querySelector('input[name="profile_picture"]');
            if (!fileInput.files.length) {
                errors.push('Please select a file to upload.');
            } else {
                // File format validation
                const file = fileInput.files[0];
                const allowedExtensions = ['jpg','jpeg','png','gif'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExt)) {
                    errors.push('Only JPG, PNG, or GIF images allowed.');
                }
            }

            // Display errors if validation fails
            if (errors.length > 0) {
                e.preventDefault();
                errorDiv.innerHTML = errors.join('<br>');
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    }

    /**
    * Validates the change password form.
    * Ensures the new password meets length requirements and matches the confirmation field.
    *
    * @param {Event} e - The form submission event object.
    */
    if (passwordForm) {
        passwordForm.addEventListener('submit', function (e) {
            // Array to store validation errors
            let errors = [];

            // Retrieve and sanitize password input values
            // const currentPassword = passwordForm.current_password.value.trim();
            const newPassword = passwordForm.new_password.value;
            const confirmNewPassword = passwordForm.confirm_new_password.value;

            // New password validation: minimum length requirement
            if (newPassword.length < 8) {
                errors.push('New password must be at least 8 characters long.');
            }

            // New password confirmation validation
            if (newPassword !== confirmNewPassword) {
                errors.push('New passwords do not match.');
            }

            // Display errors if validation fails
            if (errors.length > 0) {
                errorDiv.innerHTML = errors.join('<br>');
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    }
});
