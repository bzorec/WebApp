<?php
include_once('header.php');
global $conn;
$error = "";
if (isset($_POST["submit"])) {
    $ad_id = $_POST["ad_id"];
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $category_id = $_POST["category"];

    $query = "UPDATE ads SET title='$title', description='$desc', category_id='$category_id' WHERE id='$ad_id' AND user_id='" . $_SESSION["USER_ID"] . "'";
    if ($conn->query($query)) {
        header("Location: my_ads.php");
        die();
    } else {
        $error = "Prišlo je do napake pri urejanju oglasa.";
    }
} else {
    $ad_id = $_GET["id"];

    $query = "SELECT * FROM ads WHERE id='$ad_id' AND user_id='" . $_SESSION["USER_ID"] . "'";
    $result = $conn->query($query);
    if ($result->num_rows != 1) {
        header("Location: my_ads.php");
        die();
    }

    $ad = $result->fetch_assoc();
    $title = $ad["title"];
    $desc = $ad["description"];
    $category_id = $ad["category_id"];
}

?>
<h2 class="fw-bold mt-4 mb-3">Uredi oglas</h2>
<form action="/edit_ad.php" method="POST">
    <input type="hidden" name="ad_id" value="<?php echo $ad_id; ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Naslov</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo $title; ?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Vsebina</label>
        <textarea name="description" id="description" rows="10" cols="50" class="form-control"><?php echo $desc; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Kategorija</label>
        <select name="category" id="category" class="form-select">
            <?php
            $query = "SELECT id, name FROM categories";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '"';
                if ($category_id == $row['id']) {
                    echo ' selected';
                }
                echo '>' . $row['name'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <input type="submit" name="submit" value="Shrani" class="btn btn-primary">
    </div>
    <div class="mb-3">
        <label><?php echo $error; ?></label>
    </div>
</form>

<?php
include_once('footer.php');
?>
