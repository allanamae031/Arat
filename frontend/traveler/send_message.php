<?php
require '../../backend/config/db.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);

        echo "<script>alert('Your message has been sent successfully!'); window.location.href = 'contact.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Failed to send your message.'); window.location.href = 'contact.php';</script>";
    }
} else {
    header("Location: contact.php");
    exit;
}
