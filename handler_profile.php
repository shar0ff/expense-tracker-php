<?php

/**
* Profile management script for handling user profile updates.
* 
* This script handles actions such as uploading a profile picture and changing a password.
* It requires the user to be logged in and uses server-side validation to ensure security.
*/

require 'common.php';
requireLogin();
$user = getCurrentUser($pdo);

// Action to determine what operation to perform
$action = $_POST['action'] ?? '';

if ($action === 'upload_picture') {
    /**
    * Handles profile picture upload.
    * Validates the uploaded file, ensures it is a valid image, and updates the user profile.
    */
    if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['profile_error'] = 'File upload error.';
        header('Location: profile.php');
        exit;
    }

    $file = $_FILES['profile_picture'];
    $info = getimagesize($file['tmp_name']);
    if ($info === false) {
        $_SESSION['profile_error'] = 'Uploaded file is not a valid image.';
        header('Location: profile.php');
        exit;
    }

    //Check image type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($info['mime'], $allowedTypes)) {
        $_SESSION['profile_error'] = 'Only JPG, PNG, or GIF images allowed.';
        header('Location: profile.php');
        exit;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $newFileName = 'user_' . $user['id'] . '_profile.' . $extension;

    // Move file to uploads directory
    if (!move_uploaded_file($file['tmp_name'], 'uploads/' . $newFileName)) {
        $_SESSION['profile_error'] = 'Failed to move uploaded file.';
        header('Location: profile.php');
        exit;
    }

    // Update user record
    $stmt = $pdo->prepare("UPDATE User SET profile_picture = ? WHERE id = ?");
    $stmt->execute([$newFileName, $user['id']]);

    $_SESSION['profile_success'] = 'Profile picture updated successfully.';
    header('Location: profile.php');
    exit;
}

if ($action === 'change_password') {
    /**
    * Handles password change.
    * Validates the current password, ensures the new password meets criteria, and updates the user record.
    */
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    // Server-side validation
    if (strlen($new_password) < 8) {
        $_SESSION['profile_error'] = 'New password must be at least 8 characters.';
        header('Location: profile.php');
        exit;
    }

    if ($new_password !== $confirm_new_password) {
        $_SESSION['profile_error'] = 'New passwords do not match.';
        header('Location: profile.php');
        exit;
    }

    // Check current password
    $stmt = $pdo->prepare("SELECT password FROM User WHERE id = ?");
    $stmt->execute([$user['id']]);
    $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dbUser || !password_verify($current_password, $dbUser['password'])) {
        $_SESSION['profile_error'] = 'Current password is incorrect.';
        header('Location: profile.php');
        exit;
    }

    // Update password
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE User SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $user['id']]);

    $_SESSION['profile_success'] = 'Password changed successfully.';
    header('Location: profile.php');
    exit;
}

// Redirect to profile page for undefined actions
header('Location: profile.php');
exit;
