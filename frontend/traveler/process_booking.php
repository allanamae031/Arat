<?php
session_start();
require '../../backend/config/db.php';

// Make sure all required fields are set
if (!isset($_POST['full_name'], $_POST['email'], $_POST['package_id'], $_POST['payment_method'], $_POST['num_travelers'])) {
    die("Missing required fields.");
}

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$package_id = $_POST['package_id'];
$payment_method = $_POST['payment_method'];
$num_travelers = (int)$_POST['num_travelers'];
$special_requests = $_POST['special_requests'] ?? '';

// Validate input
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}
if ($num_travelers < 1) {
    die("Number of travelers must be at least 1.");
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Fetch package price
$stmt = $pdo->prepare("SELECT price FROM destinations WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("Invalid package.");
}

$price_per_person = $package['price'];
$total_price = $price_per_person * $num_travelers;

try {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, package_id, booking_date, payment_method, special_requests, num_travelers, total_price, status)
        VALUES (?, ?, NOW(), ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $user_id,
        $package_id,
        $payment_method,
        $special_requests,
        $num_travelers,
        $total_price
    ]);

    header("Location: booking_success.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
