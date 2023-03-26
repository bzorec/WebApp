<?php
include_once('header_view.php');

// Funkcija vstavi nov oglas v bazo. Preveri tudi, ali so podatki pravilno izpolnjeni. 
// Vrne false, če je prišlo do napake oz. true, če je oglas bil uspešno vstavljen.
function publish($title, $desc, $img, $category_id, $price): bool
{
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    $user_id = $_SESSION["USER_ID"];

    //Preberemo vsebino (byte array) slike
    $img_file = file_get_contents($img["tmp_name"]);
    //Pripravimo byte array za pisanje v bazo (v polje tipa LONGBLOB)
    $img_file = mysqli_real_escape_string($conn, $img_file);

    $query = "INSERT INTO vaja1.ads (title, description, user_id, image, category_id, price)
              VALUES ('$title', '$desc', '$user_id', '$img_file', '$category_id', '$price')";
    if ($conn->query($query)) {
        return true;
    } else {
        echo mysqli_error($conn);
        return false;
    }
}

$error = "";
if (isset($_POST["submit"])) {
    // never assume the upload succeeded
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
        if (publish($_POST["title"], $_POST["description"], $_FILES["image"], $_POST["category"], $_POST["price"])) {
            header("Location: index.php");
            die();
        } else {
            $error = "Prišlo je do napake pri objavi oglasa.";
        }
    } else {
        $error = "Niste naložili slike.";
    }
}

?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="mb-4">Objavi oglas</h2>
                <form action="/publish.php" enctype="multipart/form-data" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Naslov</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Cena</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Vsebina</label>
                        <textarea class="form-control" name="description" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategorija</label>
                        <select class="form-select" name="category" required>
                            <?php
                            global $conn;
                            $query = "SELECT id, name FROM vaja1.categories";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Slika</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Objavi</button>
                    <br>
                    <label class="mt-3"><?php echo $error; ?></label>
                </form>
            </div>
        </div>
    </div>
<?php
include_once('footer_view.php');
?>