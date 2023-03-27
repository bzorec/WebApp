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

    private function CheckUserLogedIn(): void
    {
        if (!isset($_SESSION["USER_ID"])) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    }

    public function handleAddComment($ad_id, $userId, $commentText, $ip): bool
    {
        $this->CheckUserLogedIn();

        if (!$this->userModel->user_exists($userId)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        $this->commentModel->add_comment($ad_id, $userId, $commentText, $ip);

        return true;
    }

    public function handleGetComments($ad_id): array
    {
        $comments = array();
        try {
            $commentObjects = $this->commentModel->get_comments($ad_id);
            foreach ($commentObjects as $comment) {
                $user = $this->userModel->getUserById($comment['user_id']);
                $commentArray = array(
                    'id' => $comment['id'],
                    'content' => $comment['content'],
                    'created_at' => $comment['created_at'],
                    'ip_address' => $comment['ip_address'],
                    'user' => $user ? $user['username'] : ''
                );
                $comments[] = $commentArray;
            }
        } catch (Exception $e) {
            error_log('Error retrieving comments: ' . $e->getMessage());
        }
        return $comments;
    }

    public function handleDeleteComment(): array
    {
        $this->CheckUserLogedIn();

        $userId = $_SESSION["USER_ID"];

        if (!$this->userModel->user_exists($userId)) {
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
