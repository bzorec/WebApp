<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h2 class="mb-4">Prijava</h2>
            <form action="/index.php?page=login" method="POST">
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
