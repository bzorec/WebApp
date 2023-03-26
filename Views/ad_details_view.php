<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $ad->title; ?></h4>
                    <h6 class="card-subtitle mb-2 text-muted">Cena: <?php echo $ad->price; ?> EUR</h6>
                    <p class="card-text"><?php echo $ad->description; ?></p>
                    <img src="data:image/jpg;base64, <?php echo $img_data; ?>" class="card-img-top" alt="ad"/>
                    <p class="card-text">Objavil: <?php echo $ad->username; ?></p>
                    <p class="card-text">E-pošta: <?php echo $ad->email; ?></p>
                    <p class="card-text">Telefon: <?php echo $ad->phone_number; ?></p>
                    <p class="card-text">Kraj: <?php echo $ad->address; ?></p>
                    <p class="card-text">Poštna številka: <?php echo $ad->postal_code; ?></p>
                    <p class="card-text">Kategorija: <?php echo $ad->name; ?></p>
                    <a href="../index.php" class="btn btn-primary">Nazaj</a>
                </div>
            </div>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="comments-section">
                <h4>
                    Comments
                    <button type="button" class="btn btn-link float-end" data-bs-toggle="collapse" data-bs-target="#comment-list" aria-expanded="false" aria-controls="comment-list">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </h4>
                <div class="collapse" id="comment-list">
                    <ul class="comment-list">
                    </ul>
                </div>
            </div>
            <div class="comment-form">
                <h4>Leave a comment:</h4>
                <form id="comment-form">
                    <div class="form-group">
                        <textarea class="form-control" id="comment-content"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: 'API/comment_api.php?ad_id=<?php echo $ad->id; ?>',
            type: 'GET',
            success: function (comments) {
                if (comments.length > 2) {
                    for (var i = 0; i < comments.length; i++) {
                        var comment = comments[i];
                        var commentHtml = '<li class="comment-item">' + '<div class="comment-header">' + '<span class="comment-author">' + comment.username + '</span>' + '<span class="comment-date">' + comment.created_at + '</span>' + '</div>' + '<div class="comment-content">' + '<p>' + comment.content + '</p>' + '</div>' + '</li>';
                        $('.comment-list').append(commentHtml);
                    }
                } else {
                    $('.comment-list').append('<li>No comments yet.</li>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Failed to retrieve comments: ' + errorThrown);
            }
        });

        $('#comment-form').submit(function (event) {
            event.preventDefault();

            let content = $('#comment-content').val();

            $.ajax({
                url: 'API/comment_api.php',
                type: 'POST',
                data: JSON.stringify({
                    ad_id: <?php echo $ad->id; ?>,
                    user_id: <?php
                    if (isset($_SESSION["USER_ID"])) {
                        echo $_SESSION["USER_ID"];
                    } else echo "''"
                    ?>,
                    content: content
                }),
                contentType: 'application/json',
                success: function () {
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Failed to add comment: ' + errorThrown);
                }
            });
        });
    });
</script>

