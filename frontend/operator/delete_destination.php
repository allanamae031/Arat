<?php
require '../../backend/config/db.php'; // adjust path if needed

// Check if ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No destination ID provided.";
    exit;
}

$destination_id = $_GET['id'];

// Check if the destination exists
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$destination_id]);
$destination = $stmt->fetch();

if (!$destination) {
    echo "Destination not found.";
    exit;
}

// Proceed to delete the destination
try {
    $stmt = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
    $stmt->execute([$destination_id]);

    // Redirect to destinations page after successful deletion
    header("Location: destinations.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
