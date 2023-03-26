<?php

namespace Controllers;

use Exception;
use Models\CommentModel;
use Models\UserModel;
use mysqli;

require_once('../Models/CommentModel.php');
require_once('../Models/UserModel.php');

class CommentController
{
    private UserModel $userModel;
    private CommentModel $commentModel;

    public function __construct(mysqli $conn)
    {
        $this->userModel = new UserModel($conn);
        $this->commentModel = new CommentModel($conn);
    }


    public function handleAddComment(): array
    {
        if (!isset($_SESSION["USER_ID"])) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }

        $userId = $_SESSION["USER_ID"];

        if (!$this->userModel->username_exists($userId)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        $postData = file_get_contents("php://input");
        $postJson = json_decode($postData, true);
        $postId = $postJson["postId"];
        $commentText = $postJson["commentText"];

        $this->commentModel->add_comment($postId, $userId, $commentText);

        return ["success" => true];
    }

    function handleGetComments($ad_id): array
    {
        $comments = array();
        try {
            $comments = $this->commentModel->get_comments($ad_id);
        } catch (Exception $e) {
            error_log('Error retrieving comments: ' . $e->getMessage());
        }
        return $comments;
    }

    public function handleDeleteComment(): array
    {
        if (!isset($_SESSION["USER_ID"])) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }

        $userId = $_SESSION["USER_ID"];

        if (!$this->userModel->username_exists($userId)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        $postData = file_get_contents("php://input");
        $postJson = json_decode($postData, true);
        $commentId = $postJson["commentId"];

        if (!$this->commentModel->comment_exists($commentId)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        if (!$this->commentModel->is_comment_owner($commentId, $userId)) {
            header("HTTP/1.1 403 Forbidden");
            exit();
        }

        $this->commentModel->delete_comment($commentId);

        return ["success" => true];
    }
}
