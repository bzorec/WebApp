<?php

namespace Controllers;

use Models\AdModel;

require_once "Models\AdModel.php";

class AdDetailsController
{
    private AdModel $adModel;
    private PageController $pageController;

    public function __construct($conn)
    {
        $this->adModel = new AdModel($conn);
        $this->pageController = new PageController($conn);

    }

    public function handleAdDetails($id): void
    {
        $ad = $this->adModel->getAd($id);
        if ($ad == null) {
            echo "Oglas ne obstaja.";
            die();
        }
        $img_data = base64_encode($ad->image);

        $this->pageController->render_header("Podrobnosti");
        require_once('Views/ad_details_view.php');
        $this->pageController->render_footer();
    }
}

