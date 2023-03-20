<?php
include_once('header.php');

function validate_login($username, $password): int
{
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $pass = sha1($password);
    $query = "SELECT * FROM vaja1.users WHERE username='$username' AND password='$pass'";
    $res = $conn->query($query);
    if ($user_obj = $res->fetch_object()) {
        return $user_obj->id;
    }
    return -1;
}

$error = "";
if (isset($_POST["submit"])) {
    //Preveri prijavne podatke
    if (($user_id = validate_login($_POST["username"], $_POST["password"])) >= 0) {
        //Zapomni si prijavljenega uporabnika v seji in preusmeri na index.php
        $_SESSION["USER_ID"] = $user_id;
        header("Location: index.php");
        die();
    } else {
        $error = "Prijava ni uspela.";
    }
}
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="mb-4">Prijava</h2>
                <form action="/login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Uporabniško ime</label>
                        <input type="text" required name="username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Geslo</label>
                        <input type="password" required name="password" class="form-control">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Pošlji</button>
                    <div class="mt-3">
                        <label><?php echo $error; ?></label>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
include_once('footer.php');
?>