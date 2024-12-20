<?php

/**
* Admin User Management Page for Expense Tracker
* 
* This script displays a paginated list of users, allowing administrators to view,
* edit, or delete user accounts. It includes pagination and a summary of user details.
*/

require 'common.php';
requireAdmin($pdo);

// Determine the current page
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$limit = 10; // Show 10 users per page
$offset = ($page - 1) * $limit;

// Count total users
$stmt = $pdo->query("SELECT COUNT(*) FROM User");
$total_users = $stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_users / $limit);

// Fetch users for the current page
$stmt = $pdo->query("SELECT id, email, role, created_at FROM User ORDER BY role DESC, email ASC LIMIT $limit OFFSET $offset");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Users | Expense Tracker </title>
        <link rel="stylesheet" href="./styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Manage Users (Admin)</h1>
            <p><a href="dashboard.php">Back to Dashboard</a></p>

            <table border="1">
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['role']); ?></td>
                    <td><?php echo $u['created_at']; ?></td>
                    <td>
                        <a href="users_edit.php?id=<?php echo $u['id']; ?>">Edit</a> |
                        <a href="handler_user.php?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="pagination">
                <?php if ($total_pages > 1): ?>
                    <?php if ($page > 1): ?>
                        <a href="users_admin.php?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <strong><?php echo $i; ?></strong>
                        <?php else: ?>
                            <a href="users_admin.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                        <?php if ($i < $total_pages) echo " | "; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="users_admin.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
