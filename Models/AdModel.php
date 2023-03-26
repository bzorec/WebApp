<?php

namespace Models;

use mysqli;

class AdModel
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAd($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);
        $query = "SELECT ads.*, users.username, categories.name, users.postal_code, users.email, users.phone_number, users.address FROM vaja1.ads LEFT JOIN vaja1.users ON users.id = ads.user_id LEFT JOIN vaja1.categories ON categories.id = ads.category_id WHERE ads.id = $id;";
        $res = $this->conn->query($query);
        if ($obj = $res->fetch_object()) {
            return $obj;
        }
        return null;
    }
}
