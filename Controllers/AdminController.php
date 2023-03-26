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
        $users = $this->model->get_all_users();

        $this->pageController->render_header("Admin Page");
        require_once 'Views/admin_view.php';
        $this->pageController->render_footer();
    }
}
