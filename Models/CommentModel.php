<?php

namespace Models;

use InvalidArgumentException;
use mysqli;

class CommentModel
{
    private mysqli $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->conn->set_charset("UTF8");
    }

    function add_comment($adId, $userId, $commentText, $ip): bool
    {
        $query = "INSERT INTO comments (ad_id, user_id, content, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $stmt->bind_param("iiss", $adId, $userId, $commentText, $ipAddress);

        if ($stmt->execute()) {
            return true;
        } else {
            echo mysqli_error($this->conn);
            return false;
        }
    }


    function get_comments($adId): array
    {
        $query = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE ad_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $adId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return [];
        }

        $comments = array();
        while ($comment = $res->fetch_assoc()) {
            $comments[] = $comment;
        }
        return $comments;
    }


    function delete_comment($commentId): bool
    {
        $query = "DELETE FROM comments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $commentId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function comment_exists($commentId): bool
    {
        $query = "SELECT * FROM comments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function is_comment_owner($commentId, $userId): bool
    {
        $query = "SELECT * FROM comments WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $commentId, $userId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
