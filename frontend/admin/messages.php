<?php
session_start();
require '../../backend/config/db.php';

// Only allow access for admin users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Fetch all messages
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Users</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Playfair Display', serif;
      background-image: url('/image/bora1.jpg');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      color: #333;
      line-height: 1.6;
    }

    header {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      padding: 10px 40px;
      display: flex;
      align-items: center;
      z-index: 10;
      background: rgba(0, 0, 0, 0.4);
    }

    header h1 {
      font-size: 32px;
      color: #fff;
    }

    nav {
      position: absolute;
      right: 40px;
      display: flex;
      align-items: center;
    }

    nav a {
      margin-left: 20px;
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      font-size: 16px;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #00b4b6;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

    h2 {
      color:rgb(255, 255, 255);
      margin-bottom: 20px;
      text-align: center;
    }

    .message-card {
      background: rgba(0,0,0,0.5);
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .message-card h3 {
      margin: 0 0 10px;
      font-size: 20px;
      color: white;
    }

    .message-card p {
      margin: 5px 0;
      color: white;
    }

    .message-card small {
      display: block;
      margin-top: 10px;
      color: #888;
      font-size: 13px;
    }


    .overlay{
        background-color: rgba(0, 0, 0, 0.6);
        padding: 80px 20px 40px;
        min-height: 100vh;
    }

  </style>
</head>
<body>

<div class="overlay">

      <header>
        <h1>Arat</h1>
        <nav>
            <a href="dashboard.php">Home</a>
            <a href="users.php">Users</a>
            <a href="destinations.php">Destinations</a>
            <a href="bookings.php">Bookings</a>
            <a href="messages.php">Messages</a>
            <a href="/backend/auth/logout.php">Logout</a>
        </nav>
      </header>


    <div class="container">
        <h2>Contact Messages</h2>

        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
            <div class="message-card">
                <h3><?= htmlspecialchars($msg['subject']) ?></h3>
                <p><strong>From:</strong> <?= htmlspecialchars($msg['name']) ?> (<?= htmlspecialchars($msg['email']) ?>)</p>
                <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                <small>Received on: <?= htmlspecialchars($msg['created_at']) ?></small>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No messages to display.</p>
        <?php endif; ?>
    </div>
</div>


</body>
</html>
