<?php
session_start();

// Session validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];

// Fetch total earnings for the owner from the bookings table
$totalEarningsQuery = "SELECT SUM(amount) AS total_earnings FROM payments WHERE status = 'Paid'";
$totalEarningsResult = Database::search($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;

// Fetch payment history and owner details (first name, last name)
$paymentsQuery = "
    SELECT p.*, o.first_name, o.last_name 
    FROM payments p 
    JOIN vehicle_owners o ON p.owner_id = o.id
    WHERE p.status = 'Paid' 
    ORDER BY p.payment_date DESC";
$paymentsResultHistory = Database::search($paymentsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Earnings and Payment History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>
        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-5" style="margin-top: 20px;">
                <div class="mt-5">
                    <h2 class="mb-4">Total Earnings & Payment History</h2>

                    <!-- Total Earnings Section -->
                    <div class="card mb-4 border border-primary shadow-sm" style="background-color: #f9f9f9;">
                        <div class="card-body">
                            <h4 class="card-title text-primary"><i class="fas fa-wallet"></i> Total Earnings</h4>
                            <p class="card-text fs-5 text-dark">
                                <strong>Total Earnings: </strong>Rs<?php echo number_format($totalEarnings, 2); ?><br>
                            </p>
                        </div>
                    </div>

                    <!-- Payment History Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-history"></i> Payment History</h4>
                            <?php if ($paymentsResultHistory && $paymentsResultHistory->num_rows > 0) : ?>
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Owner Name</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($payment = $paymentsResultHistory->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                                <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                                                <td>Rs <?php echo number_format($payment['amount'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                                <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                                <td>
                                                    <!-- Update Payment Button -->
                                                    <a href="admin-update-payment.php?payment_id=<?php echo $payment['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Update
                                                    </a>

                                                    <!-- Delete Payment Button with Bootstrap Confirmation -->
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal<?php echo $payment['id']; ?>">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>

                                                    <!-- Bootstrap Modal for Delete Confirmation -->
                                                    <div class="modal fade" id="confirmDeleteModal<?php echo $payment['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete Payment ID <?php echo $payment['id']; ?>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="controllers/admin_delete_payment_process.php" method="POST">
                                                                        <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No payment history available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
