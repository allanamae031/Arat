<?php
session_start();
require '../../backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    echo "Access denied.";
    exit;
}

$operator_id = $_SESSION['user_id'];
$status_filter = $_GET['status'] ?? '';

$query = "
    SELECT b.*, u.name AS user_name, u.email, d.name AS destination_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN destinations d ON b.package_id = d.id
    WHERE d.operator_id = ?
";

$params = [$operator_id];

if ($status_filter) {
    $query .= " AND b.status = ?";
    $params[] = $status_filter;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Operator Bookings</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Playfair Display', serif;

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
  </style>
</head>
<body>
  <header>
    <h1>Arat</h1>
    <nav>
      <a href="dashboard.php">Home</a>
      <a href="destinations.php">Destinations</a>
      <a href="bookings.php">Bookings</a>
      <a href="/backend/auth/logout.php">Logout</a>
    </nav>
  </header>

  <div class="overlay">
    <h2>Your Bookings</h2>

    <div class="filter">
      <form method="GET">
        <label for="status">Filter by Status:</label>
        <select name="status" onchange="this.form.submit()">
          <option value="">-- All --</option>
          <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="paid" <?= $status_filter === 'paid' ? 'selected' : '' ?>>Paid</option>
          <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Destination</th>
          <th>Status</th>
          <th>Booking Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($bookings) === 0): ?>
          <tr><td colspan="5">No bookings found.</td></tr>
        <?php else: ?>
          <?php foreach ($bookings as $booking): ?>
            <tr>
              <td><?= htmlspecialchars($booking['user_name']) ?></td>
              <td><?= htmlspecialchars($booking['email']) ?></td>
              <td><?= htmlspecialchars($booking['destination_name']) ?></td>
              <td><?= htmlspecialchars($booking['status']) ?></td>
              <td><?= htmlspecialchars($booking['booking_date']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
