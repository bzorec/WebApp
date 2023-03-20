<?php
include_once('header.php');

// Funkcija za prikaz uporabnikovih oglasov
function showAds()
{
    global $conn;
    $user_id = $_SESSION["USER_ID"];
    $query = "SELECT * FROM ads WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        echo '<div class="ad card mb-4">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" class="card-img-top" alt="' . $row['title'] . '"/>';
        echo '<div class="card-body">';
        echo '<h3 class="card-title">' . $row['title'] . '</h3>';
        echo '<p class="card-text">' . $row['description'] . '</p>';
        echo '<a href="edit_ad.php?id=' . $row['id'] . '" class="btn btn-primary">Uredi</a> <a href="delete_ad.php?id=' . $row['id'] . '" class="btn btn-danger">Izbriši</a>';
        echo '</div>';
        echo '</div>';
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="container my-5">
                <h2 class="mb-4">Moji oglasi</h2>
                <a href="publish.php" class="btn btn-success mb-3">Dodaj oglas</a>
                <div class="row">
                    <?php showAds(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>
