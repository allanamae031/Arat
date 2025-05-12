<?php
session_start();
require '../../backend/config/db.php';

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the admin has updated the status of a user
if (isset($_GET['toggle_status'])) {
    $user_id = $_GET['toggle_status'];
    $stmt = $pdo->prepare("UPDATE users SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Users</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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
      color: rgb(255, 255, 255);
      font-size: 32px;
    }

    p {
      text-align: center;
      margin-bottom: 30px;
      color: white;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.3);
      padding: 80px 20px 40px;
      min-height: 100vh;
    }

    .table-container {
      width: 80%;
      margin: 0 auto;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
      text-align: center;
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

    .btn-group a {
      display: inline-block;
      background-color:rgba(0, 0, 0, 0.3);
      color: white;
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-group a:hover {
      background-color:rgba(255, 255, 255, 0.26);    
    }


    .overlay{
        background-color: rgba(0, 0, 0, 0.6);
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

    <h2>Manage Users</h2>
    <p>Admin panel to manage all registered users.</p>

    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= ucfirst(htmlspecialchars($user['role'])) ?></td>
                <td><?= ucfirst(htmlspecialchars($user['status'])) ?></td>
                <td class="btn-group">
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn">Edit</a>
                    <a href="?toggle_status=<?= $user['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to toggle this user\'s status?')">
                        <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                    </a>
                    <?php if ($user['role'] === 'traveler'): ?>
                        <a href="user_bookings.php?user_id=<?= $user['id'] ?>" class="btn">View Bookings</a>
                    <?php elseif ($user['role'] === 'operator'): ?>
                        <a href="user_tours.php?id=<?= $user['id'] ?>" class="btn">View Tours</a>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
  </div>

</body>
</html>
