<?php
session_start();
require __DIR__ . '/db_connect.php';

function back_to_signup($errorCode) {
    header("Location: signup.html?error=" . urlencode($errorCode));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.html");
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

// Basic validation
if ($fullName === '' || $email === '' || $phone === '' || $password === '') {
    back_to_signup('missing');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_to_signup('email');
}
if (strlen($password) < 6) {
    back_to_signup('weak');
}
if ($password !== $confirm) {
    back_to_signup('mismatch');
}

// Make sure this email hasn't already signed up
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    back_to_signup('exists');
}

// Never store plain-text passwords — hash before saving
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)"
);
$stmt->execute([$fullName, $email, $phone, $passwordHash]);

// Log the new user in immediately
$_SESSION['user_id']   = $pdo->lastInsertId();
$_SESSION['user_name'] = $fullName;

header("Location: welcome.php");
exit;
