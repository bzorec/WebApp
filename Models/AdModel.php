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

    public function getCategories(): array
    {
        $query = "SELECT id, name FROM categories";
        $result = $this->conn->query($query);
        $categories = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }

    function publish($title, $desc, $img, $category_id, $price): bool
    {
        $title = mysqli_real_escape_string($this->conn, $title);
        $desc = mysqli_real_escape_string($this->conn, $desc);
        $user_id = $_SESSION["USER_ID"];

        //Preberemo vsebino (byte array) slike
        $img_file = file_get_contents($img["tmp_name"]);
        //Pripravimo byte array za pisanje v bazo (v polje tipa LONGBLOB)
        $img_file = mysqli_real_escape_string($this->conn, $img_file);

        $query = "INSERT INTO vaja1.ads (title, description, user_id, image, category_id, price)
              VALUES ('$title', '$desc', '$user_id', '$img_file', '$category_id', '$price')";
        if ($this->conn->query($query)) {
            return true;
        } else {
            echo mysqli_error($this->conn);
            return false;
        }
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
