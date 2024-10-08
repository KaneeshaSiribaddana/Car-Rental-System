<?php
require_once 'config.php';

class Vehicle
{
    // Add vehicle to the database
    public function addVehicle($make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $images, $price, $description)
    {
        // Use a prepared statement to avoid SQL injection
        $query = "INSERT INTO vehicles (make, model, year, type, fuel_type, transmission, seating_capacity, mileage, color, owner, driver,price,description) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('sssssssssssss', $make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $price, $description);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Get the last inserted vehicle ID
        $vehicleId = $stmt->insert_id;

        // Process image uploads
        if ($this->uploadImages($vehicleId, $images)) {
            return true;
        }
        return false;
    }

    // Upload vehicle images
    private function uploadImages($vehicleId, $images)
    {
        $uploadDir = 'uploads/vehicles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($images['tmp_name'] as $key => $tmpName) {
            if ($images['error'][$key] === UPLOAD_ERR_OK) {
                $imageName = $vehicleId . '_' . time() . '_' . basename($images['name'][$key]);
                $targetFile = $uploadDir . $imageName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $query = "INSERT INTO vehicle_images (vehicle_id, image_path) VALUES (?, ?)";
                    $stmt = Database::$connection->prepare($query);

                    if ($stmt === false) {
                        die("Prepare failed: " . Database::$connection->error);
                    }

                    $stmt->bind_param('is', $vehicleId, $targetFile);

                    if (!$stmt->execute()) {
                        die("Execute failed: " . $stmt->error);
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    // Fetch all vehicles
    public function getAllVehicles()
    {
        $query = "SELECT * FROM vehicles";
        $result = Database::search($query);

        if (!$result) {
            die("Query failed: " . Database::$connection->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch vehicle by ID
    public static function getVehicleById($vehicleId)
    {
        Database::setUpConnection(); // Ensure connection is established
        $query = "SELECT * FROM vehicles WHERE id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Delete vehicle by ID
    public function deleteVehicleById($id)
    {
        $query = "DELETE FROM vehicles WHERE id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Update vehicle details
    public function updateVehicle($vehicleId, $make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $images = null)
    {
        $query = "UPDATE vehicles 
                  SET make = ?, model = ?, year = ?, type = ?, fuel_type = ?, transmission = ?, seating_capacity = ?, mileage = ?, color = ?, owner = ?, driver = ? 
                  WHERE id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('sssssssssssi', $make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        if ($images && $images['tmp_name'][0] != '') {
            $this->deleteVehicleImages($vehicleId);
            if (!$this->uploadImages($vehicleId, $images)) {
                return false;
            }
        }
        return true;
    }

    // Delete all images related to a vehicle
    private function deleteVehicleImages($vehicleId)
    {
        $query = "SELECT image_path FROM vehicle_images WHERE vehicle_id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $imagePath = $row['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $query = "DELETE FROM vehicle_images WHERE vehicle_id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }
    public function getVehicleWithImages($vehicleId)
    {
        // Prepare SQL statement to retrieve vehicle details
        $sql = "SELECT v.*, i.image_path 
                FROM vehicles v 
                LEFT JOIN vehicle_images i ON v.id = i.vehicle_id 
                WHERE v.id = " . intval($vehicleId);

        // Execute the search query
        $result = Database::search($sql);

        // Fetch vehicle details
        if ($result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();

            $imagesSql = "SELECT image_path FROM vehicle_images WHERE vehicle_id = " . intval($vehicleId);
            $imagesResult = Database::search($imagesSql);

            $vehicle['images'] = [];
            while ($imageRow = $imagesResult->fetch_assoc()) {
                $vehicle['images'][] = $imageRow['image_path'];
            }

            return $vehicle; 
        }

        return null; // Vehicle not found
    }
}
