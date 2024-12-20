<?php

/**
* Dashboard Page
* 
* Displays a personalized dashboard for the logged-in user.
* Includes user profile information, navigation links, and role-specific features.
*/

require 'common.php'; // Include utilities and session management
requireLogin(); // Ensure the user is logged in

// Retrieve logged-in user's information
$user = getCurrentUser($pdo);

// Determine the profile picture path; default if not set
$profilePic = $user['profile_picture'] ? 'uploads/' . $user['profile_picture'] : 'uploads/default.png';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Dashboard | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="dashboard-container">
            <h1>Dashboard</h1>

            <div class="dashboard-card">
                <div class="profile-section">
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic">
                    <p>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</p>
                </div>

                <ul class="dashboard-links">
                    <li><a href="categories.php">Manage Categories</a></li>
                    <li><a href="operations.php">Manage Operations</a></li>
                    <li><a href="profile.php">Edit Profile</a></li>
                    <?php if ($user['role'] === 'Admin'): ?>
                    <li><a href="users_admin.php">Manage Users</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </body>
</html>