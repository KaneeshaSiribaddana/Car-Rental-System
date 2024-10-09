<?php

require_once 'config.php';

class Payment
{
    public static function createPayment($owner_id, $amount, $payment_date, $status)
    {
        // Validate payment details
        if (empty($owner_id) || empty($amount) || empty($payment_date)) {
            return "All fields are required.";
        }

        // Insert payment into the payments table
        $query = "INSERT INTO payments (owner_id, amount, payment_date, status) 
                  VALUES ('{$owner_id}', '{$amount}', '{$payment_date}', '{$status}')";

        // Use the iud method from the Database class to execute the query
        return Database::iud($query);
    }
}
