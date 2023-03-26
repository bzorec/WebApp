<?php
include_once('header_view.php');

function get_ads(): array
{
    global $conn;
    $query = "SELECT ads.*, categories.name AS category_name FROM ads
              JOIN categories ON ads.category_id = categories.id
              ORDER BY ads.publish_date DESC;";
    $res = $conn->query($query);
    $ads = array();
    while ($ad = $res->fetch_object()) {
        $ads[] = $ad;
    }
    return $ads;
}

$ads = get_ads();

foreach ($ads as $ad) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <a href="/index.php?page=ad-details&id=<?php echo $ad->id; ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($ad->image); ?>" alt="ad"
                                     class="img-fluid rounded-start" style="max-height: 250px;">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $ad->title; ?></h5>
                                <p class="card-text"><?php echo $ad->category_name; ?></p>
                                <a href="/index.php?page=ad-details&id=<?php echo $ad->id; ?>" class="btn btn-primary">Preberi
                                    več</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

include_once('footer_view.php');
?>