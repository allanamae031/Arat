<?php
// Get package ID from the URL
$package_id = $_GET['package_id'] ?? null;

if (!$package_id) {
    die("Package ID is missing.");
}

// Get package details from the database
require '../../backend/config/db.php';

$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("Package not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book <?= htmlspecialchars($package['name']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body, html {
      height: 100%;
      font-family: 'Playfair Display', serif;
      background-image: url('<?= htmlspecialchars($package['image_url']) ?>');
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

    .form-container {
      background: rgba(0, 0, 0, 0.3);
      color: #333;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 600px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    h2 {
      color: white;
      font-size: 28px;
      margin-bottom: 20px;
      text-align: center;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: white;
    }

    input[type="text"],
    input[type="email"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      background: rgba(0, 0, 0, 0.3);
      color: white;
      font-family: 'Playfair Display', serif;

    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    .package-details {
      margin-top: 20px;
      padding: 15px;
      background:transparent;
      border-radius: 8px;
      color: white;
    }

    .package-details p {
      margin: 5px 0;
    }

    button[type="submit"] {
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      margin-top: 25px;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 100%;
      font-family: 'Playfair Display', serif;

    }

    button[type="submit"]:hover {
      background-color:rgba(255, 255, 255, 0.5);
    }

    .number-input {
  display: flex;
  align-items: center;
  margin-top: 5px;
}

.number-input button {
  background-color: rgba(0, 0, 0, 0.6);
  color: white;
  border: none;
  padding: 8px 14px;
  font-size: 18px;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.number-input button:hover {
  background-color: rgba(255, 255, 255, 0.5);
}

.number-input input[type="number"] {
  width: 60px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
  margin: 0 10px;
  padding: 8px;
}

input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Hide number input arrows for Firefox */
input[type=number] {
  -moz-appearance: textfield;
}


    @media (max-width: 600px) {
      h2 {
        font-size: 24px;
      }

      button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="overlay">
    <div class="form-container">
      <h2>Book <?= htmlspecialchars($package['name']) ?></h2>

      <form action="process_booking.php" method="POST">
        <input type="hidden" name="package_id" value="<?= htmlspecialchars($package['id']) ?>">

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <div class="package-details">
          <p><strong>Destination:</strong> <?= htmlspecialchars($package['name']) ?></p>
          <p><strong>Price per person:</strong> ₱<?= number_format($package['price'], 2) ?></p>
        </div>

        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
          <option value="">-- Select Payment Method --</option>
          <option value="credit_card">Credit/Debit Card</option>
          <option value="online_banking">Online Banking</option>
          <option value="e_wallet">E-wallet</option>
        </select>

        <label for="num_travelers">Number of Travelers:</label>
        <div class="number-input">
            <button type="button" onclick="adjustTravelers(-1)">−</button>
            <input type="number" id="num_travelers" name="num_travelers" min="1" value="1" required>
            <button type="button" onclick="adjustTravelers(1)">+</button>
        </div>

        <label for="special_requests">Special Requests (Optional):</label>
        <textarea name="special_requests" id="special_requests"></textarea>

        <button type="submit">Submit Booking</button>
      </form>
    </div>
  </div>

  <script>
        function adjustTravelers(amount) {
        const input = document.getElementById('num_travelers');
        let value = parseInt(input.value) || 1;
        value = Math.max(1, value + amount);
        input.value = value;
        }


        const pricePerPerson = <?= $package['price'] ?>;
        const numTravelersInput = document.getElementById('num_travelers');
        const totalPriceDisplay = document.getElementById('total_price');

        function updateTotalPrice() {
            const numTravelers = parseInt(numTravelersInput.value) || 1;
            const total = pricePerPerson * numTravelers;
            totalPriceDisplay.textContent = total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

    </script>

</body>
</html>
