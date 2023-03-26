<?php

class Database
{
    private mysqli $conn;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset('UTF8');
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}
