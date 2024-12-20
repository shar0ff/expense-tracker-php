<?php

/**
* Operations Handler
* 
* Handles CRUD (Create, Update, Delete) operations.
* - Add: Adds a new operation.
* - Update: Updates an existing operation.
* - Delete: Deletes an operation.
*/

require 'common.php'; // Include utilities and database connection
requireLogin(); // Ensure the user is logged in

$user = getCurrentUser($pdo); // Fetch the currently logged-in user

// Determine the action: 'add', 'update', or 'delete'
$action = $_POST['action'] ?? $_GET['action'] ?? '';

/**
* Handle 'Add' Action: Add a new operation.
*/
if ($action === 'add') {
    $category_id = (int)($_POST['category_id'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);
    $date = $_POST['date'] ?? '';

    // Check presence of category, amount, and date
    if ($category_id === 0 || $amount === '' || $date === '') {
        $_SESSION['operation_error'] = 'Category, Amount, and Date are required.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header('Location: operations.php');
        exit;
    }

    // Check amount is a positive number
    if (!is_numeric($amount) || (float)$amount <= 0) {
        $_SESSION['operation_error'] = 'Amount must be a positive number.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header('Location: operations.php');
        exit;
    }

    // Check category validity and ownership
    $stmt = $pdo->prepare("SELECT id FROM Category WHERE id = ? AND user_id = ?");
    $stmt->execute([$category_id, $user['id']]);
    if (!$stmt->fetch()) {
        $_SESSION['operation_error'] = 'Invalid category selected.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header('Location: operations.php');
        exit;
    }

    // Check if date is valid (basic check using strtotime)
    if (!strtotime($date)) {
        $_SESSION['operation_error'] = 'Invalid date format.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header('Location: operations.php');
        exit;
    }

    // Insert the new operation into the database
    $stmt = $pdo->prepare("INSERT INTO Operation (user_id, category_id, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user['id'], $category_id, $amount, $date]);

    $_SESSION['operation_success'] = 'Operation added successfully.';
    header('Location: operations.php');
    exit;
}

/**
* Handle 'Update' Action: Update an existing operation.
*/
if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);
    $date = $_POST['date'] ?? '';

    // Validate presence
    if ($category_id === 0 || $amount === '' || $date === '') {
        $_SESSION['operation_error'] = 'Category, Amount, and Date are required.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header("Location: operations_edit.php?id=$id");
        exit;
    }

    // Validate amount
    if (!is_numeric($amount) || (float)$amount <= 0) {
        $_SESSION['operation_error'] = 'Amount must be a positive number.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header("Location: operations_edit.php?id=$id");
        exit;
    }

    // Validate operation ownership
    $stmt = $pdo->prepare("SELECT id FROM Operation WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    if (!$stmt->fetch()) {
        $_SESSION['operation_error'] = 'Operation not found or unauthorized.';
        header("Location: operations.php");
        exit;
    }

    // Validate category ownership
    $stmt = $pdo->prepare("SELECT id FROM Category WHERE id = ? AND user_id = ?");
    $stmt->execute([$category_id, $user['id']]);
    if (!$stmt->fetch()) {
        $_SESSION['operation_error'] = 'Invalid category selected.';
        header("Location: operations_edit.php?id=$id");
        exit;
    }

    // Validate date
    if (!strtotime($date)) {
        $_SESSION['operation_error'] = 'Invalid date format.';
        $_SESSION['prev_operation_category_id'] = $category_id;
        $_SESSION['prev_operation_amount'] = $amount;
        $_SESSION['prev_operation_date'] = $date;
        header("Location: operations_edit.php?id=$id");
        exit;
    }

    // Update the operation in the database
    $stmt = $pdo->prepare("UPDATE Operation SET category_id = ?, amount = ?, date = ? WHERE id = ?");
    $stmt->execute([$category_id, $amount, $date, $id]);
    
    $_SESSION['operation_success'] = 'Operation updated successfully.';
    header("Location: operations.php");
    exit;
}

/**
* Handle 'Delete' Action: Delete an operation.
*/
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    // Delete only if operation belongs to this user
    $stmt = $pdo->prepare("DELETE FROM Operation WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);

    $_SESSION['operation_success'] = 'Operation deleted successfully.';
    header('Location: operations.php');
    exit;
}

// Redirect to operations page if no valid action is provided
header('Location: operations.php');
exit;
