<?php

namespace Controllers;

use Models\UserModel;
use Controllers\PageController;

require_once 'Models/UserModel.php';

class RegisterController
{
    private UserModel $model;
    private PageController $pageController;

    function __construct($conn)
    {
        $this->model = new UserModel($conn);
        $this->pageController = new PageController($conn);

    }

    function handleRegister(): void
    {
        $this->pageController->render_header("Domov");

        if (isset($_POST["submit"])) {
            if ($_POST["password"] != $_POST["repeat_password"]) {
                $error = "Passwords do not match.";
            } else if ($this->model->username_exists($_POST["username"])) {
                $error = "Username already taken.";
            } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } else if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"]) || empty($_POST["first_name"]) || empty($_POST["last_name"])) {
                $error = "Please fill in all required fields.";
            } else if ($this->model->register_user($_POST["username"], $_POST["password"], $_POST["email"], $_POST["first_name"], $_POST["last_name"], $_POST["address"], $_POST["postal_code"], $_POST["phone_number"])) {
                header("Location: /index.php?page=login");
                die();
            } else {
                $error = "An error occurred while registering the user.";
            }
        }

        require_once 'Views/register_view.php';
        $this->pageController->render_footer();

    }

}
