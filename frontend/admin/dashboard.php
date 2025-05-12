<?php
session_start();
require '../../backend/config/db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

try {
    // Total Users
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'traveler'");
    $stmt1->execute();
    $total_users = $stmt1->fetchColumn();

    // Total Operators
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'operator'");
    $stmt2->execute();
    $total_operators = $stmt2->fetchColumn();

    // Total Destinations
    $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM destinations");
    $stmt3->execute();
    $total_destinations = $stmt3->fetchColumn();

    // Total Bookings
    $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM bookings");
    $stmt4->execute();
    $total_bookings = $stmt4->fetchColumn();

    // Bookings by Status (Pending, Paid, Canceled)
    $stmt5 = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
    $stmt5->execute();
    $pending_bookings = $stmt5->fetchColumn();

    $stmt6 = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'paid'");
    $stmt6->execute();
    $paid_bookings = $stmt6->fetchColumn();

    $stmt7 = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'");
    $stmt7->execute();
    $canceled_bookings = $stmt7->fetchColumn();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch recent bookings (last 5)
$bookings_stmt = $pdo->query("SELECT b.*, u.name AS user_name, d.name AS destination_name
                              FROM bookings b
                              JOIN users u ON b.user_id = u.id
                              JOIN destinations d ON b.package_id = d.id
                              ORDER BY b.booking_date DESC LIMIT 5");

$recent_bookings = $bookings_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent users (last 5 users who registered)
$users_stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
$recent_users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent destinations (last 5 destinations added)
$destinations_stmt = $pdo->query("SELECT * FROM destinations ORDER BY created_at DESC LIMIT 5");
$recent_destinations = $destinations_stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT 
        d.id, 
        d.name, 
        d.start_date, 
        d.end_date,
        d.max_slots,
        COALESCE(SUM(CASE WHEN b.status = 'paid' THEN b.num_travelers ELSE 0 END), 0) AS booked_slots
    FROM destinations d
    LEFT JOIN bookings b ON d.id = b.package_id
    WHERE d.start_date >= ?
    GROUP BY d.id
    ORDER BY d.start_date ASC
    LIMIT 5
");

$stmt->execute([$today]);
$upcomingTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent messages (last 5)
$messages_stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
$recent_messages = $messages_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        ::-webkit-scrollbar { display: none; }

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

        .overlay{
            background-color: rgba(0, 0, 0, 0.6);
            padding: 80px 20px 40px;
            min-height: 100vh;
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

        .hero-overlay { 
            border-radius: 12px;
            text-align: center;
            color: white;
        }

        .hero-overlay h2 {
            font-size: 36px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(0,0,0,0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            font-size: 18px;
            color: white;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .section-title {
            font-size: 24px;
            color:white;
            margin-bottom: 15px;
        }

        .card-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: rgba(0,0,0,0.5);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .card h4 {
            margin-bottom: 8px;
            color: white;
        }

        .card p {
            margin: 5px 0;
            color: white;
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
    <div class="hero-overlay">
        <h2>Welcome, Admin</h2>
        <p>Oversee everything. You're the boss.</p>
    </div>
</section>

<div class="dashboard-container">
    <div class="stats-grid">
        <div class="stat-card"><h3>Total Users</h3><p><?= $total_users ?></p></div>
        <div class="stat-card"><h3>Total Operators</h3><p><?= $total_operators ?></p></div>
        <div class="stat-card"><h3>Total Destinations</h3><p><?= $total_destinations ?></p></div>
        <div class="stat-card"><h3>Total Bookings</h3><p><?= $total_bookings ?></p></div>
        <div class="stat-card"><h3>Pending Bookings</h3><p><?= $pending_bookings ?></p></div>
        <div class="stat-card"><h3>Paid Bookings</h3><p><?= $paid_bookings ?></p></div>
        <div class="stat-card"><h3>Cancelled Bookings</h3><p><?= $canceled_bookings ?></p></div>
    </div>

    <h2 class="section-title">Recent Bookings</h2>
    <div class="card-list">
        <?php foreach ($recent_bookings as $booking): ?>
            <div class="card">
                <h4><?= htmlspecialchars($booking['user_name']) ?></h4>
                <p>Booked: <?= htmlspecialchars($booking['destination_name']) ?></p>
                <p>Date: <?= htmlspecialchars($booking['booking_date']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">Recent Users</h2>
    <div class="card-list">
        <?php foreach ($recent_users as $user): ?>
            <div class="card">
                <h4><?= htmlspecialchars($user['name']) ?></h4>
                <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                <p>Joined: <?= htmlspecialchars($user['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">Recent Destinations</h2>
    <div class="card-list">
        <?php foreach ($recent_destinations as $dest): ?>
            <div class="card">
                <h4><?= htmlspecialchars($dest['name']) ?></h4>
                <p>Added: <?= htmlspecialchars($dest['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">Upcoming Tours</h2>
    <div class="card-list">
        <?php foreach ($upcomingTours as $tour): 
            $availableSlots = $tour['max_slots'] - $tour['booked_slots'];
        ?>
            <div class="card">
                <h4><?= htmlspecialchars($tour['name']) ?></h4>
                <p>Start: <?= $tour['start_date'] ?></p>
                <p>End: <?= $tour['end_date'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">Recent Messages</h2>
    <div class="card-list">
        <?php foreach ($recent_messages as $msg): ?>
            <div class="card">
                <h4><?= htmlspecialchars($msg['name']) ?> (<?= htmlspecialchars($msg['email']) ?>)</h4>
                <p><strong>Subject:</strong> <?= htmlspecialchars($msg['subject']) ?></p>
                <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                <p><small>Sent: <?= $msg['created_at'] ?></small></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</div>

</body>
</html>
