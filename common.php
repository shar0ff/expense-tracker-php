<?php

    /**
    * Common Utilities for Expense Tracker Application
    * 
    * This script handles:
    * - Database connection initialization
    * - User authentication helpers
    * - Admin role enforcement
    */

    session_start();

    try {
        /**
        * Establish a PDO database connection.
        * 
        * Update the credentials (`host`, `dbname`, `username`, and `password`) as necessary.
        */
        $pdo = new PDO('mysql:host=localhost;dbname=sharoiva;charset=utf8', 'sharoiva', 'webove aplikace');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        /**
        * If the database connection fails, stop execution and display an error message.
        */
        die("Database connection failed: " . $e->getMessage());
    }

    /**
    * Retrieves the currently logged-in user's information.
    * 
    * @param PDO $pdo The PDO database connection.
    * @return array|null The user data (id, email, role, profile_picture) or null if not logged in.
    */
    function getCurrentUser($pdo) {
        // Check if the user ID is stored in the session
        if (!isset($_SESSION['user_id'])) return null;

        // Fetch the user's data from the database
        $stmt = $pdo->prepare("SELECT id, email, role, profile_picture FROM User WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        // Return the user's data or null if not found
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
    * Requires the user to be logged in.
    * 
    * Redirects to the login page (index.php) if the user is not authenticated.
    */
    function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    /**
    * Requires the user to have an Admin role.
    * 
    * @param PDO $pdo The PDO database connection.
    * Redirects to the homepage (index.php) if the user is not an admin.
    */
    function requireAdmin($pdo) {
        $user = getCurrentUser($pdo);
        // Check if the user is not logged in or not an admin
        if (!$user || $user['role'] !== 'Admin') {
            header('Location: index.php');
            exit;
        }
    }
?>