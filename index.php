<?php
include_once('header.php');

// Funkcija prebere oglase iz baze in vrne polje objektov
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

//Preberi oglase iz baze
$ads = get_ads();

//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
foreach ($ads as $ad) {
    ?>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($ad->image); ?>" alt="ad"
                     class="img-fluid rounded-start" style="max-height: 250px;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $ad->title; ?></h5>
                    <p class="card-text"><?php echo $ad->category_name; ?></p>
                    <a href="ad.php?id=<?php echo $ad->id; ?>" class="btn btn-primary">Preberi več</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

include_once('footer.php');
?>
