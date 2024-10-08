<?php
// Import the Database class
require_once 'config.php';

class VehicleDriver
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;
    private $emergencyContactName;
    private $emergencyContactPhone;
    private $drivingExperience;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null, $emergencyContactName = null, $emergencyContactPhone = null, $drivingExperience = null) {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
            $this->emergencyContactName = $emergencyContactName;
            $this->emergencyContactPhone = $emergencyContactPhone;
            $this->drivingExperience = $drivingExperience;
        }
    }
    

    public function register()
    {
        // Prepare an SQL statement for inserting data into the 'vehicle_drivers' table
        $stmt = "INSERT INTO vehicle_drivers (first_name, last_name, email, phone, password, dob, emergency_contact_name, emergency_contact_phone, driving_experience) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob', '$this->emergencyContactName', '$this->emergencyContactPhone', '$this->drivingExperience')";
        
        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM vehicle_drivers WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }
    public function login($email, $password)
    {
        // Prepare the SQL statement to find the driver
        $query = "SELECT * FROM vehicle_drivers WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $driverData = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $driverData['password'])) {
                return $driverData; // Return driver data if login is successful
            }
        }
        return false; // Return false if login fails
    }
}
?>
