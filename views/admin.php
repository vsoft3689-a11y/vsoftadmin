<?php
include "../config/database.php"; // your DB connection file

$db = new Database();
$conn = $db->connect();

// Fetch total projects
$projectsQuery = $conn->query("SELECT COUNT(*) as total FROM projects");
$totalProjects = $projectsQuery->fetch_assoc()['total'];

// Fetch total users
$usersQuery = $conn->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $usersQuery->fetch_assoc()['total'];

// Fetch new custom requirements (example: type='contact' and status='pending')
$requirementsQuery = $conn->query("SELECT COUNT(*) as total FROM custom_requirements WHERE status='pending'");
$totalRequirements = $requirementsQuery->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VSoftSolutions Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <style>
        .container {
            max-width: 1000px;
        }

        .grid {
            display: flex;
            gap: 20px;
        }

        .card {
            flex: 1;
            padding: 20px;
            background: #06BBCC !important;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .small {
            font-size: 20px !important;
            font-weight: bold;
            color: #fef9f9ff;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <?php include "./adminnavbar.php" ?>

    <div class='container mt-5'>
        <div class='grid'>
            <div class='card'>
                <div class='small'>Total Projects</div>
                <div style='font-size:28px;font-weight:700;color:aliceblue'><?= $totalProjects ?></div>
            </div>
            <div class='card'>
                <div class='small'>Total Users</div>
                <div style='font-size:28px;font-weight:700;color:aliceblue'><?= $totalUsers ?></div>
            </div>
            <div class='card'>
                <div class='small'>New Custom Requirements</div>
                <div style='font-size:28px;font-weight:700;color:aliceblue'><?= $totalRequirements ?></div>
            </div>
        </div>
    </div>

    <?php include "./footer.php" ?>
</body>

</html>