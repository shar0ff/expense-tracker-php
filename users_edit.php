<?php

/**
* Admin User Edit Page for Expense Tracker
* 
* This script allows administrators to edit user accounts. It fetches user details based on the
* provided ID, validates access, and displays a form to update the user's role or password.
*/

require 'common.php';
requireAdmin($pdo);

$id = (int)($_GET['id'] ?? 0);

// Fetch user details
$stmt = $pdo->prepare("SELECT id, email, role FROM User WHERE id = ?");
$stmt->execute([$id]);
$userToEdit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userToEdit) {
    // Redirect if user not found
    $_SESSION['user_error'] = 'User not found.';
    header("Location: users_admin.php");
    exit;
}

// Retrieve error messages and previous input values
$error = $_SESSION['user_error'] ?? null;
$prev_role = $_SESSION['prev_role'] ?? $userToEdit['role'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Edit User | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Edit User</h1>
            <p><a href="users_admin.php">Back to Users</a></p>

            <?php if ($error): ?>
                <!-- Display server-side error message -->
                <div  class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div id="userErrors" class="error-field-user"></div>

            <p>Email:&nbsp; <?php echo htmlspecialchars($userToEdit['email']); ?></p>
            
            <div class="edit-item">
                <form action="handler_user.php" method="POST" id="editUserForm">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $userToEdit['id']; ?>">
                    <label>Role:&nbsp;
                        <select name="role">
                            <option value="User" <?php if ($userToEdit['role'] === 'User') echo 'selected'; ?>>User</option>
                            <option value="Admin" <?php if ($userToEdit['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </label>
                    <br><br>
                    <label>New Password (leave blank to keep current):&nbsp; 
                        <input type="password" name="new_password">
                    </label>
                    <br><br>
                    <button type="submit">Update User</button>
                </form>
            </div>
        </div>
        <script src="js/users.js"></script>
    </body>
</html>
<?php unset($_SESSION['user_error'], $_SESSION['prev_role']); ?>