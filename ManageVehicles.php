<?php
// Include your Vehicle model
require_once 'models/Vehicle.php';

$vehicleModel = new Vehicle();
$vehicles = $vehicleModel->getAllVehicles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Manage Vehicles</h1>
    
    <!-- Search Bar -->
    <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search vehicles...">
    
    <!-- Vehicle Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Vehicle Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="vehicleTableBody">
            <?php foreach ($vehicles as $vehicle) : ?>
                <tr id="vehicle-row-<?php echo $vehicle['id']; ?>">
                    <td><?php echo $vehicle['make']; ?></td>
                    <td><?php echo $vehicle['model']; ?></td>
                    <td><?php echo $vehicle['year']; ?></td>
                    <td><?php echo $vehicle['type']; ?></td>
                    <td>
                        <a href="updateVehicle.php?vehicle_id=<?php echo $vehicle['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $vehicle['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this vehicle?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let vehicleIdToDelete;

    // Function to confirm deletion
    function confirmDelete(id) {
        vehicleIdToDelete = id;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Function to delete vehicle
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        fetch(`controllers/deleteVehicle.php?id=${vehicleIdToDelete}`, {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the deleted vehicle's row from the table
                document.getElementById(`vehicle-row-${vehicleIdToDelete}`).remove();
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
            } else {
                alert('Failed to delete vehicle.');
            }
        });
    });

    // Search functionality
    document.getElementById('searchBar').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#vehicleTableBody tr');
        rows.forEach(row => {
            const make = row.children[0].innerText.toLowerCase();
            const model = row.children[1].innerText.toLowerCase();
            const year = row.children[2].innerText.toLowerCase();
            const type = row.children[3].innerText.toLowerCase();
            
            if (make.includes(searchText) || model.includes(searchText) || year.includes(searchText) || type.includes(searchText)) {
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
