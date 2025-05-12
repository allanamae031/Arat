<?php
session_start();
require_once '../../backend/config/db.php';

// Get booking ID from URL
$booking_id = $_GET['booking_id'] ?? null;

// Redirect if booking_id is missing
if (!$booking_id) {
    header("Location: bookings.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cancellation of Booking</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Playfair Display', serif;
      background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: #fff;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .message-container {
      background: rgba(0, 0, 0, 0.3);
      color: #333;
      padding: 40px 30px;
      border-radius: 15px;
      max-width: 600px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    h2 {
      font-size: 30px;
      color: #fff;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 30px;
      color: #fff;
    }

    a {
      display: inline-block;
      padding: 12px 24px;
      background-color: #009688;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      margin: 0 10px;
      transition: background-color 0.3s;
    }

    a:hover {
      background-color: #00796b;
    }

    @media (max-width: 600px) {
      h2 {
        font-size: 24px;
      }

      p {
        font-size: 16px;
      }

      a {
        width: 100%;
        margin-bottom: 10px;
        display: block;
      }
    }
  </style>
</head>
<body>

  <div class="overlay">
    <div class="message-container">
      <h2>Are you sure you want to cancel this booking?</h2>
      <a href="cancel_booking.php?booking_id=<?= urlencode($booking_id) ?>">Yes, Cancel</a>
      <a href="bookings.php">No, Go Back</a>
    </div>
  </div>

</body>
</html>
