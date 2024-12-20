# Expense Tracker

A simple web-based expense tracking application allowing users to log expenses, 
manage categories, handle user accounts, upload profile pictures, and track incomes and expenses. 

## Key Features
- User registration and login
- Expense and income category management
- Operation (expense/income) tracking with CRUD operations
- User profiles with password change and profile picture upload
- Admin role for managing all users
- Pagination and sorting for categories and operations

## Technologies Used
- PHP (vanilla, no frameworks)
- MySQL for data storage
- HTML, CSS, and JavaScript (vanilla JS)
- PDO for database access
- Sessions for authentication

## Prerequisites
- PHP 7.4+ and a local server environment (e.g., XAMPP, LAMP, WAMP)
- MySQL or MariaDB
- A web browser

## Installation & Setup
1. Clone or download the repository:
   ```bash
   git clone https://github.com/shar0ff/expense-tracker-php
2. Move the project folder into your local server's htdocs or www directory.
3. Set up database using creation_script.sql (optionally insertion_script.sql) to create the required tables.
4. Update database credentials in common.php 
5. Ensure the uploads/ directory is writable by the web server user.
6. Open http://localhost/expense-tracker in your web browser.

## Usage
- Sign up or log in from the homepage.
- Once logged in, access your dashboard to manage categories, operations, or edit your profile.
- If you are an admin, manage all users from the admin panel.
- Try adding some categories and operations to get started.

## Print-Optimized Pages
The project includes `@media print` styles to produce clean, printer-friendly output.  
When you print pages (like categories or operations lists), unnecessary elements are hidden, and layouts adjust for a better print experience.

## Troubleshooting
- If you get "Permission denied" errors on upload, ensure `uploads/` is writable:
  ```bash
  chmod 755 uploads/
  or adjust ownership with chown according to your server environment.

## Credits
Project was created by Ivan Sharov as part of a semester project for the ZWA course at FEL CTU in the winter semester of the academic year 24/25..