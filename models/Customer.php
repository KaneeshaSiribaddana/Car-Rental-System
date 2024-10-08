<?php
// Import the Database class
require_once 'config.php';

class Customer
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;
    private $preferredContactMethod;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null, $preferredContactMethod = null) {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
            $this->preferredContactMethod = $preferredContactMethod;
        }
    }
    

    public function register()
    {
        // Prepare an SQL statement for inserting data
        $stmt = "INSERT INTO customers (first_name, last_name, email, phone, password, dob, preferred_contact_method) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob', '$this->preferredContactMethod')";
        
        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM customers WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }

    public function login($email, $password)
    {
        // Prepare the SQL statement to find the customer
        $query = "SELECT * FROM customers WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $customerData = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $customerData['password'])) {
                return $customerData; // Return customer data if login is successful
            }
        }
        return false; // Return false if login fails
    }
}
?>
