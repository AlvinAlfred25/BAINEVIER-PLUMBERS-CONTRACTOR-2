<?php
session_start();
require __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: login.html?error=missing");
    exit;
}

$stmt = $pdo->prepare("SELECT id, full_name, password_hash FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    header("Location: login.html?error=invalid");
    exit;
}

$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['full_name'];

header("Location: welcome.php");
exit;
