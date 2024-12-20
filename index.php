<?php

/**
* Expense Tracker Home Page
* 
* This script renders the home page of the Expense Tracker application.
* It includes options for users to log in or sign up and provides a brief description of the application.
*/

require 'common.php';

// Retrieve the current logged-in user, if any
$currentUser = getCurrentUser($pdo);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome | Expense Tracker</title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container home-container">
            <h1>Welcome to the Expense Tracker</h1>
            <p>Manage your expenses, track your income, and stay on top of your financial goals.</p>

            <div class="home-actions">
                <!-- Links for user authentication -->
                <a href="login.php" class="btn-primary">Login</a>
                <a href="signup.php" class="btn-secondary">Sign Up</a>
            </div>
        </div>
    </body>
</html>
