<?php

namespace Controllers;

class PageController
{
    public function render_header($title): void
    {
        $page_title = $title;
        require_once 'Views/header_view.php';
    }

    public function render_footer(): void
    {
        require_once 'Views/footer_view.php';
    }
}