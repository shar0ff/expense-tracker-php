<?php

/**
* Edit Category Page
* 
* This script handles the display of a form to edit a category.
* It fetches the category from the database based on the provided ID
* and ensures the current user has access to it.
*/

require 'common.php'; // Common utilities and configurations
requireLogin(); // Ensures the user is logged in

$user = getCurrentUser($pdo); // Fetch the currently logged-in user

// Retrieve and sanitize the category ID from the URL
$id = (int)($_GET['id'] ?? 0);

/**
* Fetch category details based on ID and user.
* Prevents unauthorized access to other users' categories.
*/
$stmt = $pdo->prepare("SELECT id, name, type FROM Category WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user['id']]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if the category is not found or the user is unauthorized
if (!$category) {
    $_SESSION['category_error'] = 'Category not found or unauthorized.';
    header("Location: categories.php");
    exit;
}

// Retrieve previous form values or default to current category data
$error = $_SESSION['category_error'] ?? null;
$prev_name = $_SESSION['prev_category_name'] ?? $category['name'];
$prev_type = $_SESSION['prev_category_type'] ?? $category['type'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <title> Edit Category | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Edit Category</h1>
            <p><a href="categories.php">Back to Categories</a></p>

            <?php if ($error): ?>
                <div class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div id="categoryErrors" class="error-field-user"></div>

            <div class="edit-item">
                <form action="handler_cat.php" method="POST" id="editCategoryForm">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <label>*Name:&nbsp; <input type="text" name="name" required value="<?php echo htmlspecialchars($prev_name); ?>" required></label>
                    <label>*Type:&nbsp;
                        <select name="type" required>
                            <option value="expense" <?php if ($prev_type === 'expense') echo 'selected'; ?>>Expense</option>
                            <option value="income" <?php if ($prev_type === 'income') echo 'selected'; ?>>Income</option>
                        </select>
                    </label>
                    <div>* Fields are required </div>
                    <button type="submit">Update Category</button>
                </form>
            </div>
        </div>
        <script src="js/categories.js"></script>
    </body>
</html>
<?php unset($_SESSION['category_error'], $_SESSION['prev_category_name'], $_SESSION['prev_category_type']); ?>