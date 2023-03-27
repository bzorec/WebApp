<!DOCTYPE html>
<html lang="si">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaja 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<h1 class="text-center mt-3">Oglasnik</h1>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php?page=home">Domov</a>
                </li>
                <?php
                if (isset($_SESSION["USER_ID"])) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php?page=user_ads">Moji oglasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php?page=publish">Objavi oglas</a>
                    </li>
                    <?php
                    if ($_SESSION["ROLE"] == "admin") {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php?page=admin">Admin</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php?page=logout">Odjava</a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php?page=login">Prijava</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php?page=register">Registracija</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>


<hr/>