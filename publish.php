<?php
include_once('header.php');

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

    $query = "INSERT INTO ads (title, description, user_id, image, category_id, price)
              VALUES ('$title', '$desc', '$user_id', '$img_file', '$category_id', '$price')";
    if ($conn->query($query)) {
        return true;
    } else {
        //Izpis MYSQL napake z: echo mysqli_error($conn);
        return false;
    }
}

$error = "";
if (isset($_POST["submit"])) {
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
    <h2 class="mb-4">Objavi oglas</h2>
    <form action="/publish.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Naslov</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Cena</label>
            <input type="number" class="form-control" name="price" id="price" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Vsebina</label>
            <textarea class="form-control" name="description" id="description" rows="10" required></textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategorija</label>
            <select class="form-select" name="category" id="category" required>
                <?php
                global $conn;
                $query = "SELECT id, name FROM categories";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Slika</label>
            <input type="file" class="form-control" name="image" id="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Objavi</button>
        <br>
        <label class="mt-3"><?php echo $error; ?></label>
    </form>

<?php
include_once('footer.php');
?>