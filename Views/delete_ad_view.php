<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card my-5">
                <div class="card-body">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                        <h2 class="card-title">Izbriši oglas</h2>
                        <p class="card-text">Oglas je bil uspešno izbrisan.</p>
                    <?php else: ?>
                        <h2 class="card-title">Izbriši oglas</h2>
                        <p class="card-text"><?php echo $error; ?></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
