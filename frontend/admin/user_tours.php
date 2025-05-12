<?php
session_start();
require '../../backend/config/db.php';

// Ensure admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid operator ID.";
    exit;
}

$operator_id = $_GET['id'];

// Fetch operator info
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ? AND role = 'operator'");
$stmt->execute([$operator_id]);
$operator = $stmt->fetch();

if (!$operator) {
    echo "Operator not found.";
    exit;
}

// Fetch destinations added by this operator
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE operator_id = ?");
$stmt->execute([$operator_id]);
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($operator['name']) ?>'s Tours</title>
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
            background-image: url('/image/bora1.jpg'); /* adjust path as needed */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            color: #333;
            line-height: 1.6;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            z-index: 10;
            background: rgba(0, 0, 0, 0.4);
        }
        header h1 {
            font-size: 32px;
            color: #fff;
        }
        nav {
            position: absolute;
            right: 40px;
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
      text-align: center;
      margin: 40px 0 10px;
      color:rgb(255, 255, 255);
      font-size: 32px;
    }

    p {
      text-align: center;
      margin-bottom: 30px;
      color: white;
    }

    .destination-cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 0 20px 40px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 320px;
      height: 480px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.3s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card h3 {
      font-size: 22px;
      color: #009688;
      margin: 15px;
      text-align: center;
    }

    .card p {
      margin: 0 15px;
      color: #666;
      font-size: 15px;
      flex-grow: 1;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      text-align: center;
    }
    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      height: 100%;
    }

    h1{
        color: white;
        text-align: center;
    }
    </style>
</head>
<body>
<div class="overlay">
    <section class="hero">
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
    </section>


    <br><br><br><br>
    <h1>Tours by <?= htmlspecialchars($operator['name']) ?></h1>
    <br>
        <div class="destination-cards">
            <?php if (count($destinations) === 0): ?>
                <p style="text-align:center;">No destinations found for this operator.</p>
            <?php endif; ?>

            <?php foreach ($destinations as $dest): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($dest['image_url']) ?>" alt="<?= htmlspecialchars($dest['name']) ?>">
                    <h3><?= htmlspecialchars($dest['name']) ?></h3>
                    <p><?= htmlspecialchars($dest['description']) ?></p>
                    <p><strong>Price:</strong> ₱<?= number_format($dest['price'], 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>


</div>


</body>
</html>
