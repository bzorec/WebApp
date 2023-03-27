<?php

namespace Controllers;

use Models\AdModel;
use mysqli;

class PublishController
{

    private mysqli $conn;
    private PageController $pageController;
    private AdModel $model;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->pageController = new PageController();
        $this->model = new AdModel($this->conn);
    }

    public function getCategories(): array
    {
        return $this->model->getCategories();
    }

    public function handlePublish(): void
    {
        $this->pageController->render_header("Publish");
        $error = "";
        if (isset($_POST["submit"])) {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                die("Upload failed with error code " . $_FILES['file']['error']);
            }

            $info = getimagesize($_FILES['image']['tmp_name']);
            if ($info === FALSE) {
                die("Unable to determine image type of uploaded file");
            }

            if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
                die("Not a gif/jpeg/png");
            }

            if (!empty($_FILES["image"]["tmp_name"])) {
                if ($this->model->publish($_POST["title"], $_POST["description"], $_FILES["image"], $_POST["category"], $_POST["price"])) {
                    header("Location: index.php");
                    die();
                } else {
                    $error = "Prišlo je do napake pri objavi oglasa.";
                }
            } else {
                $error = "Niste naložili slike.";
            }
        }
        $categories = $this->getCategories();
        $data = array(
            'categories' => $categories,
            'error' => $error
        );
        require_once "Views/publish_view.php";
        $this->pageController->render_footer();
    }
}