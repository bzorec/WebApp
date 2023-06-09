<?php

use Controllers\CommentController;
use Models\CommentModel;

require_once('../config.php');
require_once('../Database.php');
require_once('../Models/CommentModel.php');
require_once('../Models/UserModel.php');
require_once('../Controllers/CommentController.php');

$db = new Database();
$conn = $db->getConnection();

$commentController = new CommentController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['user_id']) && isset($data['ad_id']) && isset($data['content']) && isset($data['ip_address'])) {
        $user_id = $data['user_id'];
        $ad_id = $data['ad_id'];
        $content = $data['content'];
        $ip = $data['ip_address'];
        $result = $commentController->handleAddComment($ad_id, $user_id, $content, $ip);
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Comment added successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to add comment.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input data.']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['ad_id']) && !isset($_GET['limit'])) {
        $ad_id = $_GET['ad_id'];
        $result = $commentController->handleGetComments($ad_id);
        http_response_code(200);
        echo json_encode($result);
    } else if (isset($_GET['ad_id']) && isset($_GET['limit'])) {
        $limit = $_GET['limit'];
        $ad_id = $_GET['ad_id'];
        $result = $commentController->handleGetComments($ad_id, $limit);
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input data.']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['commentId'])) {
        $commentId = $data['commentId'];
        $result = $commentController->handleDeleteComment($commentId);
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Comment deleted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete comment.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
