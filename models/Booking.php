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

    public function __construct($vehicleId = null, $startDate = null, $endDate = null, $location = null, $withDriver = null, $userId = null, $status = 'pending')
    {
        $this->vehicleId = $vehicleId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->location = $location;
        $this->withDriver = $withDriver;
        $this->userId = $userId;
        $this->status = $status;
    }

    // Function to add a booking
    public function addBooking()
    {
        $withDriverValue = $this->withDriver ? 1 : 0; // Convert boolean to integer for the database
        $query = "INSERT INTO bookings (vehicle_id, start_date, end_date, location, with_driver, user_id, status) 
                  VALUES ('{$this->vehicleId}', '{$this->startDate}', '{$this->endDate}', '{$this->location}', '{$withDriverValue}', '{$this->userId}', '{$this->status}')";
        return Database::iud($query);
    }
}
