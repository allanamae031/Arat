<?php
session_start();
require '../../backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    echo "Access denied.";
    exit;
}

$operator_id = $_SESSION['user_id'];

$stmt = $pdo->query("SELECT * FROM destinations");
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Destinations</title>
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

    .card .btn {
      display: inline-block;
      background-color: #009688;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      padding: 10px 15px;
      font-weight: bold;
      margin: 10px 15px;
      transition: background-color 0.3s;
      text-align: center;
    }

    .card .btn:hover {
      background-color: #00796b;
    }

    .add-card {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      color: #009688;
      border: 2px dashed #009688;
      background-color: #f0fefc;
      cursor: pointer;
      transition: all 0.3s;
      height: 480px;
      width: 320px;
      border-radius: 12px;
      text-align: center;
    }

    .add-card p{
      color: #00796b;
    }

    .add-card:hover {
      background-color: #e0fbf9;
    }

    .btn-group {
  display: flex;
  flex-direction: column;
  margin: 0 15px 15px;
}

.btn-group .btn {
  margin: 5px 0;
}

.overlay {
      background-color: rgba(0, 0, 0, 0.6);
      height: 100%;
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
                <a href="/backend/auth/logout.php">Logout</a>
            </nav>
        </header>
</section>

<div class="overlay">
  <br><br><br>
<h2>Your Destinations</h2>
<p>Manage the destinations you’ve added.</p>

<section class="destination-cards">
  <?php foreach ($destinations as $dest): ?>
    <?php if ($dest['operator_id'] == $operator_id): ?>
      <div class="card">
        <img src="<?= htmlspecialchars($dest['image_url']) ?>" alt="<?= htmlspecialchars($dest['name']) ?>">
        <h3><?= htmlspecialchars($dest['name']) ?></h3>
        <p><?= htmlspecialchars($dest['description']) ?>...</p>
        <div class="btn-group">
          <a href="edit_destination.php?id=<?= $dest['id'] ?>" class="btn">Edit</a>
          <a href="delete_destination.php?id=<?= $dest['id'] ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this destination?');">Delete</a>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

  <div class="add-card" onclick="location.href='add_destination.php'">
    <div>
      <div style="font-size: 50px;">&#43;</div>
      <p>Add New Destination</p>
    </div>
  </div>
</div>

</section>

</body>
</html>
