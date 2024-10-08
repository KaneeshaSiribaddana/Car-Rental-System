<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flux Rent - Rent Vehicles Easily</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom Styling for the Hero Section */
        .hero {
            background: url('https://advertising.expedia.com/wp-content/uploads/2020/08/Car-Hero_1920x800.jpg') no-repeat center center/cover;
            min-height: 60vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            position: relative;
        }

        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-control {
            max-width: 350px;
            margin-bottom: 15px;
        }

        .filter-button {
            background-color: #f7c600;
            color: #000;
        }

        .filter-button:hover {
            background-color: #e6b500;
        }

        @media (max-width: 768px) {
            .hero {
                text-align: center;
            }

            .form-container {
                align-items: center;
            }

            .form-control {
                margin: 0 auto 15px auto;
            }
        }
    </style>
</head>

<body>
    <?php
    include 'header.php'
    ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="display-4">Find Your Perfect Vehicle</h1>
            <p class="lead">Choose from a wide range of vehicles to rent for any occasion.</p>

            <!-- Date Selection Form -->
            <div class="row">
                <div class="col-md-8">
                    <form id="vehicleFilterForm" class="form-container">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date:</label>
                                <input type="date" id="startDate" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date:</label>
                                <input type="date" id="endDate" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn filter-button btn-lg"><i class="fas fa-search"></i> Check Availability</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Vehicles Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Top Vehicles</h2>
            <div class="row" id="vehicle-list">
                <!-- Cards for vehicles -->
                <!-- Example card -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="https://source.unsplash.com/400x300/?car1" class="card-img-top" alt="Car">
                        <div class="card-body">
                            <h5 class="card-title">Luxury Sedan</h5>
                            <p class="card-text">A comfortable and stylish sedan for all your needs.</p>
                            <button class="btn btn-primary"><i class="fas fa-car"></i> Rent Now</button>
                        </div>
                    </div>
                </div>
                <!-- Repeat similar blocks for other vehicles -->
            </div>
        </div>
    </section>

    <?php
    include 'footer.php'
    ?>

    <!-- Bootstrap and JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to Handle Date Filter Form Submission -->
    <script>
        document.getElementById('vehicleFilterForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Please select both start and end dates.');
                return;
            }

            // Here you would make a request to the backend to filter available vehicles
            // based on the date range. This is just a placeholder for now.
            console.log(`Filtering vehicles from ${startDate} to ${endDate}`);

            // Fetch available vehicles and display them
            // Example: populateVehicleList(availableVehicles);

        });

        // Dummy function to simulate populating vehicle list
        function populateVehicleList(vehicles) {
            const vehicleList = document.getElementById('vehicle-list');
            vehicleList.innerHTML = ''; // Clear the existing vehicles
            vehicles.forEach(vehicle => {
                const vehicleCard = `<div class="col-md-4">
                    <div class="card mb-4">
                        <img src="${vehicle.image}" class="card-img-top" alt="Vehicle">
                        <div class="card-body">
                            <h5 class="card-title">${vehicle.name}</h5>
                            <p class="card-text">${vehicle.description}</p>
                            <button class="btn btn-primary"><i class="fas fa-car"></i> Rent Now</button>
                        </div>
                    </div>
                </div>`;
                vehicleList.innerHTML += vehicleCard;
            });
        }
    </script>
</body>

</html>