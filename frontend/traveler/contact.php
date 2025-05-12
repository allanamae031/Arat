<?php
session_start();
require '../../backend/config/db.php'; // adjust path as needed

$name = '';
$email = '';

// Ensure user is logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $name = $user['name'];
        $email = $user['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arat pre gala tayo</title>
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
      background-image: url('/image/arat.jpg');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      color: #333;
      line-height: 1.6;
      position: relative;
    }

    .overlay {
      position: relative;
      z-index: 1;
      padding: 100px 40px 40px;
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 10px 40px;
      display: flex;
      align-items: center;
      z-index: 1000;
      background: rgba(0, 0, 0, 0.4);
    }

    header h1 {
      font-size: 32px;
      color: #fff;
    }

    nav {
      margin-left: auto;
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
      font-size: 32px;
      color:rgb(255, 255, 255);
      margin-bottom: 20px;
      text-align: center;
    }

    .contact-section {
      background-color: rgba(0, 0, 0, 0.3);
      padding: 60px 20px;
      min-height: 60vh;
      border-radius: 20px;
    }
    .contact-container {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 800px;
      margin: auto;
    }
    .contact-info {
      flex: 1;
    }
    .contact-info h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }
    .contact-info p {
      margin: 10px 0;
      color: white;
    }
    .social-links a {
      display: inline-block;
      margin-right: 10px;
      color: #8adfe1;
      text-decoration: none;
    }
    .contact-form {
      flex: 1;
    }
    .contact-form form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .contact-form input,
    .contact-form textarea {
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      font-family: 'Playfair Display', serif;

    }
    .contact-form textarea {
      height: 120px;
      resize: vertical;
    }
    .contact-form button {
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      font-family: 'Playfair Display', serif;

    }
    .contact-form button:hover {
      background-color: rgba(255, 255, 255, 0.79);
      color: black;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .contact-container {
        flex-direction: column;
      }
    }

    </style>
</head>
<body>

    <header>
      <h1>Arat</h1>
      <nav>
        <a href="dashboard.php">Home</a>
        <a href="destinations.php">Destinations</a>
        <a href="bookings.php">Bookings</a>
        <a href="contact.php">Contact</a>
        <a href="/backend/auth/logout.php">Logout</a>
      </nav>
    </header>

<div class="overlay">
  <br><br>
  <section class="contact-section">
    <div class="contact-container">
      <div class="contact-info">
        <h2>Get in Touch</h2>
        <p>Email: support@arat.com</p>
        <p>Phone: +63 902 491 5299</p>
        <p>Address: Jan sa Tabi-Tabi Avenue, Makati City, Philippines</p>
        <p>Hours: Mon–Fri 9:00 AM – 6:00 PM</p>
        <div class="social-links">
          <a href="#">Facebook</a>
          <a href="#">Instagram</a>
        </div>
      </div>
  
      <div class="contact-form">
        <form method="POST" action="send_message.php">
          <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Your Name" required />
          <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Your Email" required />
          <input type="text" name="subject" placeholder="Subject" />
          <textarea name="message" placeholder="Your Message" required></textarea>
          <button type="submit">Send Message</button>
        </form>
      </div>
    </div>
  </section>

</div>
  
</body>
</html>
