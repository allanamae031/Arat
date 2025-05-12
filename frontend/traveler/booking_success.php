<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Successful</title>
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
      background-image: url('/image/arat10.jpg'); /* Use your own image if needed */
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
      color:rgb(255, 255, 255);
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 30px;
      color: #555;
      color: white;
    }

    a {
      display: inline-block;
      padding: 12px 24px;
      background-color:rgba(0, 0, 0, 0.3);
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
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
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <div class="overlay">
    <div class="message-container">
      <h2>Your Booking Was Successful!</h2>
      <p>Thank you for booking with us. A confirmation email will be sent to you shortly.</p>
      <a href="dashboard.php">Go to Dashboard</a>
    </div>
  </div>

</body>
</html>
