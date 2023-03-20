<?php
include_once('header.php');

// Funkcija preveri, ali v bazi obstaja uporabnik z določenim imenom in vrne true, če obstaja.
function username_exists($username): bool
{
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT * FROM users WHERE username='$username'";
    $res = $conn->query($query);
    return mysqli_num_rows($res) > 0;
}

// Funkcija ustvari uporabnika v tabeli users. Poskrbi tudi za ustrezno šifriranje uporabniškega gesla.
function register_user($username, $password, $email, $first_name, $last_name, $address = '', $postal_code = '', $phone_number = ''): bool
{
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $pass = sha1($password);
    /*
        Tukaj za hashiranje gesla uporabljamo sha1 funkcijo. V praksi se priporočajo naprednejše metode, ki k geslu dodajo naključne znake (salt).
        Več informacij:
        http://php.net/manual/en/faq.passwords.php#faq.passwords
        https://crackstation.net/hashing-security.htm
    */
    $email = mysqli_real_escape_string($conn, $email);
    $name = mysqli_real_escape_string($conn, $first_name);
    $surname = mysqli_real_escape_string($conn, $last_name);
    $address = mysqli_real_escape_string($conn, $address);
    $postal_code = mysqli_real_escape_string($conn, $postal_code);
    $phone_number = mysqli_real_escape_string($conn, $phone_number);

    $query = "INSERT INTO users (username, password, email, first_name, last_name, address, postal_code, phone_number) VALUES ('$username', '$pass', '$email', '$first_name', '$last_name', '$address', '$postal_code', '$phone_number');";
    if ($conn->query($query)) {
        return true;
    } else {
        echo mysqli_error($conn);
        return false;
    }
}

$error = "";
if (isset($_POST["submit"])) {
    /*
    VALIDATION: we need to verify if the user has entered the correct data (unique username, password length, email format,...)
    Always perform data validation on the server side. Client-side validation (e.g. JavaScript) only provides a better user interface, as users are informed of errors immediately. Client-side validation does not provide any security, as users can easily bypass it (e.g. developer tools,...).
    */
//Check if passwords match
    if ($_POST["password"] != $_POST["repeat_password"]) {
        $error = "Passwords do not match.";
    } //Check if username exists
    else if (username_exists($_POST["username"])) {
        $error = "Username already taken.";
    } //Check if email format is valid
    else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } //Check if required fields are empty
    else if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"]) || empty($_POST["first_name"]) || empty($_POST["last_name"])) {
        $error = "Please fill in all required fields.";
    } //Register user
    else if (register_user($_POST["username"], $_POST["password"], $_POST["email"], $_POST["first_name"], $_POST["last_name"], $_POST["address"], $_POST["postal_code"], $_POST["phone_number"])) {
        header("Location: login.php");
        die();
    } //Registration failed
    else {
        $error = "An error occurred while registering the user.";
    }
}

?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="mb-4">Registracija</h2>
                <form action="/register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Uporabniško ime</label>
                        <input type="text" required name="username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Geslo</label>
                        <input type="password" required name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="repeat_password" class="form-label">Ponovi geslo</label>
                        <input type="password" required name="repeat_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-naslov</label>
                        <input type="email" required name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ime</label>
                        <input type="text" required name="first_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Priimek</label>
                        <input type="text" required name="last_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Naslov</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Poštna številka</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Telefonska številka</label>
                        <input type="text" name="phone_number" class="form-control">
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