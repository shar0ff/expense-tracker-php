/**
* Event listener for when the DOM content is fully loaded.
* Attaches validation logic to the login form.
*/

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const errorDiv = document.getElementById('loginErrors');

    /**
    * Validates the login form fields (email and password).
    * Displays error messages and prevents form submission if validation fails.
    *
    * @param {Event} e - The event object from the form submission.
    */

    form.addEventListener('submit', function (e) {
        // Array to store validation errors
        let errors = [];

        // Retrieve and sanitize form input
        const email = form.email.value.trim();
        const password = form.password.value;

        /**
        * Regular expression to validate email format.
        * Ensures the email has a valid structure (e.g., name@domain.com).
        */
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Email validation
        if (!emailPattern.test(email)) {
            errors.push('Please enter a valid email address.');
        }

        // Password validation
        if (password === '') {
            errors.push('Please enter your password.');
        }

        // If there are errors, display them and prevent form submission
        if (errors.length > 0) {
            e.preventDefault();
            errorDiv.innerHTML = errors.join('<br>');
            errorDiv.style.display = 'block';
        } else {
            errorDiv.style.display = 'none';
        }
    });
});
