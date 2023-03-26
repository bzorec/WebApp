<?php

namespace Controllers;
require_once("config.php");
require_once("PageController.php");

class HomeController
{
    private PageController $pageController;

    public function __construct($conn)
    {
        $this->pageController = new PageController($conn);
    }

    public function handleHome(): void
    {
        $this->pageController->render_header("Domov");
        require_once 'Views/home_view.php';
        $this->pageController->render_footer();
    }
}