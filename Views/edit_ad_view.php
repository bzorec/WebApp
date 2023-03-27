<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h2 class="fw-bold mt-4 mb-3">Uredi oglas</h2>
            <form action="/index.php?page=user_ads&action=edit_user_ad&ad_id=<?php echo $ad_id; ?>" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Naslov</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo $ad->title; ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Vsebina</label>
                    <textarea name="description" id="description" rows="10" cols="50"
                              class="form-control"><?php echo $ad->description; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategorija</label>
                    <select name="category" id="category" class="form-select">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php if ($ad->category_id == $category['id']) echo 'selected'; ?>>
                                <?php echo $category['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="submit" name="submit" value="Shrani" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
