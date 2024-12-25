<?php

/**
* Edit Profile Page for Expense Tracker
* 
* This script displays the profile management page, allowing users to:
* - View their current profile picture
* - Upload a new profile picture
* - Change their password
* 
* Success and error messages are displayed for relevant actions.
*/


require 'common.php';
requireLogin();
$user = getCurrentUser($pdo);

// Retrieve messages from session
$error = $_SESSION['profile_error'] ?? null;
$success = $_SESSION['profile_success'] ?? null;

// Determine the profile picture path
$profilePic = $user['profile_picture'] ? 'uploads/' . $user['profile_picture'] : 'uploads/default.png';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles/styles.css">
        <title> Edit Profile | Expense Tracker </title>
    </head>
    <body>
        <div class="container profile-container"> 
            <h1>Edit Profile</h1>
            <p><a href="dashboard.php">Back to Dashboard</a></p>

            <?php if ($error): ?>
                <!-- Display server-side error message -->
                <p class="error-field-server"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <!-- Display server-side success message -->
                <p class="success-field-server"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <div id="profileErrors" class="error-field-user"></div>

            <div class="profile-pic-section">
                <!-- Display current profile picture -->
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic-large">
                <p>Current Profile Picture</p>
            </div>

            <!-- Upload new profile picture form -->
            <div class="edit-item upload-picture">
                <h3>Upload New Profile Picture</h3>
                <form action="handler_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload_picture">
                    <input type="file" name="profile_picture" accept="image/*" required>
                    <div>Field is required </div>
                    <button type="submit">Upload</button>
                </form>
            </div>

             <!-- Change password form -->
            <div class="edit-item change-password">
                <h3>Change Password</h3>
                <form action="handler_profile.php" method="POST" id="changePasswordForm">
                    <input type="hidden" name="action" value="change_password">
                    <label>*Current Password:&nbsp;
                        <input type="password" name="current_password" required>
                    </label>
                    <label>*New Password:&nbsp;
                        <input type="password" name="new_password" required>
                    </label>
                    <label>*Confirm New Password:&nbsp;
                        <input type="password" name="confirm_new_password" required>
                    </label>
                    <div>* Fields are required </div>
                    <button type="submit">Update Password</button>
                </form>
            </div>
        </div>
        <script src="js/profile.js"></script>
    </body>
</html>
<?php unset($_SESSION['profile_error'], $_SESSION['profile_success']); ?>