<?php
/**
 * Saves a Contact Us / quote-request submission to MySQL.
 * Called via fetch() from script.js, in parallel with the existing
 * WhatsApp hand-off — so a database hiccup never blocks the WhatsApp message.
 */
header('Content-Type: application/json');
require __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$fullName = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$date     = trim($_POST['date'] ?? '');
$service  = trim($_POST['service'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($fullName === '' || $phone === '' || $service === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Only pass a date through if one was actually picked
$preferredDate = $date !== '' ? $date : null;
$messageValue  = $message !== '' ? $message : null;

$stmt = $pdo->prepare(
    "INSERT INTO contact_messages (full_name, phone, preferred_date, service, message) VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([$fullName, $phone, $preferredDate, $service, $messageValue]);

echo json_encode(['success' => true]);
