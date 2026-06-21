<?php
/**
 * Database connection for XAMPP (Apache + MySQL + phpMyAdmin).
 *
 * Default XAMPP MySQL settings: host "localhost", user "root", blank password.
 * If you set a root password in phpMyAdmin, update $DB_PASS below to match.
 */

$DB_HOST = "localhost";
$DB_NAME = "bainevier_db";
$DB_USER = "root";
$DB_PASS = "";

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // Log the real error for you (check XAMPP's apache error log), but never
    // show database details to a visitor.
    error_log("Bainevier DB connection failed: " . $e->getMessage());
    http_response_code(500);
    die("Sorry, we couldn't connect to the database right now. Please make sure XAMPP's MySQL service is running and the 'bainevier_db' database has been imported, then try again.");
}
