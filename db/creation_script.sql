-- Create the database
CREATE DATABASE IF NOT EXISTS expense_tracker_db;

-- Use the database
USE expense_tracker_db;


-- Remove conflicting tables
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Operation;
DROP TABLE IF EXISTS User;
-- End of removing

-- Users table
CREATE TABLE
    User (
        id INT AUTO_INCREMENT PRIMARY KEY,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        role ENUM ('User', 'Admin') DEFAULT 'User' NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        profile_picture VARCHAR(255) DEFAULT NULL
    );

-- Categories table
CREATE TABLE
    Category (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        type ENUM('income', 'expense') DEFAULT 'expense' NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE
    );

-- Operations table
CREATE TABLE
    Operation (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        category_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        date DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES Category (id) ON DELETE CASCADE
    );