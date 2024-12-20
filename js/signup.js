/**
* Event listener for when the DOM content is fully loaded.
* Adds validation logic to the signup form to ensure correct input.
*/

document.addEventListener('DOMContentLoaded', function () {
    // Retrieve the signup form and the error display container
    const form = document.getElementById('signupForm');
    const errorDiv = document.getElementById('signupErrors');

    /**
    * Validates the signup form fields: email, password, and confirm password.
    * Ensures email format is correct, password meets length requirements,
    * and passwords match each other.
    *
    * @param {Event} e - The form submission event object.
    */
    form.addEventListener('submit', function (e) {
        // Array to store validation errors
        let errors = [];

        // Retrieve and sanitize input values
        const email = form.email.value.trim();
        const password = form.password.value;
        const confirmPassword = form.confirm_password.value;

        /**
        * Regular expression for validating email format.
        * Ensures the email follows a correct structure (e.g., user@domain.com).
        */
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate email format
        if (!emailPattern.test(email)) {
            errors.push('Please enter a valid email address.');
        }

        // Validate password length
        if (password.length < 8) {
            errors.push('Password must be at least 8 characters long.');
        }

        // Validate password confirmation
        if (password !== confirmPassword) {
            errors.push('Passwords do not match.');
        }

        // Display errors or allow form submission
        if (errors.length > 0) {
            e.preventDefault();
            errorDiv.innerHTML = errors.join('<br>');
            errorDiv.style.display = 'block';
        } else {
            errorDiv.style.display = 'none';
        }
    });
});
