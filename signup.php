<?php

/**
* User Signup Script for Expense Tracker
* 
* This script handles user registration by validating input, checking for existing users,
* and creating a new user account. It also automatically logs in the user upon successful registration.
*/

require 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
    * Handle signup form submission.
    * Validates the provided email, password, and password confirmation.
    */

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Email Validation
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = 'Please provide a valid email address.';
        $_SESSION['prev_email'] = $email;
        $_SESSION['prev_password'] = $password;
        $_SESSION['prev_password_confirmation'] = $confirm_password;
        header('Location: signup.php');
        exit;
    } 
    // Password Length Validation
    else if (strlen($password) < 8) {
        $_SESSION['signup_error'] = 'Password must be at least 8 characters long.';
        $_SESSION['prev_email'] = $email;
        $_SESSION['prev_password'] = $password;
        $_SESSION['prev_password_confirmation'] = $confirm_password;
        header('Location: signup.php');
        exit;
    } 
    // Password Confirmation Check
    else if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = 'Passwords do not match.';
        $_SESSION['prev_email'] = $email;
        $_SESSION['prev_password'] = $password;
        $_SESSION['prev_password_confirmation'] = $confirm_password;
        header('Location: signup.php');
        exit;
    }

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM User WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['signup_error'] = 'Email already exists.';
        $_SESSION['prev_email'] = $email;
        $_SESSION['prev_password'] = $password;
        $_SESSION['prev_password_confirmation'] = $confirm_password;
        header('Location: signup.php');
        exit;
    } else {
        // Create new user
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO User (email, password, role) VALUES (?, ?, 'User')");
        $stmt->execute([$email, $hashed]);
        $user_id = $pdo->lastInsertId();

        // Auto login after signup
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = 'User';
        header('Location: dashboard.php');
        exit;
    }
}

// Retrieve flash messages and previous input
$error = $_SESSION['signup_error'] ?? null;
$prev_email = $_SESSION['prev_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Sign Up | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="wrapper"> 
            <div class="title">Sign Up</div>

            <?php if ($error): ?>
                <!-- Display server-side error message -->
                <div class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div id="signupErrors" class="error-field-user"></div>

            <form method="POST" id="signupForm">
                <div class="input-field">
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($prev_email); ?>">
                    <label> *Email: </label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label> *Password: </label>
                </div>
                <div class="input-field">
                    <input type="password" name="confirm_password" required>
                    <label> *Confirm Password: </label>
                </div>
                <div class="reset-link">* Fields are required </div>
                <div class="input-field">
                    <input type="submit" value="Sign Up">
                </div>
                <div class="login-link">
                    Already have an account?
                    <a href="login.php">  Log in </a>
                </div>
            </form>
        </div>
        <script src="js/signup.js"></script>
    </body>
</html>
<?php unset($_SESSION['signup_error'], $_SESSION['prev_email']);?>