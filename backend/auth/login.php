<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/', // <-- This makes session accessible across all folders
]);

session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Check if the account is deactivated
        if ($user['status'] == 'inactive') {
          header("Location: deactivated.php");
        } else {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['name']    = $user['name'];

                // Redirect by role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: /frontend/admin/dashboard.php");
                        break;
                    case 'operator':
                        header("Location: /frontend/operator/dashboard.php");
                        break;
                    default:
                        header("Location: /frontend/traveler/dashboard.php");
                }
                exit;
            } else {
                $error_message = "Invalid email or password.";
            }
        }
    } else {
        $error_message = "User not found. Please check your email.";
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

    /* Login Form Styles */
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }

    .hero input {
      padding: 10px;
      width: 250px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 10px;
      font-family: 'Playfair Display', serif;
      background: rgba(0, 0, 0, 0.5);
      color: white;
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
    }

    .hero button:hover {
      background-color: #00796b;
    }

    /* Error message style */
    .error-message {
      color:rgb(255, 255, 255);
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
      <form action="login.php" method="POST">
        <h3><b>Login to your Account</b></h3>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button> <br>
      </form>

      <!-- Display error message if set -->
      <?php if (isset($error_message)): ?>
        <div class="error-message">
          <?= htmlspecialchars($error_message) ?>
        </div>
      <?php endif; ?>

      Don't have an account? <a href="register.php">Register here.</a>
    </div>
  </section>

  <script>
  function searchDestinations() {
    const searchTerm = document.getElementById('searchInput').value;
    window.location.href = 'destinations.php?search=' + encodeURIComponent(searchTerm); // Redirect with search query
  }

  const heroSection = document.querySelector('.hero');
  const images = [   '/image/bora1.jpg', 
                     '/image/arat1.jpg', 
                     '/image/arat8.jpg', 
                     '/image/arat4.jpg',           
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

</body>
</html>
