<?php

/**
* Categories Page
* 
* Displays a paginated list of categories for the logged-in user.
* Allows users to add, edit, and delete categories.
*/

require 'common.php'; // Include common utilities and configurations
requireLogin(); // Ensure the user is logged in

$user = getCurrentUser($pdo); // Fetch the currently logged-in user

// --------------------------- Pagination Setup ---------------------------

// Get the current page number; default to page 1
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$limit = 10; // Number of categories per page
$offset = ($page - 1) * $limit; // Calculate the offset for SQL query

// ------------------------- Fetch Category Count -------------------------

/**
* Count total categories belonging to the logged-in user.
*/
$stmt = $pdo->prepare("SELECT COUNT(*) FROM Category WHERE user_id = ?");
$stmt->execute([$user['id']]);
$total_categories = $stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_categories / $limit);

// ------------------------- Fetch Paginated Categories -------------------------

/**
* Fetch the categories for the current page.
* Sorts categories by type and name in ascending order.
* Note: LIMIT and OFFSET are concatenated after validation as PDO does not support binding them directly.
*/
$stmt = $pdo->prepare("SELECT id, name, type FROM Category WHERE user_id = ? ORDER BY type ASC, name ASC LIMIT $limit OFFSET $offset");
$stmt->execute([$user['id']]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------------- Handle Session Data -------------------------

// Retrieve potential session messages or previous form inputs
$error = $_SESSION['category_error'] ?? null;
$success = $_SESSION['category_success'] ?? null;
$prev_name = $_SESSION['prev_category_name'] ?? '';
$prev_type = $_SESSION['prev_category_type'] ?? 'expense';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Categories | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container"> 
            <h1>Your Categories</h1>
            <p><a href="dashboard.php">Back to Dashboard</a></p>

                <?php if ($error): ?>
                    <div class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="success-field-server"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div id="categoryErrors" class="error-field-user"></div>

            <table border="1">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td><?php echo htmlspecialchars($cat['type']); ?></td>
                    <td>
                        <a href="categories_edit.php?id=<?php echo $cat['id']; ?>">Edit</a> | 
                        <a href="handler_cat.php?action=delete&id=<?php echo $cat['id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="pagination">
                <?php if ($total_pages > 1): ?>
                    <?php if ($page > 1): ?>
                        <a href="categories.php?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <strong><?php echo $i; ?></strong>
                        <?php else: ?>
                            <a href="categories.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                        <?php if ($i < $total_pages) echo " | "; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="categories.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="add-item">
                <h2>Add New Category</h2>
                <form action="handler_cat.php" method="POST" id="addCategoryForm">
                    <input type="hidden" name="action" value="add">
                    <label>Name:&nbsp;<input type="text" name="name" required value="<?php echo htmlspecialchars($prev_name); ?>"></label>
                    <label>Type:&nbsp; 
                        <select name="type" required>
                            <option value="expense" <?php if ($prev_type === 'expense') echo 'selected'; ?>>Expense</option>
                            <option value="income" <?php if ($prev_type === 'income') echo 'selected'; ?>>Income</option>
                        </select>
                    </label>
                    <button type="submit">Add Category</button>
                </form>
            </div>
        </div>
        <script src="js/categories.js"></script>
    </body>
</html>

<?php unset($_SESSION['category_error'], $_SESSION['category_success'], $_SESSION['prev_category_name'], $_SESSION['prev_category_type']); ?>