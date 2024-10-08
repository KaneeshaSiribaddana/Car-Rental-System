<?php
require_once 'models/Vehicle.php';

// Check if vehicle ID is provided
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    // Initialize Vehicle model
    $vehicleModel = new Vehicle();

    // Delete the vehicle
    if ($vehicleModel->deleteVehicleById($vehicleId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Vehicle ID is missing']);
}
?>
