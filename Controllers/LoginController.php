<?php

namespace Controllers;

use Models\UserModel;

class LoginController
{
    private UserModel $userModel;
    private PageController $pageController;

    public function __construct($conn)
    {
        $this->userModel = new UserModel($conn);
        $this->pageController = new PageController($conn);
    }

    public function handleLogin(): void
    {
        $error = "";
        if (isset($_POST["submit"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $user = $this->userModel->getUserByUsernameAndPassword($username, $password);
            if ($user != null) {
                $_SESSION["USER_ID"] = $user->getId();
                $_SESSION["ROLE"] = $user->getRole();
                header("Location: index.php");
                die();
            } else {
                $error = "Prijava ni uspela.";
            }
        }
        $this->pageController->render_header("Prijava");
        require_once('Views/login_view.php');
        $this->pageController->render_footer();
    }
}
