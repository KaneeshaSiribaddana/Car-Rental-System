<?php
// Include your Vehicle model
require_once 'models/Vehicle.php';

// Get the vehicle ID from the query parameter
$vehicleId = isset($_GET['vehicleId']) ? intval($_GET['vehicleId']) : 0;

$vehicleModel = new Vehicle();
// Validate vehicle ID and fetch vehicle details
if ($vehicleId > 0) {
    $vehicle = $vehicleModel->getVehicleWithImages($vehicleId);
    if (!$vehicle) {
        // If no vehicle is found with the given ID, show an error
        $errorMessage = "No vehicle available with this ID.";
    }
} else {
    $errorMessage = "Invalid vehicle ID.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - Car Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .car-details img {
            width: 100%;
            border-radius: 5px;
        }

        .car-info {
            padding: 20px;
        }

        .car-info h2 {
            font-size: 28px;
        }

        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .centered-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* Ensure the carousel fits within its container */
        #vehicleCarousel {
            max-width: 100%;
            /* Responsive width */
            height: auto;
            /* Auto height */
        }

        #vehicleCarousel .carousel-inner {
            height: 400px;
            /* Set a fixed height */
        }

        #vehicleCarousel .carousel-item {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #vehicleCarousel .carousel-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">
    <?php include 'header.php'; ?>

    <div class="container centered-container mt-5">
        <div class="row justify-content-center">
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php else: ?>
                <!-- Car Details Section -->
                <h2><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="car-details">
                            <!-- Carousel for Vehicle Images -->
                            <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-inner">
                                    <?php if (!empty($vehicle['images'])): ?>
                                        <?php foreach ($vehicle['images'] as $index => $image): ?>
                                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                <!-- Ensure each image is displayed properly -->
                                                <img src="controllers/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?>" class="d-block w-100" style="object-fit: cover; max-height: 400px;">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="carousel-item active">
                                            <!-- Default fallback image if no images are available -->
                                            <img src="path/to/default/image.jpg" alt="Default Vehicle Image" class="d-block w-100" style="object-fit: cover; max-height: 400px;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Carousel Controls -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Booking Form Section -->
                    <div class="col-md-6">
                        <div class="car-info mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-car"></i> Brand: <?= htmlspecialchars($vehicle['make']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-calendar-alt"></i> Year: <?= htmlspecialchars($vehicle['year']) ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-car-side"></i> Model: <?= htmlspecialchars($vehicle['model']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-car-alt"></i> Type: <?= htmlspecialchars($vehicle['type']) ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-gas-pump"></i> Fuel Type: <?= htmlspecialchars($vehicle['fuel_type']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-cogs"></i> Transmission: <?= htmlspecialchars($vehicle['transmission']) ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-users"></i> Seating Capacity: <?= htmlspecialchars($vehicle['seating_capacity']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted"><i class="fas fa-tachometer-alt"></i> Mileage: <?= htmlspecialchars($vehicle['mileage']) ?> miles</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="text-muted">
                                        <i class="fas fa-palette"></i>
                                        Color
                                        <span class="color-display" style="display: inline-block; width: 20px; height: 20px;margin-top:5px; background-color: <?= htmlspecialchars($vehicle['color']) ?>; border-radius: 5px; margin-right: 5px;"></span>

                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p class="text-muted">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="badge bg-success text-white" style="padding: 5px 10px; border-radius: 5px;">
                                        Price: $<?= htmlspecialchars($vehicle['price']) ?>/day
                                    </span>
                                </p>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-muted"><i class="fas fa-align-left"></i> Description: <?= htmlspecialchars($vehicle['description']) ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <div class="form-section col-12 col-md-6">
                        <h3 class="text-center mb-4">Book Now</h3>
                        <form id="bookingForm" method="POST" action="controllers/add_booking_process.php">
                            <input type="hidden" name="vehicleId" value="<?= htmlspecialchars($vehicle['id']) ?>">

                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Pickup Location</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Enter pickup location" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="withDriver" name="withDriver">
                                <label class="form-check-label" for="withDriver">Get with a Driver</label>
                            </div>

                            <button type="button" class="btn btn-primary w-100" onclick="validateForm()">Confirm Booking</button>
                        </form>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Confirm Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to confirm this booking?</p>
                    <ul id="bookingSummary"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>

    <script>
        function validateForm() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const location = document.getElementById('location').value;
            const withDriver = document.getElementById('withDriver').checked;

            // Validate that fields are not empty
            if (!startDate || !endDate || !location) {
                alert("Please fill out all required fields.");
                return;
            }

            // Check that end date is not before start date
            if (new Date(endDate) < new Date(startDate)) {
                alert("End date cannot be before the start date.");
                return;
            }

            // Display summary in the modal
            const summary = `
            <li><strong>Start Date:</strong> ${startDate}</li>
            <li><strong>End Date:</strong> ${endDate}</li>
            <li><strong>Pickup Location:</strong> ${location}</li>
            <li><strong>With Driver:</strong> ${withDriver ? "Yes" : "No"}</li>
        `;
            document.getElementById('bookingSummary').innerHTML = summary;

            // Show the Bootstrap confirmation modal
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            bookingModal.show();
        }

        function submitForm() {
            // Submit the form after confirmation
            document.getElementById('bookingForm').submit();
        }
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>