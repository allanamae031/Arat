<?php
session_start();
require_once '../../backend/config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['booking_id'])) {
    die("No booking ID provided.");
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

try {
    // Update the status to 'cancelled' only if the booking belongs to the logged-in user
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $user_id]);

    header("Location: bookings.php");
    exit;
} catch (PDOException $e) {
    die("Error cancelling booking: " . $e->getMessage());
}
?>
