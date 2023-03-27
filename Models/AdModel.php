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

    public function get_user_ads($user_id): array
    {
        $query = "SELECT * FROM ads WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ads = array();
        while ($row = $result->fetch_assoc()) {
            $ads[] = $row;
        }
        return $ads;
    }

    public function getAd($id)
    {
        $stmt = $this->conn->prepare("SELECT ads.*, users.username, categories.name, users.postal_code, users.email, users.phone_number, users.address FROM vaja1.ads LEFT JOIN vaja1.users ON users.id = ads.user_id LEFT JOIN vaja1.categories ON categories.id = ads.category_id WHERE ads.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($obj = $res->fetch_object()) {
            return $obj;
        }
        return null;
    }


    public function update_ad($ad_id, $title, $description, $category_id): bool
    {
        $user_id = $_SESSION["USER_ID"];

        $query = "UPDATE ads SET title=?, description=?, category_id=? WHERE id=? AND user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisi", $title, $description, $category_id, $ad_id, $user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            echo $stmt->error;
            return false;
        }
    }

    public function deleteAd($ad_id, $user_id): bool
    {
        $ad_id = mysqli_real_escape_string($this->conn, $ad_id);
        $user_id = mysqli_real_escape_string($this->conn, $user_id);

        $query = "SELECT id FROM ads WHERE id='$ad_id' AND user_id='$user_id'";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            $query = "DELETE FROM ads WHERE id='$ad_id'";
            if ($this->conn->query($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
