<?php
// Include the Database class
include 'models/config.php';

// Fetch drivers data
$query = "SELECT first_name, last_name, email, phone, dob, emergency_contact_name, emergency_contact_phone, driving_experience FROM vehicle_drivers";
$drivers = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Drivers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-5 mb-5">
                <h1>Manage Drivers</h1>

                <!-- Search Bar -->
                <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search drivers...">

                <!-- Drivers Table -->
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>DOB</th>
                            <th>Emergency Contact Name</th>
                            <th>Emergency Contact Phone</th>
                            <th>Driving Experience</th>
                        </tr>
                    </thead>
                    <tbody id="driverTableBody">
                        <?php if ($drivers && $drivers->num_rows > 0) : ?>
                            <?php while ($driver = $drivers->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($driver['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['email']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['dob']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['emergency_contact_name']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['emergency_contact_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['driving_experience']); ?> years</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8">No drivers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Search functionality
        document.getElementById('searchBar').addEventListener('keyup', function () {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#driverTableBody tr');
            rows.forEach(row => {
                const firstName = row.children[0].innerText.toLowerCase();
                const lastName = row.children[1].innerText.toLowerCase();
                const email = row.children[2].innerText.toLowerCase();
                const phone = row.children[3].innerText.toLowerCase();

                if (firstName.includes(searchText) || lastName.includes(searchText) || email.includes(searchText) || phone.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
