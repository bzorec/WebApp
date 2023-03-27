<?php

namespace Controllers;

use Models\AdModel;
use function MongoDB\BSON\toJSON;

require_once 'Models/AdModel.php';

class UserAdsController
{
    private AdModel $model;
    private PageController $pageController;

    function __construct($conn)
    {
        $this->model = new AdModel($conn);
        $this->pageController = new PageController($conn);
    }

    private function showAds(): void
    {
        $user_id = $_SESSION["USER_ID"];
        $ads = $this->model->get_user_ads($user_id);

        foreach ($ads as $ad) {
            echo '<div class="ad card mb-4">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($ad['image']) . '" class="card-img-top img-fluid" alt="' . $ad['title'] . '"/>';
            echo '<div class="card-body">';
            echo '<h3 class="card-title">' . $ad['title'] . '</h3>';
            echo '<p class="card-text">' . $ad['description'] . '</p>';
            echo '<a href="/index.php?page=user_ads&action=edit_user_ad&ad_id=' . $ad['id'] . '" class="btn btn-primary">Uredi</a> <a href="/index.php?page=user_ads&action=delete_user_ad&ad_id=' . $ad['id'] . '" class="btn btn-danger">Izbriši</a>';
            echo '</div>';
            echo '</div>';
        }

    }

    public function handleEditUserAds(): void
    {
        $ad_id = $_GET['ad_id'];
        $ad = $this->model->getAd($ad_id);

        if ($_SESSION['USER_ID'] !== $ad->user_id) {
            echo 'You do not have permission to edit this ad.';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category_id = $_POST['category'];

            if ($this->model->update_ad($ad_id, $title, $description, $category_id)) {
                header("Location: /index.php?page=user_ads");
                return;
            } else {
                echo "An error occurred while updating the ad.";
            }
        }

        $categories = $this->model->getCategories();
        $this->pageController->render_header('Uredi oglas');
        require_once 'Views/edit_ad_view.php';
        $this->pageController->render_footer();
    }


    public function handleDeleteUserAds(): void
    {
        if (isset($_SESSION["USER_ID"]) && isset($_GET["ad_id"])) {
            $ad_id = $_GET["ad_id"];
            $user_id = $_SESSION["USER_ID"];

            // check if the user is the owner of the ad
            $ad = $this->model->getAd($ad_id);
            if ($ad && $ad->user_id == $user_id) {
                // delete the ad
                if ($this->model->deleteAd($ad_id, $user_id)) {
                    header("Location: /index.php?page=user_ads&action=delete_user_ad&success=1&ad_id=$ad_id");
                    return;
                } else {
                    echo "An error occurred while deleting the ad.";
                }
            } else {
                echo "The ad does not exist or you are not the owner of the ad.";
            }
        } else {
            echo "The ad does not exist or you are not logged in.";
        }

        header("Location: /index.php?page=user_ads");
    }

    public function handleUserAds(): void
    {
        $this->pageController->render_header("User_ADS");
        require_once 'Views/user_ads_view.php';
        $this->pageController->render_footer();
    }
}