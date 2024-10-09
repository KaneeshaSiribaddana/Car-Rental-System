<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];



// Fetch total bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$totalBookingsResult = Database::search($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetch_assoc()['total_bookings'];

// Fetch total earnings (owner's share before platform charges)
$totalEarningsQuery = "SELECT SUM(amount) AS total_earnings FROM payments WHERE status = 'Paid'";
$totalEarningsResult = Database::search($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;

// Calculate earnings after 5% platform charge
$platformCharge = 0.05;
$earningsAfterCharge = $totalEarnings - ($totalEarnings * $platformCharge);

// Fetch booking statuses
$totalCustomersQuery = "SELECT COUNT(*) AS total_customers FROM customers";
$totalDriversQuery = "SELECT COUNT(*) AS total_drivers FROM vehicle_drivers ";
$totalOwnersQuery = "SELECT COUNT(*) AS total_owners FROM vehicle_owners";

$totalCustomers = Database::search($totalCustomersQuery)->fetch_assoc()['total_customers'];
$totalDrivers = Database::search($totalDriversQuery)->fetch_assoc()['total_drivers'];
$totalOwners = Database::search($totalOwnersQuery)->fetch_assoc()['total_owners'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Owner | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            height: 100%;
        }

        .wrapper {
            min-height: 100%;
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
            <div class="container my-3">
                <h2 class="mb-4 text-center">Admin Dashboard</h2>
                <div class="row g-4">

                    <!-- Total Earnings Card -->
                    <div class="col-md-6">
                        <div class="card dashboard-card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Total Earnings</h5>
                                <p class="card-text">Rs <?= number_format($totalEarnings, 2) ?></p>
                                <a href="vehicle-owner-payments.php" class="btn btn-light mt-3">Go to Payment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Other Cards -->
                    <div class="col-md-6">
                        <div class="row g-4">
                            <!-- Total Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-calendar-check"></i> Total Bookings</h5>
                                        <p class="card-text"><?= $totalBookings ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-warning">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Total Customers</h5>
                                        <p class="card-text"><?= $totalCustomers ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Total Vehicle Owners</h5>
                                        <p class="card-text"><?= $totalOwners ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejected Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-danger">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-times-circle"></i> Total Vehicle Drivers</h5>
                                        <p class="card-text"><?= $totalDrivers ?></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            
        </div>

    </div>
    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>