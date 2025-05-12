<?php
session_start();
require '../config/db.php';  // Ensure this is the correct path to db.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role']; // 'traveler', 'operator', or 'admin'

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        header("Location: success.php"); // or whatever page you want to redirect to
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arat pre gala tayo</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">

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
      background-color: #fff;
      color: #333;
      font-family: 'Playfair Display', serif;
      background: rgba(0, 0, 0, 0.5);
      color: white;
    }

    .hero select {
      -webkit-appearance: none;  /* Removes default dropdown styling in some browsers */
      -moz-appearance: none;
      appearance: none;
      background-color: #fff;
      background-image: url('data:image/svg+xml;utf8,<svg fill="none" height="10" width="10" xmlns="http://www.w3.org/2000/svg"><path d="M5 0L10 5H0L5 0Z" fill="#333"/></svg>');
      background-position: right 10px center;
      background-repeat: no-repeat;
      padding-right: 30px;
      background: rgba(0, 0, 0, 0.5);
    }

    .hero button {
      padding: 10px 20px;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
      font-family: 'Playfair Display', serif;

    }

    .hero button:hover {
      background-color: #00796b;
    }

    /* Error message style */
    .error-message {
      color: #ff4d4d;
      font-size: 16px;
      margin-top: 10px;
    }

    /* Register Link */
    a {
      color: #8adfe1;
      text-decoration: none;
      font-size: 16px;
      margin-top: 10px;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>   
</head>
<body>
  <section class="hero">
    <header>
      <h1>Arat</h1>
      <nav>
        <a href="/index.php">Home</a>
        <a href="/destinations.php">Destinations</a>
        <a href="/contact.html">Contact</a>
        <a href="login.php">Login</a>
      </nav>
    </header>

    <div class="hero-overlay">
        <form action="register.php" method="POST">
            <h3><b>Register an Account</b></h3>   
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="traveler">Traveler</option>
                <option value="operator">Operator</option>
            </select>
            <button type="submit">Register</button>
        </form>
        Already have an account? <a href="login.php">Login here.</a>
    </div>

    <script>
  function searchDestinations() {
    const searchTerm = document.getElementById('searchInput').value;
    window.location.href = 'destinations.php?search=' + encodeURIComponent(searchTerm); // Redirect with search query
  }

  const heroSection = document.querySelector('.hero');
  const images = [   '/image/bora1.jpg', 
                     '/image/arat1.jpg', 
                     '/image/arat9.jpg', 
                     '/image/arat11.jpg',                 
                     '/image/Kawasan Falls.jpg'                    
                    ];

  let current = 0;

  function changeBackground() {
    heroSection.style.backgroundImage = `url('${images[current]}')`;
    current = (current + 1) % images.length;
  }

  changeBackground(); 
  setInterval(changeBackground, 8000);
  </script> 
  </section>
</body>
</html>
