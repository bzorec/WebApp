<?php

use Controllers\AdDetailsController;
use Controllers\HomeController;
use Controllers\LoginController;
use Controllers\RegisterController;
use Controllers\AdminController;
use Models\AdModel;

require_once 'config.php';
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();

require_once 'Controllers/HomeController.php';
require_once 'Controllers/LoginController.php';
require_once 'Controllers/RegisterController.php';
require_once 'Controllers/AdminController.php';
require_once 'Controllers/AdDetailsController.php';

$homeController = new HomeController($conn);
$loginController = new LoginController($conn);
$registerController = new RegisterController($conn);
$adminController = new AdminController($conn);
$adDetailsController = new AdDetailsController($conn);

if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'login':
            $loginController->handleLogin();
            break;
        case 'register':
            $registerController->handleRegister();
            break;
        case 'admin':
            $adminController->handleAdmin();
            break;
        case 'ad-details':
            $id = $_GET['id'];
            $adDetailsController->handleAdDetails($id);
            break;
        default:
            $homeController->handleHome();
            break;
    }
} else {
    $homeController->handleHome();
}

