<?php
session_start();
require '../../backend/config/db.php';

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get user ID from query string
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch bookings for the specific user
    $stmt = $pdo->prepare("SELECT bookings.*, destinations.name AS destination_name, destinations.price 
                           FROM bookings
                           INNER JOIN destinations ON bookings.package_id = destinations.id
                           WHERE bookings.user_id = ?");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    echo "Invalid user ID.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Bookings</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    ::-webkit-scrollbar {
      display: none;
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
      position: relative;
    }

    .overlay {
      position: relative;
      z-index: 1;
      padding: 100px 40px 40px;
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
    }

    header {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      padding: 10px 40px;
      display: flex;
      align-items: center;
      z-index: 1000;
      background: rgba(0, 0, 0, 0.4);
    }

    header h1 {
      font-size: 32px;
      color: #fff;
    }

    nav {
      margin-left: auto;
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

    h2 {
      font-size: 32px;
      color:rgb(255, 255, 255);
      margin-bottom: 20px;
      text-align: center;
    }

    .filter {
      margin-bottom: 30px;
      color: white;
      text-align: right;
    }

    .filter select {
      color: white;
      padding: 8px;
      font-size: 16px;
      border-radius: 5px;
      text-align: center;
      background-color: rgba(0, 0, 0, 0.4);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    th, td {
      padding: 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background-color:rgba(0, 0, 0, 0.1);
      color: white;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background-color:rgba(0, 0, 0, 0.5);
    }

    td {
      color: white;
    }

    h1{
        color: white;
        text-align: center;
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


        <h1>User Bookings</h1>

        <?php if (empty($bookings)): ?>
            <p>No bookings found for this user.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking Date</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Payment Method</th>
                        <th>Special Requests</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                            <td><?= htmlspecialchars($booking['destination_name']) ?></td>
                            <td><?= htmlspecialchars($booking['price']) ?></td>
                            <td><?= htmlspecialchars($booking['payment_method']) ?></td>
                            <td><?= htmlspecialchars($booking['special_requests']) ?></td>
                            <td><?= htmlspecialchars($booking['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
</div>


</body>
</html>
