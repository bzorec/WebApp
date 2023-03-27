<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h2 class="mb-4">Objavi oglas</h2>
            <form action="/index.php?page=publish" enctype="multipart/form-data" method="POST">
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
                        <?php foreach ($categories as $category) {
                            echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                        } ?>
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
