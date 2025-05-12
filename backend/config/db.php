<?php
$host = 'localhost';
$db = 'tourism_booking';  // Make sure the DB name is correct
$user = 'root';           // XAMPP default
$pass = '';               // XAMPP default (no password for 'root')
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
