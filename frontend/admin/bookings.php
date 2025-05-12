<?php
session_start();
require '../../backend/config/db.php';

$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
  $bookingId = $_POST['booking_id'];
  $newStatus = $_POST['status'];

  $updateStmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
  $updateStmt->execute([$newStatus, $bookingId]);

  // Optional: Redirect to avoid form resubmission on refresh
  header("Location: bookings.php?status=" . urlencode($status_filter));
  exit;
}


// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// Initialize filter variables
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build the WHERE clause dynamically based on filters
$where_clauses = [];
$params = [];

if ($status_filter) {
    $where_clauses[] = "b.status = ?";
    $params[] = $status_filter;
}

// Construct the final query with the filters
$query = "SELECT b.*, u.name AS user_name, d.name AS destination_name
          FROM bookings b
          JOIN users u ON b.user_id = u.id
          JOIN destinations d ON b.package_id = d.id";

// Add the WHERE clause if any filters are applied
if (count($where_clauses) > 0) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}

$query .= " ORDER BY b.booking_date DESC";

// Prepare and execute the query with parameters
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All User Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
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

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                align-items: center;
            }

            .content {
                padding: 20px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="overlay">
    <section class="hero">
        <header>
            <h1>All User Bookings</h1>
            <nav>
              <a href="dashboard.php">Home</a>
              <a href="users.php">Users</a>
              <a href="destinations.php">Destinations</a>
              <a href="bookings.php">Bookings</a>
              <a href="messages.php">Messages</a>
              <a href="/backend/auth/logout.php">Logout</a>
            </nav>
        </header>
    </section>

    <div class="content">
        <h2>All Bookings</h2>

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


        <!-- Booking Table -->
        <?php if (count($bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Destination</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($bookings as $booking): ?>
                      <tr>
                          <td><?= htmlspecialchars($booking['user_name']) ?></td>
                          <td><?= htmlspecialchars($booking['destination_name']) ?></td>
                          <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                          <td>
                              <?php if ($edit_id == $booking['id']): ?>
                                  <div class="filter">
                                  <form method="POST" style="display: flex; align-items: center; gap: 2px;">
                                      <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                      <select name="status" style="padding: 4px; border-radius: 4px;">
                                          <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                          <option value="paid" <?= $booking['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                          <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                      </select>
                                      <button type="submit" name="update_status" style="padding: 4px 8px; border-radius: 4px; background-color: rgba(255, 255, 255, 0.28); color: white; cursor: pointer;">Save</button>
                                  </form>
                                  </div>
                              <?php else: ?>
                                  <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                              <?php endif; ?>
                          </td>

                          <td>
                              <?php if ($edit_id == $booking['id']): ?>
                                  <a href="bookings.php" style="color:rgba(217, 217, 217, 0.49);">Cancel</a>
                              <?php else: ?>
                                  <a href="bookings.php?edit_id=<?= $booking['id'] ?>" style="color:rgba(89, 255, 108, 0.49);">Edit</a>
                              <?php endif; ?>|
                              <a href="delete_booking.php?id=<?= $booking['id'] ?>" style="color:rgba(255, 13, 61, 0.49);" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                          </td>
                      </tr>
                  <?php endforeach; ?>
                </tbody>

            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
