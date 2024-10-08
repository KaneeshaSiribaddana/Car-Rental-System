<?php
require_once 'config.php';

class Booking
{
    private $vehicleId;
    private $startDate;
    private $endDate;
    private $location;
    private $withDriver;
    private $userId;
    private $status;
    private $driverId;
    private $amount;

    public function __construct($vehicleId = null, $startDate = null, $endDate = null, $location = null, $withDriver = null, $userId = null, $status = 'pending',$driverId=null,$amount=null)
    {
        $this->vehicleId = $vehicleId;
        $this->amount = $amount;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->location = $location;
        $this->withDriver = $withDriver;
        $this->userId = $userId;
        $this->driverId = $driverId;
        $this->status = $status;
    }

    // Function to add a booking
    public function addBooking()
{
    // Fetch the vehicle price from the vehicles table based on vehicle_id
    $vehicleQuery = "SELECT price FROM vehicles WHERE id = '{$this->vehicleId}'";
    $result = Database::search($vehicleQuery);
    
    if ($result && $result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
        $pricePerDay = $vehicle['price'];

        // Calculate the number of rental days
        $startDate = new DateTime($this->startDate);
        $endDate = new DateTime($this->endDate);
        $days = $startDate->diff($endDate)->days + 1; 
        
        // Calculate the total rental amount
        $this->amount = $days * $pricePerDay;

        // Convert boolean to integer for 'with_driver'
        $withDriverValue = $this->withDriver ? 1 : 0;

        // Insert the booking into the bookings table
        $query = "INSERT INTO bookings (vehicle_id, start_date, end_date, location, with_driver, user_id, status, amount) 
                  VALUES ('{$this->vehicleId}', '{$this->startDate}', '{$this->endDate}', '{$this->location}', '{$withDriverValue}', '{$this->userId}', '{$this->status}', '{$this->amount}')";
        return Database::iud($query);
    } else {
        return "Vehicle not found";
    }
}

}
