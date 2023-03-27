<?php

namespace Controllers;

use Models\UserModel;

require_once 'Models/UserModel.php';
require_once 'Controllers/PageController.php';

class AdminController
{
    private UserModel $model;
    private PageController $pageController;

    function __construct($conn)
    {
        $this->model = new UserModel($conn);
        $this->pageController = new PageController();
    }

    function handleAdmin(): void
    {

        $this->pageController->render_header("Admin Page");
        $users = $this->model->get_all_users();
        require_once 'Views/admin_view.php';
        $this->pageController->render_footer();
    }

    function handleDeleteUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            exit();
        }
        $user_id = $_POST['user_id'];

        if ($this->model->delete_user($_SESSION['USER_ID'], $user_id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    function handleEditUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            exit();
        }

        if (!isset($_SESSION['USER_ID'])) {
            http_response_code(401); // Unauthorized
            exit();
        }

        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $current_user_id = $_SESSION['USER_ID'];

        $result = $this->model->edit_user($current_user_id, $user_id, $username, $email, $first_name, $last_name);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true]);
            http_response_code(200);
        } else {
            echo json_encode(['success' => false]);
            http_response_code(500);
        }
    }
}
