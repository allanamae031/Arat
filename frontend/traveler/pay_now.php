<?php
session_start();
require_once '../../backend/config/db.php';

if (!isset($_SESSION['user_id'], $_GET['booking_id'])) {
    echo "Invalid request.";
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'];

// Fetch the booking details from the database
try {
    $stmt = $pdo->prepare("
        SELECT 
            b.id AS booking_id,
            u.name AS user_name,
            b.booking_date,
            b.payment_method,
            b.status,
            d.name AS destination_name,
            d.price
        FROM bookings b
        JOIN destinations d ON b.package_id = d.id
        JOIN users u ON b.user_id = u.id
        WHERE b.user_id = ? AND b.id = ?
    ");
    $stmt->execute([$user_id, $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        echo "Booking not found.";
        exit;
    }

    // Check if booking is already paid
    if ($booking['status'] === 'paid') {
        echo "This booking is already paid for.";
        exit;
    }

} catch (PDOException $e) {
    die("Error fetching booking details: " . $e->getMessage());
}

// Handle payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get payment method and user input
    $payment_method = $_POST['payment_method'];
    $account_number = $_POST['account_number'];
    $pin = $_POST['pin'];

    // For the sake of the mock, we'll just simulate the payment and update the status.
    // In a real scenario, you would validate the payment method and input details.

    try {
        // Update booking status to 'paid'
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'paid' WHERE id = ?");
        $stmt->execute([$booking_id]);

        echo "Payment successful! Booking is now paid.";
        // Redirect to booking confirmation or success page
        header('Location: payment_success.php');
        exit;

    } catch (PDOException $e) {
        echo "Error updating booking status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment for <?= htmlspecialchars($booking['destination_name']) ?></title>
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
        background-image: url('/image/arat7.jpg'); /* Use your own image if needed */
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #fff;
        }

        .overlay {
        background-color: rgba(0, 0, 0, 0.6);
        min-height: 100vh;
        display: absolute;
        justify-content: center;
        align-items: center;
        }

        h2 {
            text-align: center;
            color:rgba(255, 255, 255, 0.91);
            margin-bottom: 20px;
        }

        .form-container {
            background: rgba(0,0,0,0.3);
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: white;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background: rgba(0,0,0,0.5);
            color: white;
            font-family: 'Playfair Display', serif;

        }

        .form-container input[type="password"] {
            font-family: Arial, sans-serif;
            font-family: 'Playfair Display', serif;

        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: rgba(0,0,0,0.5);
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Playfair Display', serif;

        }

        .form-container button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .form-container p {
            text-align: center;
            color: #777;
            font-size: 14px;
        }

        .form-container .error {
            color: red;
            text-align: center;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="overlay">
    <br><br><br>
<h2>Payment for <?= htmlspecialchars($booking['destination_name']) ?></h2>

<div class="form-container">
    <form action="pay_now.php?booking_id=<?= $booking['booking_id'] ?>" method="POST">
        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Credit/Debit Card</option>
            <option value="online_banking">Online Banking</option>
            <option value="e_wallet">E-Wallet</option>
        </select>

        <label for="account_number">Account Number</label>
        <input type="text" name="account_number" id="account_number" required placeholder="Enter your account number">

        <label for="pin">PIN</label>
        <input type="password" name="pin" id="pin" required placeholder="Enter your PIN">

        <button type="submit">Pay Now</button>
    </form>

    <p class="error"><?= isset($error) ? $error : '' ?></p>
</div>
</div>


</body>
</html>
