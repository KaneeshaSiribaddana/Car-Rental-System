<?php
// Import the Database class
require_once 'config.php';

class VehicleOwner
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null) {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
        }
    }

    public function register()
    {
        // Prepare an SQL statement for inserting data
        $stmt = "INSERT INTO vehicle_owners (first_name, last_name, email, phone, password, dob) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob')";
        
        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM vehicle_owners WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }
    public function login($email, $password)
    {
        // Prepare the SQL statement to find the vehicle owner
        $query = "SELECT * FROM vehicle_owners WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $ownerData = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $ownerData['password'])) {
                return $ownerData; // Return vehicle owner data if login is successful
            }
        }
        return false; // Return false if login fails
    }
}
?>
