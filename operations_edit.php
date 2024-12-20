<?php

/**
* Edit Operation Script for Expense Tracker
* 
* This script allows users to edit an existing financial operation. It fetches the operation details,
* validates the user's permissions, retrieves categories for selection, and displays a form for editing.
*/

require 'common.php';
requireLogin();
$user = getCurrentUser($pdo);

$id = (int)($_GET['id'] ?? 0);

// Fetch operation details
$stmt = $pdo->prepare("SELECT id, category_id, amount, date FROM Operation WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user['id']]);
$operation = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$operation) {
    $_SESSION['operation_error'] = 'Operation not found or unauthorized.';
    header("Location: operations.php");
    exit;
}

// Fetch categories for dropdown menu
$stmt = $pdo->prepare("SELECT id, name, type FROM Category WHERE user_id = ?");
$stmt->execute([$user['id']]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve flash data for pre-filling the form
$error = $_SESSION['operation_error'] ?? null;
$prev_category_id = $_SESSION['prev_operation_category_id'] ?? $operation['category_id'];
$prev_amount = $_SESSION['prev_operation_amount'] ?? $operation['amount'];
$prev_date = $_SESSION['prev_operation_date'] ?? date('Y-m-d\TH:i:s', strtotime($operation['date']));

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Edit Operation | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Edit Operation</h1>
            <p><a href="operations.php">Back to Operations</a></p>

            <?php if ($error): ?>
                <div class="error-fild-server"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div id="operationErrors" class="error-field-user"></div>

            <div class="edit-item">
                <form action="handler_op.php" method="POST" id="editOperationForm">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $operation['id']; ?>">
                    <label>Category:&nbsp;
                        <select name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $operation['category_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($cat['name']) . " (" . $cat['type'] . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Amount:&nbsp; <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($operation['amount']); ?>" required></label>
                    <label>Date:&nbsp; <input type="datetime-local" name="date" value="<?php echo date('Y-m-d\TH:i:s', strtotime($operation['date'])); ?>" required></label>
                    <button type="submit">Update Operation</button>
                </form>
            </div>
        </div>
        <script src="js/operations.js"></script>
    </body>
</html>

<?php unset($_SESSION['operation_error'], $_SESSION['prev_operation_category_id'], $_SESSION['prev_operation_amount'], $_SESSION['prev_operation_date']); ?>