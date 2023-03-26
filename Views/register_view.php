<?php global $error ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h2 class="mb-4">Registracija</h2>
            <form action="/index.php?page=register" method="POST">
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
                    <label><?php if (isset($error)) {
                            echo $error;
                        } ?></label>
                </div>
            </form>
        </div>
    </div>
</div>
