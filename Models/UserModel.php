<?php

namespace Models;

use Couchbase\User;
use mysqli;

class UserModel
{
    private mysqli $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->conn->set_charset("UTF8");
    }

    function validate_login($username, $password): int
    {
        $username = mysqli_real_escape_string($this->conn, $username);
        $pass = sha1($password);
        $query = "SELECT id, role FROM vaja1.users WHERE username='$username' AND password='$pass'";
        $res = $this->conn->query($query);
        if ($user_obj = $res->fetch_object()) {
            $_SESSION["USER_ID"] = $user_obj->id;
            $_SESSION["ROLE"] = $user_obj->role;
            return $user_obj->id;
        }
        return -1;
    }

    function get_all_users(): array
    {
        $query = "SELECT * FROM users";
        $res = $this->conn->query($query);

        if ($res) {
            return $res->fetch_all(MYSQLI_ASSOC);
        } else {
            echo $this->conn->error;
            return [];
        }
    }

    function get_user_role($id): ?string
    {
        $query = "SELECT role FROM users WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['role'];
        } else {
            return null;
        }
    }

    function username_exists($username): bool
    {
        $query = "SELECT * FROM users WHERE username=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    function user_exists($id): bool
    {
        $query = "SELECT * FROM users WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

    function add_user($current_user_id, $username, $password, $email, $first_name, $last_name, $address, $postal_code, $phone_number, $role): bool
    {
        $current_user_role = $this->get_user_role($current_user_id);

        if ($current_user_role === 'admin') {
            $pass = sha1($password);

            $query = "INSERT INTO users (username, password, email, first_name, last_name, address, postal_code, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssssss", $username, $pass, $email, $first_name, $last_name, $address, $postal_code, $phone_number, $role);

            if ($stmt->execute()) {
                return true;
            } else {
                echo $stmt->error;
                return false;
            }
        } else {
            echo "You do not have permission to perform this action.";
            return false;
        }
    }

    function delete_user($current_user_id, $user_id): bool
    {
        $current_user_role = $this->get_user_role($current_user_id);

        if ($current_user_role === 'admin') {
            $query = "DELETE FROM users WHERE id=?;";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                return true;
            } else {
                echo $stmt->error;
                return false;
            }
        } else {
            echo "You do not have permission to perform this action.";
            return false;
        }
    }

    function edit_user($current_user,$user_id, $username, $email, $first_name, $last_name): bool
    {
        $current_user_role = $this->get_user_role($current_user);

        if ($current_user_role === 'admin') {
            $query = "UPDATE users SET username=?, email=?, first_name=?, last_name=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $username, $email, $first_name, $last_name, $user_id);

            if ($stmt->execute()) {
                return true;
            } else {
                echo $stmt->error;
                return false;
            }
        } else {
            echo "You do not have permission to perform this action.";
            return false;
        }
    }


    function register_user($username, $password, $email, $first_name, $last_name, $address = '', $postal_code = '', $phone_number = ''): bool
    {
        $pass = sha1($password);

        $query = "INSERT INTO users (username, password, email, first_name, last_name, address, postal_code, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssss", $username, $pass, $email, $first_name, $last_name, $address, $postal_code, $phone_number);

        if ($stmt->execute()) {
            return true;
        } else {
            echo $stmt->error;
            return false;
        }
    }
}
