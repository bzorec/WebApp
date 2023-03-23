<?php

namespace Models;

use mysqli;

class UserModel
{
    private mysqli $conn;

    function __construct()
    {
        require_once 'config.php';
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->conn->set_charset("UTF8");
    }

    function username_exists($username): bool
    {
        $username = mysqli_real_escape_string($this->conn, $username);
        $query = "SELECT * FROM users WHERE username='$username'";
        $res = $this->conn->query($query);
        return mysqli_num_rows($res) > 0;
    }

    function register_user($username, $password, $email, $first_name, $last_name, $address = '', $postal_code = '', $phone_number = ''): bool
    {
        $username = mysqli_real_escape_string($this->conn, $username);
        $pass = sha1($password);
        $email = mysqli_real_escape_string($this->conn, $email);
        $name = mysqli_real_escape_string($this->conn, $first_name);
        $surname = mysqli_real_escape_string($this->conn, $last_name);
        $address = mysqli_real_escape_string($this->conn, $address);
        $postal_code = mysqli_real_escape_string($this->conn, $postal_code);
        $phone_number = mysqli_real_escape_string($this->conn, $phone_number);

        $query = "INSERT INTO users (username, password, email, first_name, last_name, address, postal_code, phone_number) VALUES ('$username', '$pass', '$email', '$first_name', '$last_name', '$address', '$postal_code', '$phone_number');";
        if ($this->conn->query($query)) {
            return true;
        } else {
            echo mysqli_error($this->conn);
            return false;
        }
    }
}