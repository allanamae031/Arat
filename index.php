<?php
// Start the session to check user login and role
session_start();
require 'backend/config/db.php';  // Adjust the path to the actual location of db.php

// Fetch featured destinations (is_featured = 1)
$stmt = $pdo->query("SELECT * FROM destinations WHERE is_featured = 1");
$featured_destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
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
    padding: 10px 40px; /* Adjusted padding */
    display: flex;
    align-items: center;  /* Center vertically */
    z-index: 10;
    background: rgba(0, 0, 0, 0.4);
  }

  header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #fff;
    margin: 0;  /* Remove default margin */
  }

  nav {
    position: absolute;  /* Position the navigation items */
    right: 40px;  /* Right-aligned navigation links */
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
  
  .hero input[type="text"] {
    padding: 10px;
    width: 250px;
    border: none;
    border-radius: 5px;
    margin-right: 10px;
    font-size: 16px;
    font-family: 'Playfair Display', serif;

  }
  
  .hero button {
    padding: 10px 20px;
    background-color: #009688;
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
  
  /* Featured Section */
  .featured {
    padding: 60px 20px;
    text-align: center;
    background-color: #fff;
  }
  
  .featured h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    margin-bottom: 40px;
    color: #222;
  }
  
  /* Cards Scroll Area */
  .cards-wrapper {
    overflow-x: auto;
    padding: 0 20px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }
  
  .cards-wrapper::-webkit-scrollbar {
    display: none;
  }
  
  .cards {
    display: flex;
    gap: 20px;
    flex-wrap: nowrap;
  }
  
  /* Card Styles */
  .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    width: 280px;
    flex: 0 0 auto;
    scroll-snap-align: start;
    transition: transform 0.3s;
  }
  
  .card:hover {
    transform: translateY(-5px);
  }
  
  .card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }
  
  .card h4 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    margin: 15px;
    color: #009688;
  }
  
  .card p {
    margin: 0 15px 15px;
    color: #666;
    font-size: 15px;
  }
  
  /* Footer */
  footer {
    background-color: #222;
    color: #fff;
    text-align: center;
    padding: 20px;
    font-size: 14px;
  }
    /* Cards Wrapper */
    .cards-wrapper {
      overflow-x: auto;
      padding: 0 20px;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }

    .cards-wrapper::-webkit-scrollbar {
      display: none;
    }

    .cards {
      display: flex;
      gap: 20px;
      flex-wrap: nowrap;
    }

    /* Card Styles */
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 280px;
      flex: 0 0 auto;
      scroll-snap-align: start;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card h4 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      margin: 15px;
      color: #009688;
    }

    .card p {
      margin: 0 15px 15px;
      color: #666;
      font-size: 15px;
    }
  </style>
</head>
<body>
  <section class="hero">
    <header>
      <h1>Arat</h1>
      <nav>
        <a href="index.php">Home</a>
        <a href="destinations.php">Destinations</a>
        <a href="contact.html">Contact</a>
        <a href="backend/auth/login.php">Login</a>
      </nav>
    </header>

    <div class="hero-overlay">
      <h2>Welcome to Arat!</h2>
      <p>Let's travel.</p>
      <input type="text" id="searchInput" placeholder="Search destinations..." />
      <button onclick="searchDestinations()">Search</button>
    </div>
  </section>

  <!-- Featured Destinations Section -->
  <section class="featured">
    <h3>Featured Destinations</h3>
    <div class="cards-wrapper">
      <div class="cards destinationCards">
        <!-- Dynamically generated cards -->
        <?php foreach ($featured_destinations as $dest): ?>
          <div class="card" data-name="<?= htmlspecialchars($dest['name']) ?>">
            <img src="<?= htmlspecialchars($dest['image_url']) ?>" alt="<?= htmlspecialchars($dest['name']) ?>">
            <h4><?= htmlspecialchars($dest['name']) ?></h4>
            <p><?= htmlspecialchars(substr($dest['description'], 0, 100)) ?>...</p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Arat. All rights reserved.</p>
  </footer>

  <script>
  function searchDestinations() {
    const searchTerm = document.getElementById('searchInput').value;
    window.location.href = 'destinations.php?search=' + encodeURIComponent(searchTerm); // Redirect with search query
  }

  const heroSection = document.querySelector('.hero');
  const images = [ 'image/bora1.jpg', 
                     'image/arat1.jpg', 
                     'image/arat8.jpg', 
                     'image/arat4.jpg', 
                     'image/arat7.jpg', 
                     'image/arat10.jpg',  
                     'image/arat9.jpg', 
                     'image/arat11.jpg',                 
                     'image/Kawasan Falls.jpg'                    
                    ];

  let current = 0;

  function changeBackground() {
    heroSection.style.backgroundImage = `url('${images[current]}')`;
    current = (current + 1) % images.length;
  }

  changeBackground(); 
  setInterval(changeBackground, 8000); 
  </script>
</body>
</html>
