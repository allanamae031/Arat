<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../backend/config/db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get the user ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "User ID is missing.";
    exit;
}

$user_id = $_GET['id'];

// Fetch the user's data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Toggle the user's status (active -> inactive or inactive -> active)
$new_status = ($user['status'] == 'active') ? 'inactive' : 'active';

// Update the user's status in the database
$update_stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
$update_stmt->execute([$new_status, $user_id]);

// Redirect back to the users list
header("Location: users.php");
exit;
?>
