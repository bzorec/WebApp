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
        $this->pageController->render_header("Prijava");
        $error = "";
        if (isset($_POST["submit"])) {
            if (($user_id = $this->userModel->validate_login($_POST["username"], $_POST["password"])) >= 0) {
                $_SESSION["USER_ID"] = $user_id;
                $_SESSION["ROLE"] = $this->userModel->get_user_role($user_id);
                header("Location: index.php");
                die();
            } else {
                $error = "Prijava ni uspela.";
            }
        }
        require_once('Views/login_view.php');
        $this->pageController->render_footer();
    }

    public function handleLogout(): void
    {
        session_start(); //Naloži sejo
        session_unset(); //Odstrani sejne spremenljivke
        session_destroy(); //Uniči sejo
        header("Location: index.php");
    }
}
