<?php
session_start();
require_once '../../backend/config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your bookings.";
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            b.id AS booking_id,
            u.name AS user_name,
            b.booking_date,
            b.payment_method,
            b.status,
            d.name AS destination_name,
            d.itinerary,
            d.flight_info,
            d.hotel,
            d.start_date,
            d.end_date,
            d.price,
            b.num_travelers,  -- Fetch the number of travelers
            d.price * b.num_travelers AS total_price,  -- Calculate the total price (price * num_travelers)
            d.image_url
        FROM bookings b
        JOIN destinations d ON b.package_id = d.id
        JOIN users u ON b.user_id = u.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Bookings</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Playfair Display', serif;
      background-image: url('/image/arat1.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: #fff;
    }
    /* Header */
  header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 40px; /* Adjusted padding */
    display: flex;
    align-items: center;  /* Center vertically */
    z-index: 10;
    background: rgba(0, 0, 0, 0.4);
  }

  header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #fff;
    margin: 0;  /* Remove default margin */
  }

  nav {
    position: absolute;  /* Position the navigation items */
    right: 40px;  /* Right-aligned navigation links */
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

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .container {
      margin-top: 5%;
      color: rgba(255, 255, 255, 0.67);
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 1000px;
      overflow-y: auto;
    }

    h1 {
      font-size: 32px;
      margin-bottom: 10px;
      color:rgb(255, 255, 255);
    }

    p {
      margin: 10px 0;
      line-height: 1.6;
    }

    .booking-item {
      background: rgba(0, 0, 0, 0.4);
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .booking-item h3 {
      margin-bottom: 10px;
      color:rgb(255, 255, 255);
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 24px;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: rgba(255, 255, 255, 0.4);
    }

    .status {
      font-weight: bold;
    }

    .status.pending {
      color:rgba(243, 157, 18, 0.76);
    }

    .status.paid {
      color:rgba(56, 142, 60, 0.72);
    }

    .status.cancelled {
      color:rgba(231, 77, 60, 0.78);
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 26px;
      }

      .btn {
        width: 100%;
        text-align: center;
      }
    }
  </style>
</head>
<body>
    <section class="hero">
        <header>
            <h1>Arat</h1>
            <nav>
              <a href="dashboard.php">Home</a>
              <a href="destinations.php">Destinations</a>
              <a href="bookings.php">Bookings</a>
              <a href="contact.php">Contact</a>
              <a href="/backend/auth/logout.php">Logout</a>
            </nav>
        </header>
    </section>
  <div class="overlay">
    <div class="container">
      <center><h1>Your Bookings</h1></center>

      <?php if (count($bookings) === 0): ?>
        <p style="text-align: center;">You have no bookings yet.</p>
      <?php else: ?>
        <?php foreach ($bookings as $booking): ?>
          <div class="booking-item">
            <h3><?= htmlspecialchars($booking['destination_name']) ?></h3>
            <p><strong>Booked By:</strong> <?= htmlspecialchars($booking['user_name']) ?></p>
            <p><strong>Booking Date:</strong> <?= htmlspecialchars($booking['booking_date']) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($booking['payment_method']) ?></p>
            <p><strong>Status:</strong> <span class="status <?= strtolower($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></p>
            <p><strong>Flight Info:</strong> <?= htmlspecialchars($booking['flight_info'] ?? 'Not specified') ?></p>
            <p><strong>Hotel:</strong> <?= htmlspecialchars($booking['hotel'] ?? 'Not specified') ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date'] ?? 'N/A') ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date'] ?? 'N/A') ?></p>
            <p><strong>Total Price (₱):</strong> <?= number_format($booking['total_price'], 2) ?></p>

            <?php if ($booking['status'] === 'pending'): ?>
              <a href="pay_now.php?booking_id=<?= $booking['booking_id'] ?>" class="btn">Pay Now</a>
              <a href="cancel_confirmation.php?booking_id=<?= $booking['booking_id'] ?>" class="btn">Cancel Booking</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
