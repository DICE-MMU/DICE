<?php
// db.php — shared database connection
// Fill in your actual credentials below.

$host   = 'localhost';
$dbname = 'dicemmu';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Don't leak connection details to the browser in production.
    error_log('DB connection failed: ' . $e->getMessage());
    die('Could not connect to the database. Please try again later.');
}
