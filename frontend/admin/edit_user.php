<?php
session_start();
require '../../backend/config/db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get the user ID from the URL
$user_id = $_GET['id'];

// Fetch the user's data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update the user's data
    $update_stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $update_stmt->execute([$name, $email, $role, $user_id]);

    // Redirect back to users list or show success message
    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
            /* Global Reset & Font Setup */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Hide scrollbar in WebKit browsers */
    ::-webkit-scrollbar {
      display: none;
    }

    body {
      font-family: 'Playfair Display', serif;
      background-color: #f9fafa;
      color: #333;
      line-height: 1.6;
    }

    /* Header */
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
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      color: #fff;
      margin: 0;
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

    /* Hero Section */
    .hero {
      height: 100vh;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      transition: background-image 1s ease-in-out;
    }

    .hero-overlay {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      max-width: 500px;
      color: #fff;
    }

    .hero h2 {
      font-family: 'Playfair Display', serif;
      font-size: 36px;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 18px;
      margin-bottom: 20px;
    }

    /* Login Form Styles */
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }

    .hero input,
    .hero select {
      padding: 10px;
      width: 250px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      background-color: rgba(0, 0, 0, 0.3);
      color: white;
      font-family: 'Playfair Display', serif;

    }

    .hero button {
      padding: 10px 20px;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
      font-family: 'Playfair Display', serif;

    }

    .hero button:hover {
      background-color: rgba(210, 210, 210, 0.6);
    }
    </style>
</head>
<body>
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
        <div class="hero-overlay">
    <div class="form-container">
        <h2>Edit User Information</h2>
        <form action="edit_user.php?id=<?= $user['id'] ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="traveler" <?= $user['role'] == 'traveler' ? 'selected' : '' ?>>Traveler</option>
                <option value="operator" <?= $user['role'] == 'operator' ? 'selected' : '' ?>>Operator</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>    
    </div>
    </section>

    
    <script>
      const heroSection = document.querySelector('.hero');
      const images = [
        '/image/bora1.jpg',
        '/image/Kawasan Falls.jpg'
      ];

      let current = 0;

      function changeBackground() {
        heroSection.style.backgroundImage = `url('${images[current]}')`;
        current = (current + 1) % images.length;
      }

      changeBackground(); 
      setInterval(changeBackground, 4000);
    </script>
</body>
</html>
