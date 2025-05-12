<?php
require 'backend/config/db.php'; // adjust path if needed

$search = ''; // default to empty search term

// Check if there's a search query from the user
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Prepare the SQL query with search filtering
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE name LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    // If no search term, get all destinations
    $stmt = $pdo->query("SELECT * FROM destinations");
}

$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Destinations</title>
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
  
  /* Destinations Section */
  .destination-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    padding: 50px 20px;
    margin-top: 20px; /* Add some space from the hero section */
  }

  /* Card adjustments to ensure equal height */
  .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    width: 320px; /* Adjust card width */
    height: 450px; /* Fixed height for all cards */
    transition: transform 0.3s ease-in-out;
    margin-bottom: 30px; /* Space below each card */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
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
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    margin: 15px;
    color: #009688;
  }

  .card p {
    margin: 0 15px;
    color: #666;
    font-size: 15px;
    flex-grow: 1; /* Allow the description to grow and occupy space */
    overflow: hidden; /* Hide overflowing content */
    text-overflow: ellipsis; /* Show ellipsis when content overflows */
    display: -webkit-box;
    -webkit-line-clamp: 3; /* Limit to 3 lines */
    -webkit-box-orient: vertical;
  }

  .card .read-more {
    margin: 0 15px;
    color: #009688;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
  }

  .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #009688;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    margin: 10px 15px;
    transition: background-color 0.3s;
    
  }

  .btn:hover {
    background-color: #00796b;
  }

  /* Footer */
  footer {
    background-color: #222;
    color: #fff;
    text-align: center;
    padding: 20px;
    font-size: 14px;
    margin-top: 50px;
  }

  /* More Coming Soon */
  h1 {
    text-align: center;
    margin-top: 50px;
    font-size: 32px;
    color: #009688;
  }

  /* Responsive Layout - Adjust for Smaller Screens */
  @media (max-width: 1024px) {
    .destination-cards {
      gap: 20px;
    }

    .card {
      width: 230px; /* Adjust for 4 cards per row */
    }
  }

  @media (max-width: 768px) {
    .destination-cards {
      gap: 15px;
    }

    .card {
      width: 45%;  /* 2 cards per row on tablets */
    }
  }

  @media (max-width: 480px) {
    .destination-cards {
      gap: 10px;
    }

    .card {
      width: 100%;  /* 1 card per row on mobile */
    }
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
    <h2>Arat, Let's Travel.</h2>
    <p>Have a particular destination in mind?</p>
    <input type="text" id="searchInput" value="<?= htmlspecialchars($search) ?>" placeholder="Search destinations..." />
    <button onclick="searchDestinations()">Search</button>
  </div>
</section>

<section class="destination-cards">
  <?php foreach ($destinations as $dest): ?>
    <div class="card">
      <img src="<?= htmlspecialchars($dest['image_url']) ?>" alt="<?= htmlspecialchars($dest['name']) ?>">
      <h3><?= htmlspecialchars($dest['name']) ?></h3>
      <p><?= htmlspecialchars(substr($dest['description'], 0, 150)) ?></p> <!-- Truncated description -->
      <a href="bookings.html" class="btn">Book Now</a>
    </div>
  <?php endforeach; ?>
</section>

<h1>MORE COMING SOON!</h1> <br><br><br>

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
