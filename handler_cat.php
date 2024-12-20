<?php

/**
* Category Handler
* 
* Handles CRUD operations for categories:
* - Add: Inserts a new category.
* - Update: Updates an existing category.
* - Delete: Removes a category if authorized.
*/

require 'common.php'; // Include utilities and database connection
requireLogin(); // Ensure the user is logged in

$user = getCurrentUser($pdo); // Retrieve current logged-in user

// Determine the action ('add', 'update', 'delete')
$action = $_POST['action'] ?? $_GET['action'] ?? '';

/**
* Handle 'Add' Action: Add a new category.
*/
if ($action === 'add') {
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');

    // Validation: Check presence of name and type
    if ($name === '' || $type === '') {
        $_SESSION['category_error'] = 'Name and Type are required.';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header('Location: categories.php');
        exit;
    }

    // Validation: Check name length
    if (strlen($name) > 100) {
        $_SESSION['category_error'] = 'Category name must be 100 characters or fewer.';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header('Location: categories.php');
        exit;
    }

    // Validation: Check valid type
    if (!in_array($type, ['income', 'expense'])) {
        $_SESSION['category_error'] = 'Invalid category type. Must be "income" or "expense".';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header('Location: categories.php');
        exit;
    }

    // Insert the new category
    $stmt = $pdo->prepare("INSERT INTO Category (user_id, name, type) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $name, $type]);

    $_SESSION['category_success'] = 'Category added successfully.';
    header('Location: categories.php');
    exit;
}

/**
* Handle 'Update' Action: Update an existing category.
*/
if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');

    // Validation: Check presence of name and type
    if ($name === '' || $type === '') {
        $_SESSION['category_error'] = 'Name and Type are required.';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header("Location: categories_edit.php?id=$id");
        exit;
       }

    // Validation: Check name length
    if (strlen($name) > 100) {
        $_SESSION['category_error'] = 'Category name must be 100 characters or fewer.';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header("Location: categories_edit.php?id=$id");
        exit;
    }

    // Validation: Check valid type
    if (!in_array($type, ['income', 'expense'])) {
        $_SESSION['category_error'] = 'Invalid category type. Must be "income" or "expense".';
        $_SESSION['prev_category_name'] = $name;
        $_SESSION['prev_category_type'] = $type;
        header("Location: categories_edit.php?id=$id");
        exit;
    }

    // Authorization: Ensure the category belongs to the current user
    $stmt = $pdo->prepare("SELECT id FROM Category WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    if (!$stmt->fetch()) {
        $_SESSION['category_error'] = 'Category not found or unauthorized.';
        header("Location: categories.php");
        exit;
    }
        
    // Perform the update
    $stmt = $pdo->prepare("UPDATE Category SET name = ?, type = ? WHERE id = ?");
    $stmt->execute([$name, $type, $id]);

    $_SESSION['category_success'] = 'Category updated successfully.';
    header("Location: categories.php");
    exit;

    header('Location: categories.php');
    exit;
}

/**
* Handle 'Delete' Action: Remove a category.
*/
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);

    // Authorization: Ensure the category belongs to the current user
    $stmt = $pdo->prepare("DELETE FROM Category WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);

    $_SESSION['category_success'] = 'Category updated successfully.';
    header('Location: categories.php');
    exit;
}

// Redirect to categories if no valid action is provided
header('Location: categories.php');
exit;
