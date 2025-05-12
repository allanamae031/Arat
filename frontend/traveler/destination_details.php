<?php
require '../../backend/config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No destination ID provided.";
    exit;
}

$destination_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$destination_id]);
$destination = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$destination) {
    echo "Destination not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($destination['name']) ?> - Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Playfair Display', serif;
      background-image: url('<?= htmlspecialchars($destination['image_url']) ?>');
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

    .container {
      background: rgba(0, 0, 0, 0.3);
      color: rgba(255, 255, 255, 0.78);
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 800px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    img {
      width: 100%;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 32px;
      margin-bottom: 10px;
      color: white;
      text-align: center;
    }

    p {
      margin: 10px 0;
      line-height: 1.6;
    }

    strong {
      color: white;
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 24px;
      background-color: rgba(0,0,0,0.3);
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: rgba(255, 255, 255, 0.78);
      color: black;
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
  <div class="overlay">
    <div class="container">
      <img src="<?= htmlspecialchars($destination['image_url']) ?>" alt="<?= htmlspecialchars($destination['name']) ?>">

      <h1><?= htmlspecialchars($destination['name']) ?></h1>

      <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($destination['description'])) ?></p>
      <p><strong>Itinerary:</strong> <?= nl2br(htmlspecialchars($destination['itinerary'] ?? 'Not available')) ?></p>
      <p><strong>Flight:</strong> <?= htmlspecialchars($destination['flight_info'] ?? 'Not specified') ?></p>
      <p><strong>Hotel:</strong> <?= htmlspecialchars($destination['hotel'] ?? 'Not specified') ?></p>
      <p><strong>Start Date:</strong> <?= htmlspecialchars($destination['start_date'] ?? 'N/A') ?></p>
      <p><strong>End Date:</strong> <?= htmlspecialchars($destination['end_date'] ?? 'N/A') ?></p>
      <p><strong>Total Price:</strong> ₱<?= number_format($destination['price'] ?? 0, 2) ?></p>

      <a href="booking_form.php?package_id=<?= $destination['id'] ?>" class="btn">Proceed to Booking</a>
      <a href="destinations.php" class="btn">Go back</a>

    </div>
  </div>
</body>
</html>
