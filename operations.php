<?php

/**
* Operations Page for Expense Tracker
* 
* This script displays a paginated list of the user's financial operations.
* It allows users to add new operations, edit existing ones, and delete operations.
* Categories are fetched for dropdowns, and pagination is implemented for better UX.
*/

require 'common.php';
requireLogin();

$user = getCurrentUser($pdo);

// Fetch user categories for the dropdown
$stmt = $pdo->prepare("SELECT id, name, type FROM Category WHERE user_id = ? ORDER BY type ASC, name ASC");
$stmt->execute([$user['id']]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Determine the current page
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$limit = 10; // items per page
$offset = ($page - 1) * $limit;

// Count total operations for the logged-in user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM Operation WHERE user_id = ?");
$stmt->execute([$user['id']]);
$total_operations = $stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_operations / $limit);

// Fetch only the operationss for the current page
$stmt = $pdo->prepare(
    "SELECT Operation.id, Operation.amount, Operation.date, Category.name AS category_name, Category.type 
     FROM Operation
     INNER JOIN Category ON Operation.category_id = Category.id
     WHERE Operation.user_id = ?
     ORDER BY Operation.date DESC
     LIMIT $limit OFFSET $offset"
);
$stmt->execute([$user['id']]);
$operations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve flash messages and previous input
$error = $_SESSION['operation_error'] ?? null;
$success = $_SESSION['operation_success'] ?? null;
$prev_category_id = $_SESSION['prev_operation_category_id'] ?? '';
$prev_amount = $_SESSION['prev_operation_amount'] ?? '';
$prev_date = $_SESSION['prev_operation_date'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Operations | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container"> 
            <h1>Your Operations</h1>
            <p><a href="dashboard.php">Back to Dashboard</a></p>

                <?php if ($error): ?>
                    <div class="error-field-server"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="error-field-server"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div id="operationErrors" class="error-field-user"></div>

            <table border="1">
                <tr>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($operations as $op): ?>
                <tr>
                    <td><?php echo htmlspecialchars($op['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($op['type']); ?></td>
                    <td><?php echo number_format($op['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($op['date']); ?></td>
                    <td>
                        <a href="operations_edit.php?id=<?php echo $op['id']; ?>">Edit</a> | 
                        <a href="handler_op.php?action=delete&id=<?php echo $op['id']; ?>" onclick="return confirm('Delete this operation?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="pagination">
                <?php if ($total_pages > 1): ?>
                    <?php if ($page > 1): ?>
                        <a href="operations.php?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <strong><?php echo $i; ?></strong>
                        <?php else: ?>
                            <a href="operations.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                        <?php if ($i < $total_pages) echo " | "; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="operations.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="add-item">
                <h2>Add New Operation</h2>
                <form action="handler_op.php" method="POST" id="addOperationForm">
                    <input type="hidden" name="action" value="add">
                    <label>*Category:&nbsp; 
                        <select name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if ((string)$cat['id'] === (string)$prev_category_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($cat['name']) . " (" . htmlspecialchars($cat['type']) . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>*Amount:&nbsp;  <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($prev_amount); ?>" required></label>
                    <label>*Date:&nbsp;  <input type="datetime-local" name="date" value="<?php echo htmlspecialchars($prev_date); ?>" required></label>
                    <div>* Fields are required </div>
                    <button type="submit">Add Operation</button>
                </form>
            </div>
        </div>
        <script src="js/operations.js"></script>
    </body>
</html>
<?php unset($_SESSION['operation_error'], $_SESSION['operation_success'], $_SESSION['prev_operation_category_id'], $_SESSION['prev_operation_amount'], $_SESSION['prev_operation_date']); ?>