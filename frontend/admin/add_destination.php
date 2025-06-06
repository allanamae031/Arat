<?php
require '../../backend/config/db.php';
session_start();

$operator_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $itinerary = $_POST['itinerary'] ?? '';
    $flight_info = $_POST['flight_info'] ?? '';
    $hotel = $_POST['hotel'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $price = $_POST['price'] ?? 0;

    if ($name && $description && $operator_id) {
        $stmt = $pdo->prepare("INSERT INTO destinations (name, description, image_url, itinerary, flight_info, hotel, start_date, end_date, price, operator_id)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $image_url, $itinerary, $flight_info, $hotel, $start_date, $end_date, $price, $operator_id]);
        header('Location: destinations.php');
        exit;
    } else {
        $error = "Name, description, and valid operator session are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Destination</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    a{
        text-decoration: none;
    }

    ::-webkit-scrollbar {
      display: none;
    }

    body {
      font-family: 'Playfair Display', serif;
      background-image: url('/image/bora1.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-repeat: no-repeat;
      color: #333;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
      padding: 80px 20px 40px;
    }

    .form-container {
      max-width: 700px;
      margin: auto;
      background-color: rgba(0, 0, 0, 0.3);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: white;
      font-size: 32px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: white;
    }

    input[type="text"],
    input[type="date"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-family: 'Playfair Display', serif;
      font-size: 15px;
      transition: border-color 0.3s;
      background: rgba(0, 0, 0, 0.1);
      color: white;
    }

    input:focus,
    textarea:focus {
      border-color: white;
      outline: none;
    }

    textarea {
      resize: vertical;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color:rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-family: 'Playfair Display', serif;

    }

    .btn:hover {
      background-color: rgba(180, 180, 180, 0.5);
    }

    .error {
      color: red;
      margin-bottom: 15px;
      text-align: center;
      font-size: 15px;
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="form-container">
      <h2>Add New Destination</h2>

      <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST">
        <label>Destination Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Image URL:</label>
        <input type="text" name="image_url">

        <label>Itinerary:</label>
        <textarea name="itinerary" rows="3"></textarea>

        <label>Flight Info:</label>
        <input type="text" name="flight_info">

        <label>Hotel:</label>
        <input type="text" name="hotel">

        <label>Start Date:</label>
        <input type="date" name="start_date">

        <label>End Date:</label>
        <input type="date" name="end_date">

        <label>Price (₱):</label>
        <input type="number" step="0.01" name="price">

        <button type="submit" class="btn">Add Destination</button> <br>
        <center><a href="destinations.php" class="btn">Go back</a></center>
        
        </form>
    </div>
  </div>
</body>
</html>
