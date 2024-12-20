<?php

/**
* User Login Script for Expense Tracker
* 
* This script handles user login functionality. It verifies user credentials,
* manages sessions, and displays error messages for invalid login attempts.
*/

require 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
    * Handle login form submission.
    * Validates the user-provided email and password against the database.
    */    
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Fetch user details based on the provided email
    $stmt = $pdo->prepare("SELECT id, password, role FROM User WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Correct password
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        // Invalid credentials
        $_SESSION['login_error'] = 'Invalid credentials.';
        $_SESSION['prev_email'] = $email;
    }
}

// Retrieve flash messages and previous input
$error = $_SESSION['login_error'] ?? null;
$prev_email = $_SESSION['prev_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Login | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="wrapper"> 
            <div class="title">Login</div>

            <?php if ($error): ?>
                <div class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div id="loginErrors" class="error-field-user"></div>

            <form method="POST" id="loginForm">
                <div class="input-field"> 
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($prev_email); ?>">
                    <label>Email: </label>
                </div>
                <div class="input-field"> 
                    <input type="password" name="password" required>
                    <label>Password: </label>
                </div>
                <div class="reset-link">
                    <a href="#"> Forgot password? </a>
                </div>
                <div class="input-field"> 
                    <input type="submit" value="Login">
                </div>
                <div class="signup-link">
                    Not a member?
                    <a href="signup.php">  Sign up now</a>
                </div>
            </form>
        </div>
        <script src="js/login.js"></script>
    </body>
    </html>
<?php unset($_SESSION['login_error'], $_SESSION['prev_email']);?>