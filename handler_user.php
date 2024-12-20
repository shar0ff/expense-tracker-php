<?php

/**
* Admin User Management Script
* 
* This script provides functionality for administrators to manage users, including deletion
* and updating user roles and passwords. Admin permissions are required to access these actions.
*/

require 'common.php';
requireAdmin($pdo);

// Determine the action to perform
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

if ($action === 'delete') {
    /**
    * Handles user deletion.
    * Ensures the specified user is removed from the database.
    */

    // Prevent deleting the currently logged in admin or add checks as needed
    $stmt = $pdo->prepare("DELETE FROM User WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: users_admin.php');
    exit;
}

if ($action === 'update') {

    /**
    * Handles user updates.
    * Allows admin to change user roles and passwords, with validation for input values.
    */

    requireAdmin($pdo); // ensure only admin can do this

    $id = (int)($_POST['id'] ?? 0);
    $role = $_POST['role'] ?? 'User';
    $newPassword = trim($_POST['new_password'] ?? '');

    // Validate role
    if (!in_array($role, ['User', 'Admin'])) {
        $_SESSION['user_error'] = 'Invalid role selected.';
        $_SESSION['prev_role'] = $role;
        header("Location: users_edit.php?id=$id");
        exit;
    }

    // Check user existence
    $stmt = $pdo->prepare("SELECT id FROM User WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        $_SESSION['user_error'] = 'User not found.';
        header("Location: users_admin.php");
        exit;
    }

    // Validate password if provided
     if ($new_password !== '' && strlen($new_password) < 8) {
        $_SESSION['user_error'] = 'New password must be at least 8 characters long.';
        $_SESSION['prev_role'] = $role;
        header("Location: users_edit.php?id=$id");
        exit;
    }

    // Perform update
    if ($new_password !== '') {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE User SET role = ?, password = ? WHERE id = ?");
        $stmt->execute([$role, $hashed_password, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE User SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
    }

    $_SESSION['user_success'] = 'User updated successfully.';
    header("Location: users_admin.php");
    exit;
}

// Redirect to admin page for undefined actions
header('Location: users_admin.php');
exit;
