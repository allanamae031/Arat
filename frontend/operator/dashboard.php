<?php
session_start();
require_once '../../backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    echo "Access denied.";
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $user_name = htmlspecialchars($user['name']);
    }
}

$operator_id = $_SESSION['user_id'];

try {
    // Count total destinations
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM destinations WHERE operator_id = ?");
    $stmt1->execute([$operator_id]);
    $total_destinations = $stmt1->fetchColumn();

    // Count total bookings for their destinations
    $stmt2 = $pdo->prepare("
        SELECT COUNT(*) FROM bookings b
        JOIN destinations d ON b.package_id = d.id
        WHERE d.operator_id = ?
    ");
    $stmt2->execute([$operator_id]);
    $total_bookings = $stmt2->fetchColumn();

    // Get list of destinations
    $stmt3 = $pdo->prepare("SELECT * FROM destinations WHERE operator_id = ?");
    $stmt3->execute([$operator_id]);
    $destinations = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // Upcoming tours (start in 3 days or less)
    $upcomingStmt = $pdo->prepare("
        SELECT 
            d.id,
            d.name, 
            d.start_date,
            (SELECT COUNT(*) FROM bookings b WHERE b.package_id = d.id AND b.status = 'paid') AS traveler_count
        FROM destinations d
        WHERE d.operator_id = ? 
          AND d.start_date >= CURDATE() 
          AND d.start_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
        ORDER BY d.start_date ASC
    ");
    $upcomingStmt->execute([$operator_id]);
    $upcomingTours = $upcomingStmt->fetchAll(PDO::FETCH_ASSOC);

    // Cancellations / pending payments
    $issuesStmt = $pdo->prepare("
        SELECT 
            u.name AS user_name,
            d.name AS destination_name,
            b.status
        FROM bookings b
        JOIN destinations d ON b.package_id = d.id
        JOIN users u ON b.user_id = u.id
        WHERE d.operator_id = ?
          AND (b.status = 'pending' OR b.status = 'cancelled')
        ORDER BY b.booking_date DESC
    ");
    $issuesStmt->execute([$operator_id]);
    $statusIssues = $issuesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<html>
<head>
    <title>Operator Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
        .hero h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .section-title {
            font-size: 24px;
            margin: 30px 0 15px;
            padding-left: 10px;
            color:white;
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
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            color: #fff;
        }
        .badge.pending {
            background-color:rgba(243, 157, 18, 0.53);
        }
        .badge.cancelled {
            background-color:rgba(231, 77, 60, 0.51);
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color:rgba(130, 255, 242, 0.59);
            font-weight: bold;
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
                <a href="destinations.php">Destinations</a>
                <a href="bookings.php">Bookings</a>
                <a href="/backend/auth/logout.php">Logout</a>
            </nav>
        </header>
        <div class="hero-overlay">
        <h2>Welcome, <?= $user_name ?></h2>
        <p>Here's a summary of your Tours and Bookings</p>
        </div>
    </section>

    <div class="dashboard-container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Destinations</h3>
                <p><?= $total_destinations ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?= $total_bookings ?></p>
            </div>
        </div>

        <h2 class="section-title">Upcoming Tours (Within 3 Days)</h2>
        <div class="card-list">
            <?php if (empty($upcomingTours)): ?>
                <div class="card">No upcoming tours.</div>
            <?php else: ?>
                <?php foreach ($upcomingTours as $tour): ?>
                    <div class="card">
                        <h4><?= htmlspecialchars($tour['name']) ?></h4>
                        <p><strong>Start Date:</strong> <?= htmlspecialchars($tour['start_date']) ?></p>
                        <p><strong>Travelers:</strong> <?= $tour['traveler_count'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Bookings with Issues</h2>
        <div class="card-list">
            <?php if (empty($statusIssues)): ?>
                <div class="card">No pending or cancelled bookings.</div>
            <?php else: ?>
                <?php foreach ($statusIssues as $issue): ?>
                    <div class="card">
                        <h4><?= htmlspecialchars($issue['user_name']) ?></h4>
                        <p><strong>Destination:</strong> <?= htmlspecialchars($issue['destination_name']) ?></p>
                        <span class="badge <?= $issue['status'] === 'cancelled' ? 'cancelled' : 'pending' ?>">
                            <?= ucfirst($issue['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Your Destinations</h2>
        <div class="card-list">
            <?php if (count($destinations) === 0): ?>
                <div class="card">You haven’t added any destinations yet.</div>
            <?php else: ?>
                <?php foreach ($destinations as $dest): ?>
                    <div class="card">
                        <h4><?= htmlspecialchars($dest['name']) ?></h4>
                        <p><strong>Flight:</strong> <?= htmlspecialchars($dest['flight_info']) ?></p>
                        <p><strong>Hotel:</strong> <?= htmlspecialchars($dest['hotel']) ?></p>
                        <p><strong>Dates:</strong> <?= $dest['start_date'] ?> to <?= $dest['end_date'] ?></p>
                        <p><strong>Price:</strong> ₱<?= $dest['price'] ?></p>
                        <a href="edit_destination.php?id=<?= $dest['id'] ?>">Edit</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
    
</body>
</html>
