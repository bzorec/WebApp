<?php
include_once('header.php');

// Funkcija izbere oglas s podanim ID-jem. Doda tudi uporabnika in kategorijo, ki sta objavila oglas.
function get_ad($id)
{
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT ads.*, users.username, categories.name as category_name FROM vaja1.ads LEFT JOIN vaja1.users ON users.id = ads.user_id LEFT JOIN vaja1.categories ON categories.id = ads.category_id WHERE ads.id = $id;";
    $res = $conn->query($query);
    if ($obj = $res->fetch_object()) {
        return $obj;
    }
    return null;
}

if (!isset($_GET["id"])) {
    echo "Manjkajoči parametri.";
    die();
}
$id = $_GET["id"];
$ad = get_ad($id);
if ($ad == null) {
    echo "Oglas ne obstaja.";
    die();
}
//Base64 koda za sliko (hexadecimalni zapis byte-ov iz datoteke)
$img_data = base64_encode($ad->image);
?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title"><?php echo $ad->title; ?></h4>
        <h6 class="card-subtitle mb-2 text-muted">Cena: <?php echo $ad->price; ?> EUR</h6>
        <p class="card-text"><?php echo $ad->description; ?></p>
        <img src="data:image/jpg;base64, <?php echo $img_data; ?>" class="card-img-top" alt="ad"/>
        <p class="card-text">Objavil: <?php echo $ad->username; ?></p>
        <p class="card-text">E-pošta: <?php echo $ad->email; ?></p>
        <p class="card-text">Telefon: <?php echo $ad->phone_number; ?></p>
        <p class="card-text">Kraj: <?php echo $ad->address; ?></p>
        <p class="card-text">Poštna številka: <?php echo $ad->postal_code; ?></p>
        <p class="card-text">Kategorija: <?php echo $ad->category_name; ?></p>
        <a href="index.php" class="btn btn-primary">Nazaj</a>
    </div>
</div>
<hr/>

<?php include_once('footer.php'); ?>
